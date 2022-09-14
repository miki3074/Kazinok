<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Coin;
use App\Profit;
use App\Setting;
use App\Game;
use App\Telegram;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class DiceController extends Controller
{

    public function __construct(Request $r) {
        parent::__construct();
    }

    public function resolve(Request $r)
    {
        //if ($r->id == 20) return ['success' => false, 'message' => 'Дилшод, тормози, оно тебе нахуй не надо!'];
        $user = User::query()->find($r->id);

        $secret_key = "oxyeli_g@ndoni?";

        $hash = md5($r->hid.$user->id.$secret_key.$r->hid);

        if($hash != $r->a) {
            header('HTTP/1.1 500 Internal Server Error');
            exit;
        }

        if ($r->bet < 1) {
            return ['result' => false, 'message' => 'Минимальная ставка 1 руб.'];
        }

        if ($r->bet > 1000000) {
            return ['result' => false, 'message' => 'Максимальная ставка 1000000 руб.'];
        }

        if ($r->chance < 1) {
            return ['result' => false, 'message' => 'Минимальный шанс 1%'];
        }

        if ($r->chance > 92) {
            return ['result' => false, 'message' => 'Максимальный шанс 92%'];
        }

        if ($user->balance < $r->bet) {
            return ['result' => false, 'message' => 'Недостаточно средств на балансе'];
        }

        if ($user->hid !== $r->hid) {
            return ['result' => false, 'message' => 'Игра с данным хешем уже была сыграна'];
        }

        if ($user->ban) {
            return ['result' => false, 'message' => 'Ваш аккаунт заблокирован'];
        }

        if($this->tooFastNew($user->id)) return ['success' => false, 'message' => 'Не спешите'];
        
        $oldbal = $user->balance;
        $user->decrement('balance', $r->bet);

        $min = round(($r->chance / 100) * 999999, 0);
        $max = 999999 - round(($r->chance / 100) * 999999, 0);
        $isWin = false;

        $totalwin = round((97 / $r->chance) * $r->bet, 2);
        $setting = Setting::query()->find(1);
        $profit = Profit::query()->find(1);

        if($setting->antiminus == 1 && !$user->is_youtuber || $user->is_loser) { // Антиминус
            if($totalwin - $r->bet > $profit->bank_dice || $user->is_loser) {
                $r->type === 'min' ? $random = rand( ($r->chance * 10000) - 1, 999999) : $random = rand(0, 1000000 - ($r->chance * 10000) );
                $loseGame = json_decode($user->dice);
                $loseGame->random = $random;
                $user->update([
                    'dice' => json_encode($loseGame)
                ]);
            }
        }

        $dice = json_decode($user->dice, true);
        $random = $dice['random'];

        if ($r->type === 'min') {
            if ($random <= $min) {
                $isWin = true;
            } else {
                $isWin = false;
            }
        } else if ($r->type === 'max') {
            if ($random >= $max) {
                $isWin = true;
            } else {
                $isWin = false;
            }
        }

        $coms = $profit->comission;

        if($user->is_loser) {
            $coms = $profit->loser_comission;
        }
        
        $win = 0;
        if ($isWin) {
            $win = number_format($totalwin, 2, '.', '');
            $text = 'Выиграли ' . $win;
            $user->increment('balance', $win);
            $user->update(['stat_dice' => $user->stat_dice + ($win - $r->bet)]);

            if(!$user->is_youtuber) {
                $profit->update([
                    'bank_dice' => $profit->bank_dice - ($r->bet / 100) * (100 - $coms),
                    'earn_dice' => $profit->earn_dice - ($win - $r->bet)
                ]);
            }
        } else {
            $text = 'Выпало ' . $random;
            $user->update(['stat_dice' => $user->stat_dice - $r->bet]);
            if(!$user->is_youtuber) {
                $profit->update([
                    'bank_dice' => $profit->bank_dice + ($r->bet / 100) * (100 - $coms),
                    'earn_dice' => $profit->earn_dice + $r->bet
                ]);
            }
        }

        $newDice = $this->generate();

        $user->update([
            'dice' => json_encode($newDice),
            'hid' => $newDice['hid']
        ]);

        if($user->wager < 0 || $user->wager - $r->bet < 0) {
            $user->wager = 0;
            $user->save();
        } else {
            $user->decrement('wager', $r->bet);
        }
        
        $game = Game::query()->create([
            'user_id' => $user->id,
            'game' => 'dice',
            'bet' => $r->bet,
            'chance' => $r->chance,
            'win' => $win,
            'type' => $r->type,
            'dice' => json_encode($dice)
        ]);

        Redis::publish('newGame', json_encode([
            'id' => $game->id,
            'game' => $game->game,
            'bet' => $game->bet,
            'chance' => $game->chance,
            'username' => substr($user->username, 0, -2) . '...',
            'win' => $game->win
        ]));

	\Log::info('Баланс до: ' . $oldbal . ' Дайс: выиграл ' . $win . '? - ' . $isWin . ' Юзер: #' . $user->id . ' chance: ' . $game->chance . ' Баланс: ' . $user->balance); 

        return [
            'success' => $isWin,
            'result' => true,
            'text' => $text,
            'win' => $win,
            'id' => $game->id,
            'bet' => $game->bet,
            'chance' => $game->chance,
            'balance' => $user->balance
        ];
    }

    public static function generate($num = NULL)
    {
        $salt1 = Str::random(16);
        $random = $num ?? mt_rand(0, 999999);
        $salt2 = Str::random(12);
        $string = $salt1 . '|' . $random . '|' . $salt2;
        $hash = hash('sha512', $string);
        $hid = implode("-", str_split(hash('sha1', $random), 4));

        return [
            'salt1' => $salt1,
            'random' => $random,
            'salt2' => $salt2,
            'string' => $string,
            'hash' => $hash,
            'hid' => $hid
        ];
    }
}
