<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Coin;
use App\Game;
use App\Profit;
use App\Wheel;
use App\Withdraw;
use App\WheelBets;
use Carbon\Carbon;
use App\Stat;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class IndexController extends Controller
{
    protected $auth;
    protected $token;

    public function __construct()
    {
        parent::__construct();
        $this->token = Cookie::get('token') ?? '';
        $this->auth = User::where('api_token', $this->token)->first();
    }

    const SYSTEMS = [
        //1 => [
        //    'title' => 'ЮMoney',
        //    'title1' => 'ЮMoney',
        //    'comission' => 2
        //],
        1 => [
            'title' => 'ЮMoney - FK',
            'title1' => 'ЮMoney',
            'comission' => 4 //4
        ],
        2 => [
            'title' => 'Payeer',
            'title1' => 'Payeer',
            'comission' => 0 
        ],
        //4 => [
        //    'title' => 'Qiwi - XMpay',
        //    'title1' => 'QIWI',
        //    'comission' => 2
        //],
        4 => [
            'title' => 'Qiwi - FK',
            'title1' => 'QIWI',
            'comission' => 5 //5%
        ],
        5 => [
            'title' => 'Beeline',
            'title1' => 'Beeline',
            'comission' => 0
        ],
        6 => [
            'title' => 'MegaFon',
            'title1' => 'MegaFon',
            'comission' => 0
        ],
        7 => [
            'title' => 'MTS',
            'title1' => 'MTS',
            'comission' => 0
        ],
        //9 => [
        //    'title' => 'Карты - XMpay',
        //    'title1' => 'Карты',
        //    'comission' => 1, //3%
        //    'plus' => 0
        //],
        9 => [
            'title' => 'Карты - FK',
            'title1' => 'Карты',
            'comission' => 4, //4%
            'plus' => 70 //70
        ],
        10 => [
            'title' => 'MasterCard',
            'title1' => 'Карты',
            'comission' => 0, //2%
            'plus' => 50
        ],
        11 => [
            'title' => 'Tele2',
            'title1' => 'Tele2',
            'comission' => 0
        ],
        12 => [
            'title' => 'FKWallet',
            'title1' => 'FKWallet',
            'comission' => 3
        ],
        14 => [
            'title' => 'Piastrix',
            'title1' => 'Piastrix',
            'comission' => 3
        ],
        15 => [
            'title' => 'Qiwi - RUBpay',
            'title1' => 'QIWI',
            'comission' => 4
        ],
        16 => [
            'title' => 'Карты - RUBpay',
            'title1' => 'Карты',
            'comission' => 3, //3%
            'plus' => 50
        ],
        17 => [
            'title' => 'Qiwi - XMpay',
            'title1' => 'Qiwi',
            'comission' => 2, //3%
        ],
        18 => [
            'title' => 'FKWallet - XMpay',
            'title1' => 'FKWallet',
            'comission' => 3, //3%
        ],
        19 => [
            'title' => 'Карты - XMpay',
            'title1' => 'Карты',
            'comission' => 3, //3%
            'plus' => 50
        ],
        20 => [
            'title' => 'QIWI - GetPay',
            'title1' => 'QIWI',
            'comission' => 3, //5%
        ],
        21 => [
            'title' => 'Piastrix',
            'title1' => 'Piastrix',
            'comission' => 3, //5%
        ],
    ];

    public function index()
    {
        return view('app');
    }
    public function payment()
    {
        return view('pay');
    }
    public function getGroupVK()
    {
        return \App\Setting::query()->find(1)->vk_url;
    }
    public function getInfo()
    {
        return [
            'refshare' => \App\Setting::query()->find(1)->refshare
        ];
    }

    public function updateUser() {
        foreach(\App\PromocodeActivation::query()->get() as $use) {
            $promo = \App\Promocode::query()->find($use->promo_id);
            $user = User::query()->find($use->user_id);
            if ($user) {
                $user->increment('promo_dep_sum', $promo->sum);
            }
            
        }
        return "Ok";
    }

    public function updatePay() {
        foreach(\App\Payment::query()->get() as $pay) {
            $user = User::select('username')->find($pay->user_id);
            if($user){
                $pay->update(['username' => $user->username]);
            }
        }
    }

    public function topRef()
    {
        $chunks = User::select('username', 'id', 'link_reg')->where('id', '>', '0')->get()->chunk(10);

        foreach ($chunks as $users) {
            foreach ($users as $user) {
                //$ref_cnt = User::query()->where('referral_use', $user->id)->count();
                $ref_perc = $this->config->ref_perc;
                if ($user->ref_perc != 0) {
                    $ref_perc = $user->ref_perc;
                }
                $ref_sum = round(\App\ReferralPayment::query()->where('referral_id', $user->id)->sum('sum') * ($ref_perc / 100) + ($user->ref_bonus_cnt * 5), 2);
                \App\TopRefovods::create([
                    'username' => $user->username,
                    'user_id' => $user->id,
                    'ref_cnt' => $user->link_reg,
                    'sum' => $ref_sum
                ]);
            }
        }
    }

    public function deletePromo() {
        foreach(\App\Promocode::query()->get() as $promo) {
            $allUsed = \App\PromocodeActivation::query()->where('promo_id', $promo->id)->count('id');
            if ($allUsed != $promo->activation) {
                $promo->delete();
            }
        }
    }

    public function updateRef() {
        foreach(\App\ReferralPayment::query()->where('created_at', '>', '2022-06-16 19:30:00')->get() as $ref) {
            if ($ref->sum > 250) {
                $firstpay = \App\Payment::query()->where('user_id', $ref->user_id)->first();
                if($firstpay) {
                    $origin = date_create($firstpay->created_at);
                    $target = date_create('now');
                    $interval = date_diff($origin, $target);
                    $interval = $interval->format('%a');
                    if ( $interval > 21 ) {
                        $ref->shave = 1;
                        $ref->save();
                    }
                }
            }
        }
    }

    public function saveWallets(Request $r)
    {
        $user = User::query()->find($r->id);

        if ($r->walletQiwi != null) {
            $qiwi = User::query()->where([['wallet_qiwi', $r->walletQiwi], ['id', '!=', $r->id]])->first();
            if($qiwi) {
                return [
                    'success' => false,
                    'message' => 'Данный реквизит уже занят - '. $r->walletQiwi
                ];
            }
            $user->update([
                'wallet_qiwi' => $r->walletQiwi
            ]);
        }
        
        if ($r->walletCard != null) {
            if (strlen($r->walletCard) > 16 || strlen($r->walletCard) < 16) {
                return [
                    'success' => false,
                    'message' => 'Введите корректный кошелек'
                ];
            }
            $card = User::query()->where([['wallet_card', $r->walletCard], ['id', '!=', $r->id]])->first();
            if($card) {
                return [
                    'success' => false,
                    'message' => 'Данный реквизит уже занят - '. $r->walletCard
                ];
            }
            $user->update([
                'wallet_card' => $r->walletCard
            ]);
        }

        $qiwiwdw = Withdraw::query()->where([['user_id', $user->id], ['system', 20]])->count();
        $cardwdw = Withdraw::query()->where([['user_id', $user->id], ['system', 9]])->count();

        if ($r->walletFK != null) {
            if($qiwiwdw < 1 && $cardwdw < 1) {
                return [
                    'success' => false,
                    'message' => 'Чтобы привязать данный метод, необходимо иметь успешный вывод на QIWI или карту'
                ];
            }
            if (substr($r->walletFK, 0, 1) != "F") {
                return [
                    'success' => false,
                    'message' => 'Введите корректный кошелек'
                ];
            }
            $fk = User::query()->where([['wallet_fk', $r->walletFK], ['id', '!=', $r->id]])->first();
            if($fk) {
                return [
                    'success' => false,
                    'message' => 'Данный реквизит уже занят - '. $r->walletFK
                ];
            }
            $user->update([
                'wallet_fk' => $r->walletFK
            ]);
        }
        if ($r->walletYoo != null) {
            if($qiwiwdw < 1 && $cardwdw < 1) {
                return [
                    'success' => false,
                    'message' => 'Чтобы привязать данный метод, необходимо иметь успешный вывод на QIWI или карту'
                ];
            }
            $yoo = User::query()->where([['wallet_yoomoney', $r->walletYoo], ['id', '!=', $r->id]])->first();
            if($yoo) {
                return [
                    'success' => false,
                    'message' => 'Данный реквизит уже занят - '. $r->walletYoo
                ];
            }
            $user->update([
                'wallet_yoomoney' => $r->walletYoo
            ]);
        }
        if ($r->walletPias != null) {
            if($qiwiwdw < 1 && $cardwdw < 1) {
                return [
                    'success' => false,
                    'message' => 'Чтобы привязать данный метод, необходимо иметь успешный вывод на QIWI или карту'
                ];
            }
            $pias = User::query()->where([['wallet_piastrix', $r->walletPias], ['id', '!=', $r->id]])->first();
            if($pias) {
                return [
                    'success' => false,
                    'message' => 'Данный реквизит уже занят - '. $r->walletPias
                ];
            }
            $user->update([
                'wallet_piastrix' => $r->walletPias
            ]);
        }

        return [
            'success' => true,
            'message' => 'Реквизиты обновлены'
        ];
    }
    public function getWallets($id)
    {
        $user = User::query()->find($id);
        return [
            'wallet_piastrix' => $user->wallet_piastrix,
            'wallet_fk' => $user->wallet_fk,
            'wallet_card' => $user->wallet_card,
            'wallet_qiwi' => $user->wallet_qiwi,
            'wallet_yoomoney' => $user->wallet_yoomoney

        ];
    }
    public function getRefList($id) {
        if ($this->auth->id != $id) {return;}
        $perc = 0;
        $user = User::query()->find($id);
        $refs = [];
        if ($user->ref_perc == 0) {$perc = $this->config->ref_perc;}
        else {$perc = $user->ref_perc;}
        foreach (User::query()->where('referral_use', $user->id)->get() as $u) {
            $refs[] = [
                'username' => $u->username, 
                'created_at' => $u->created_at->format('d-m-Y H:i:s'),
                'profit' => \App\ReferralPayment::query()->where([['user_id', $u->id], ['referral_id', $user->id]])->sum('sum') * ($perc / 100)
            ];
        }
        return $refs;
    }
    public function clearStats()
    {
        $profit = Profit::query()->find(1);

        $old1 = $profit->earn_dice;
        $old2 = $profit->earn_wheel;
        $old3 = $profit->earn_coinflip;
        $old4 = $profit->earn_mines;
        $old5 = $profit->earn_slots;
        $old6 = $profit->earn_stairs;

        $profit->update([
            'all_earn_dice' => $profit->all_earn_dice + $old1,
            'all_earn_mines' => $profit->all_earn_mines + $old4,
            'all_earn_wheel' => $profit->all_earn_wheel + $old2,
            'all_earn_coinflip' => $profit->all_earn_coinflip + $old3,
            'all_earn_slots' => $profit->all_earn_slots + $old5,
            'all_earn_stairs' => $profit->all_earn_stairs + $old6,
            'old_earn_dice' => $old1,
            'old_earn_mines' => $old4,
            'old_earn_wheel' => $old2,
            'old_earn_coinflip' => $old3,
            'old_earn_slots' => $old5,
            'old_earn_stairs' => $old6,
            'earn_stairs' => 0,
            'earn_slots' => 0,
            'earn_dice' => 0,
            'earn_mines' => 0,
            'earn_wheel' => 0,
            'earn_coinflip' => 0
        ]);

        return "OK";
    }
    public function makeStats()
    {
        //$stats = Stat::query()->find(1);
        $users = User::query()->latest()->first()->id;

        $coin = Coin::query()->latest()->first()->id;
        $game = Game::query()->latest()->first()->id;
        $wheel = Wheel::query()->latest()->first()->id;
        $wheelbets = WheelBets::query()->latest()->first()->id;
        $total = $coin + $game + $wheel + $wheelbets;

        $today = Carbon::today();
        $wheelWin = WheelBets::query()->where([['created_at', '>=', $today]])->sum('win_sum');
        $gameWin = Game::query()->where([['created_at', '>=', $today]])->sum('win');
        $totalSum = $wheelWin + $gameWin;

        //$stats->update([
        //    'users' => $users,
        //    'total_games' => $total,
        //    'total_win' => $totalSum
        //]);

        $data = [
            'users' => $users,
            'bets' => $total,
            'sum' => $totalSum
        ];

        return $data;
    }
    public function getLast()
    {
        $data = [];

        foreach(Withdraw::query()->orderBy('created_at', 'desc')->where('status', 1)->limit(20)->get() as $wdw) {
            $data[] = [
                'wallet' => substr($wdw->wallet, 0, -5) . '...',
                'method' => self::SYSTEMS[$wdw->system]['title1'],
                'username' => substr($wdw->username, 0, -2) . '...',
                'sum' => $wdw->sumNoCom,
                'date' => $wdw->created_at->format('H:i:s'),
            ];
        }        

        return $data;

    }
    public function getStats()
    {
        $stats = Stat::query()->find(1);

        $data = [
            'users' => $stats->users,
            'bets' => $stats->total_games,
            'sum' => $stats->total_win
        ];

        return $data;

    }
    public function getBalance(Request $r)
    {
        $balance = User::where('api_token', $r->token)->select('balance')->first() ?? false;
        return $balance;
    }

    public function editUser(Request $r)
    {
        $user = $r->user();
        $username = strip_tags($r->username);

        if ($user->old_username != null && $user->id != 28279 && $user->id != 20) {
            return [
                'success' => false,
                'message' => 'Никнейм можно изменять 1 раз'
            ];
        }

        if (!ctype_alnum($username)) {
            throw new \Exception('Никнейм может содержать только англ. буквы и цифры');
        }

        if ($username === 'ADMlN' || $username === 'ADM1N' || $username === 'MODER' || $username === 'admin2') {
            throw new \Exception('Никнейм может содержать только англ. буквы и цифры');
        }

        if (User::query()->where('username', $username)->first()) {
            throw new \Exception('Никнейм уже занят');
        }

        $user->update([
            'old_username' => $user->username,
            'username' => $username
        ]);

        return [
            'success' => true
        ];
    }

    public function setPass(Request $r)
    {
        $user = $r->user();

        //if ($user->vk_only == 0 || $user->tg_only == 0) {
        //    throw new \Exception('Ошибка!');
        //}

        $user->password = $r->password;
        $user->vk_only = 0;
        $user->tg_only = 0;
        $user->save();

        return [
            'success' => true
        ];
    }

    public function setRef($id) {
        session_start();
        if(is_numeric($id) && $refer = User::where('id', $id)->first()) {
            $_SESSION['ref'] = $id;
            $refer->link_trans += 1;
            $refer->save();
        }

        return redirect('/')->with('modal', 'signup');
    }
}
