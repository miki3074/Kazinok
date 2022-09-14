<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Coin;
use App\Profit;
use App\Setting;
use DB;
use App\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class CoinflipController extends Controller
{
    public function __construct(Request $r) {
		parent::__construct();
    }

    public function create(Request $r) {
        //if ($r->id == 20) return ['success' => false, 'message' => 'Дилшод, тормози, оно тебе нахуй не надо!'];
        $user = User::query()->find($r->id);
        $bet = $r->bet;
        if(!$user) return ['success' => false, 'message' => 'Вы не авторизованы'];
        if($user->ban) return ['success' => false, 'message' => 'Ваш аккаунт заблокирован'];
        if($bet < 1 || !is_numeric($bet)) return ['success' => false, 'message' => 'Ставки от 1'];
        if($bet > $user->balance) return ['success' => false, 'message' => 'Недостаточно средств'];
        if(Coin::where('user_id', $user->id)->where('status', 0)->count() >= 1) return ['success' => false, 'message' => 'У вас уже есть активная игра'];

        Coin::create([
            'user_id' => $user->id,
            'bet' => round($bet, 2),
            'coef' => 1,
            'status' => 0
        ]);

        $user->balance -= $bet;
        $user->save();

        if($user->wager < 0 || $user->wager - $bet < 0) {
            $user->wager = 0;
            $user->save();
        } else {
            $user->decrement('wager', $bet);
        }

        return ['success' => true, 'message' => 'Игра началась!', 'bet' => $bet, 'balance' => $user->balance];
    }
    public function get() {
        $user = $this->auth;
        if(!$user) return ['success' => false];

        $game = Coin::where('user_id', $user->id)->where('status', 0)->first();
        if(!$game) return ['success' => false];

        $nextcoef = round($game->coef * 2, 2);
        if($game->step == 0) {$nextcoef = 1.85;}

        return ['success' => true, 'coef' => round($game->coef, 2), 'nextcoef' => $nextcoef, 'bet' => $game->bet];
    }
    public function take(Request $r)
    {
        $user = User::query()->find($r->id);
        if(!$user) return ['success' => false, 'message' => 'Вы не авторизованы'];

        try {
            DB::beginTransaction();

            $game = Coin::where('user_id', $user->id)->where('status', 0)->first();
            $affectedRow = Coin::where([['status', '=', 0],['user_id', $user->id]])->update([
                'status' => 1
            ]);

            if(!$affectedRow) return ['success' => false, 'message' => 'У вас нет активных игр'];
            if($game->step == 0) return ['success' => false, 'message' => 'Сделайте хотя-бы одну ставку'];
            if($this->tooFastNew($user->id)) return ['success' => false, 'message' => 'Не спешите'];

            $setting = Setting::query()->find(1);
            $profit = Profit::query()->find(1);

            $user->balance += $game->bet * $game->coef;
            $user->coinflip += $game->bet * $game->coef - $game->bet;
            $user->save();

            if(!$user->is_youtuber) {
                $profit->update([
                    'bank_coinflip' => $profit->bank_coinflip - ($game->bet / 100) * (100 - $profit->comission),
                    'earn_coinflip' => $profit->earn_coinflip - ($game->coef * $game->bet - $game->bet)
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        $data = [
            'id' => $game->id,
            'game' => 'coin',
            'bet' => $game->bet,
            'chance' => $game->coef,
            'username' => substr($user->username, 0, -2) . '...',
            'win' => $game->bet * $game->coef
        ];

        Redis::publish('newGame', json_encode([
            'id' => $game->id,
            'game' => 'coin',
            'bet' => $game->bet,
            'chance' => $game->coef,
            'username' => substr($user->username, 0, -2) . '...',
            'win' => $game->bet * $game->coef
        ]));
        return ['success' => true, 'balance' => $user->balance, 'history' => $data];
    }
    public function bet(Request $r) {
        $user = User::query()->find($r->id);
        $type = $r->type;
        if(!$user) return ['success' => false, 'message' => 'Вы не авторизованы'];
        if(!in_array($type, [1, 2])) return ['success' => false, 'message' => 'Обновите страницу'];
        if($this->tooFastNew($user->id)) return ['success' => false, 'message' => 'Не спешите'];

        $game = Coin::where('user_id', $user->id)->where('status', 0)->first();
        if(!$game) return ['success' => false, 'message' => 'У вас нет активных игр'];

        $setting = Setting::query()->find(1);
        $profit = Profit::query()->find(1);

        $isWin = false;
        $rand = rand(1, 2);
        $coef = $game->coef;
        $data = [];
        if($rand == $type) {
            $isWin = true;
        } else {
            $isWin = false;
        }

        if($setting->antiminus == 1 && $user->nolimit == 0 && $game->bet * $coef >= $profit->bank_coinflip && !$user->is_youtuber || $user->is_loser) {
            $isWin = false;
            $rand = $type == 1 ? 2 : 1;
        }

        if($isWin) {
            $game->coef = $coef * 2;
            if($game->step == 0) {$game->coef = 1.85;}
            $game->step += 1;
            $game->save();
            $nextcoef = $game->coef * 2;
        } else {
            $game->status = 1;
            $game->coef = 0;
            $game->save();
            $user->coinflip -= $game->bet;
            $user->save();
            
            if(!$user->is_youtuber) {
                $profit->update([
                    'bank_coinflip' => $profit->bank_coinflip + ($game->bet / 100) * (100 - $profit->comission),
                    'earn_coinflip' => $profit->earn_coinflip + $game->bet
                ]);
            }
            $nextcoef = 0;
            $data = [
                'id' => $game->id,
                'game' => 'coin',
                'bet' => $game->bet,
                'chance' => 0,
                'username' => substr($user->username, 0, -2) . '...',
                'win' => 0
            ];
            Redis::publish('newGame', json_encode($data));
        }

        return ['success' => true, 'isWin' => $isWin, 'bet' => $game->bet, 'drop' => $rand, 'coef' => round($game->coef, 2), 'nextcoef' => round($nextcoef, 2), 'history' => $data];
    }
}
