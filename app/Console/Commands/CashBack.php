<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Withdraw;
use App\Payment;

class CashBack extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cashback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    const RANKS = [
        1 => [
            'cashback' => 3,
            'comission' => 2
        ],
        2 => [
            'cashback' => 4,
            'comission' => 4 //4
        ],
        3 => [
            'cashback' => 5,
            'comission' => 0 
        ],
        4 => [
            'cashback' => 6,
            'comission' => 2
        ],
        5 => [
            'cashback' => 8,
            'comission' => 0
        ],
        6 => [
            'cashback' => 10,
            'comission' => 0
        ],
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $chunks = User::select('id', 'current_rang')->where('current_rang', '>', '0')->get()->chunk(50);

        foreach ($chunks as $users) {
            foreach ($users as $user) {
                $wdws = round(Withdraw::query()->where([['created_at', '>=', \Carbon\Carbon::now()->subMonth()],['user_id', $user->id], ['status', 1], ['fake', 0]])->sum('sumNoCom'), 2);
                $pays = round(Payment::query()->where([['created_at', '>=', \Carbon\Carbon::now()->subMonth()], ['user_id', $user->id], ['status', 1], ['fake', 0]])->sum('sum'), 2);
                $perc = self::RANKS[$user['current_rang']]['cashback'];
                $sum = $pays - $wdws;

                if( $sum > 0 ) {
                    $cashback = round($sum * ($perc / 100), 2);
                    $user->increment('balance', $cashback);
                    $user->increment('wager', $cashback * 10);
                }
            }
        }
        \Log::info("Выдан кэшбэк");
    }
}
