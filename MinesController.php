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
use DB;
class MinesController extends Controller
{
    public $mines = [
        [[1.06, 1.15], [1.26, 1.39], [1.53, 1.70], [1.90, 2.14], [2.42, 2.77], [3.20, 3.73], [4.41, 5.29], [6.47, 8.08], [10.39, 13.86], [19.40, 29.10], [48.50, 96], [271]],
        [[1.11, 1.26], [1.45, 1.68], [1.96, 2.30], [2.74, 3.28], [3.99, 4.90], [6.13, 7.80], [10.14, 13.52], [18.59, 26.56], [39.84, 63.74], [109.55, 223.10], [517.75, 2111]],
        [[1.15, 1.39], [1.68, 2.05], [2.53, 3.16], [4.01, 5.16], [6.74, 8.99], [12.26, 17.16], [24.79, 37.18], [58.43, 97.39], [162.29, 330.59], [718.03, 2224.10], [9270.50]],
        [[1.21, 1.53], [1.96, 2.53], [3.33, 4.43], [6.01, 8.33], [11.80, 17.16], [25.74, 40.04], [65.07, 111.55], [201.51, 379.02], [820.29, 2154.10], [7289.35, 38536.10]],
        [[1.28, 1.70], [2.30, 3.16], [4.43, 6.33], [9.25, 13.88], [21.46, 34.32], [57.20, 100.11], [175.92, 321.83], [708.03, 1845.08], [5235.25, 20541], [131787]],
        [[1.35, 1.90], [2.74, 4.01], [6.01, 9.25], [14.65, 23.98], [40.76, 72.46], [135.86, 221.73], [538.73, 1112.97], [2985.66, 10952.20], [39284.88, 266279]],
        [[1.43, 2.14], [3.28, 5.16], [8.33, 13.88], [23.98, 43.16], [81.52, 161.04], [329.36, 775.17], [1919.45, 5458.35], [20313.95, 76569.75], [649127.75]],
        [[1.51, 2.42], [3.99, 6.74], [11.80, 21.46], [40.76, 81.52], [163.22, 355.94], [849.86, 2371.59], [7607.66, 26030.65], [108168.57, 981685.75]],
        [[1.62, 2.77], [4.90, 8.99], [17.16, 34.32], [72.46, 163.04], [355.94, 915.85], [2767.53, 9086.35], [32040.87, 218245.20], [1370697.20]],
        [[1.74, 3.20], [6.13, 12.26], [25.74, 57.20], [135.86, 309.36], [889.86, 2867.53], [9878.23, 35431.77], [190306.50, 2023678]],
        [[1.86, 3.73], [7.80, 17.16], [40.04, 100.11], [251.73, 715.17], [2171.59, 9086.35], [35431.77, 188022.39], [2344291]],
        [[2.02, 4.41], [10.14, 24.79], [65.07, 185.92], [528.73, 1919.45], [7007.66, 38040.87], [180649.64, 2344291]],
        [[2.20, 5.29], [13.52, 37.18], [111.55, 331.83], [1112.97, 5358.35], [26030.65, 198245.20], [1823678]],
        [[2.42, 6.47], [18.59, 58.43], [200.51, 718.03], [3385.66, 18313.95], [98168.57, 1170697.20]],
        [[2.70, 8.08], [26.56, 97.39], [379.02, 1845.08], [10952.20, 56569.75], [981685.75]],
        [[3.03, 10.39], [39.84, 171.29], [720.29, 5135.25], [38284.88, 449127.75]],
        [[3.46, 13.86], [63.74, 320.59], [2054.10, 19541], [166279]],
        [[4.04, 19.40], [107.55, 718.03], [6589.35, 31787]],
        [[4.85, 29.10], [203.10, 2154.10], [11536.10]],
        [[6.06, 48.5], [457.75, 9270.50]],
        [[8.08, 97.0], [1931]],
        [[12.12, 271]],
        [[24.25]]
    ];

    public function __construct(Request $r) {
        parent::__construct();
    }

    public function resolve(Request $r)
    {
        $user = User::query()->find($r->id);

        if ($r->bet < 1 or !is_numeric($r->bet)) {
            return ['success' => false, 'message' => 'Минимальная ставка 1 руб.'];
        }

        if ($r->bet > 1000000) {
            return ['success' => false, 'message' => 'Максимальная ставка 1000000 руб.'];
        }

        if ($r->bomb < 2) {
            return ['success' => false, 'message' => 'Минимальный кол-во бомб: 2'];
        }

        if ($r->bomb > 24) {
            return ['success' => false, 'message' => 'Максимальный кол-во бомб: 24'];
        }

        if ($user->balance < $r->bet) {
            return ['success' => false, 'message' => 'Недостаточно средств на балансе'];
        }

        if ($user->mines_is_active) {
            return ['success' => false, 'message' => 'У Вас уже есть активная игра'];
        }

        if ($user->ban) {
            return ['success' => false, 'message' => 'Ваш аккаунт заблокирован'];
        }
        
        $generated = $this->generate($r->bomb);

        $game = [
            'bombs' => $generated['bombs'],
            'used_positions' => [],
            'bombs_count' => $r->bomb,
            'bet' => $r->bet,
            'fair' => [
                'salt' => $generated['salt'],
                'string' => $generated['string'],
                'hash' => $generated['hash']
            ]
        ];

        $user->decrement('balance', $r->bet);

        $user->update([
            'mines_game' => json_encode($game),
            'mines_is_active' => 1
        ]);

        if($user->wager < 0 || $user->wager - $r->bet < 0) {
            $user->wager = 0;
            $user->save();
        } else {
            $user->decrement('wager', $r->bet);
        }
        
        return [
            'success' => true,
            'message' => 'Игра началась!',
            'hash' => $generated['hash'],
            'balance' => $user->balance
        ];
    }

    public function resolvePath(Request $r)
    {
        $user = User::query()->find($r->id);

        if ($r->bomb < 1 || $r->bomb > 25) {
            return ['success' => false, 'message' => 'Выбрана неправильная клетка'];
        }

        if (!$user->mines_is_active) {
            return ['success' => false, 'message' => 'У Вас нет активной игры'];
        }

        $game = json_decode($user->mines_game, true);

        if (isset($game['used_positions'][$r->bomb])) {
            return ['success' => false, 'message' => 'Вы уже задействовали эту клетку'];
        }

        $win = true; // Угадал клетку
        $instWin = false;

        $setting = Setting::query()->find(1);
        $profit = Profit::query()->find(1);

        if ($game['bombs'][$r->bomb]) {
            $win = false; // Попал на бомбу
        }

        $bombs = null;
        $winSum = 0;
        $id = 0;
        $history = [];

        if ($win) {
            $game['used_positions'][$r->bomb] = 1;

            $user->update([
                'mines_game' => json_encode($game)
            ]);

            if ($user->id == 5) {
                Telegram::create(['message' => json_encode($game) . ' Count resolvePath: ' . count($game['used_positions'])]);
            }
            if (25 - count($game['used_positions']) == $game['bombs_count']) {
                $instWin = true;

                if ($user->id == 5) {
                    Telegram::create(['message' => json_encode($game) . ' Инста:' . $instWin]);
                }

                $winSum = $this->cashout($user);

                $user->update([
                    'mines_is_active' => 0
                ]);

                $bd = $this->createGame($user, true);

                $id = $bd->id;
                $history = [
                    'id' => $bd->id,
                    'bet' => $bd->bet,
                    'chance' => $bd->chance,
                    'username' => substr($user->username, 0, -2) . '...',
                    'win' => $bd->win
                ];
            }

        } else {
            $game['used_positions'][$r->bomb] = 0;

            $user->update([
                'mines_game' => json_encode($game)
            ]);

            $bombs = $game['bombs'];

            $user->update([
                'mines_is_active' => 0
            ]);

            $bd = $this->createGame($user, false);

            $id = $bd->id;
            $history = [
                'id' => $bd->id,
                'bet' => $bd->bet,
                'chance' => $bd->chance,
                'username' => substr($user->username, 0, -2) . '...',
                'win' => $bd->win
            ];
            
            $user->update(['mines' => $user->mines - $game['bet']]);

            if(!$user->is_youtuber) {
                $profit->update([
                    //'bank_mines' => $profit->bank_mines + ($game['bet'] / 100) * (100 - $coms),
                    'earn_mines' => $profit->earn_mines + $game['bet']
                ]);
            }
        }

        $user->update([
            'mines_game' => json_encode($game)
        ]);

        return [
            'success' => true,
            'message' => 'Успешно!',
            'win' => $win,
            'selected_bombs' => json_encode($game['used_positions']),
            'bombs' => json_encode($bombs),
            'insta_win' => $instWin,
            'win_sum' => $winSum,
            'id' => $id,
            'history' => json_encode($history)
        ];
    }

    public function resolveTake(Request $r)
    {
        $user = User::query()->find($r->id); 

        try {
            DB::beginTransaction();

            $affectedRow = \App\User::find($user->id)->where('mines_is_active', '=', 1)->update([
                'mines_is_active' => 0
            ]);

            if (!$affectedRow) {
                return ['success' => false, 'message' => 'У вас нет активной игры'];
           }

            if ($user->mines_game == null) {
                return ['success' => false, 'message' => 'У вас нет активной игры'];
            }

            $winSum = $this->cashout($user);
            $game = $this->createGame($user, true);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }

        return [
            'success' => true,
            'message' => "Успешно",
            'win' => $winSum,
            'balance' => $user->balance,
            'history' => json_encode([
                'id' => $game->id,
                'bet' => $game->bet,
                'chance' => $game->chance,
                'username' => substr($user->username, 0, -2) . '...',
                'win' => $game->win
            ])
        ];
    }

    public function resolveGet(Request $r) {
        $user = User::query()->find($r->id);

        if (!$user->mines_is_active) {
            return [
                'success' => false
            ];
        } else {
            $game = json_decode($user->mines_game, true);

            return [
                'success' => true,
                'hash' => $game['fair']['hash'],
                'bet' => $game['bet'],
                'bomb' => $game['bombs_count'],
                'selected_bombs' => json_encode($game['used_positions']),
                'active_path' => count($game['used_positions']) + 1
            ];
        }
    }

    public function generate($bomb)
    {
        $salt = Str::random(24);
        $bombs = [];

        for ($i = 1; $i <= 25; $i++) {
            $bombs[$i] = 0;
        }

        for ($i = 0; $i < $bomb; $i++) {
            $position = mt_rand(1, 25);

            while ($bombs[$position]) {
                $position = mt_rand(1, 25);
            }

            $bombs[$position] = 1;
        }

        $string = implode("|", $bombs).'|'.$salt;
        $hash = hash('sha512', $string);

        return [
            'salt' => $salt,
            'string' => $string,
            'bombs' => $bombs,
            'hash' => $hash
        ];
    }

    public function cashout($user)
    {
        $profit = Profit::query()->find(1);
        $game = json_decode($user->mines_game, true);
        $currentPosition = count($game['used_positions']) + 1;
        $index = 0;

        if (($currentPosition - 1) % 2 === 0) $index = 1;

        if (!isset($this->mines[$game['bombs_count'] - 2][floor(($currentPosition) / 2) - 1][$index])) {
            if ($index === 0) {
                $index = 1;
            } else {
                $index = 0;
            }
        }

        $winSum = round($game['bet'] * $this->mines[$game['bombs_count'] - 2][floor(($currentPosition) / 2) - 1][$index], 2);

        $oldbal = $user->balance;
        $user->increment('balance', $winSum);

        \Log::info('Баланс до: ' . $oldbal . ' Мины: выиграл ' . $winSum . ' Юзер: #' . $user->id . ' Баланс: ' . $user->balance . ' Ставка: ' . $game['bet'] . ' Кол-во бомб: ' . $game['bombs_count'] . ' Текущая позиция: ' . $currentPosition);

        $user->update(['mines' => $user->mines + ($winSum - $game['bet'])]); // try

        if(!$user->is_youtuber) {
           $profit->update([
               'earn_mines' => $profit->earn_mines - ($winSum - $game['bet']),
           ]);
        }
        return $winSum;
    }

    public function getCoef($user, $win)
    {
        $game = json_decode($user->mines_game, true);
        if ($user->id == 5) {
            Telegram::create(['message' => json_encode($game) . ' Count: ' . count($game['used_positions'])]);
        }
        
        //$xz = (Клетки без мин/клеток всего)×(оставшиеся клетки без мин/оставшиеся клетки)×100%

        //$xz1 = 100/$xz;
        $currentPosition = count($game['used_positions']) + 1;
        $index = 0;

        if ($currentPosition == 2 && !$win) {
            return 0;
        }

        if (($currentPosition - 1) % 2 == 0) $index = 1;

        if (!isset($this->mines[$game['bombs_count'] - 2][floor(($currentPosition) / 2) - 1][$index])) {
            if ($index == 0) {
                $index = 1;
            } else {
                $index = 0;
            }
        }

        return $this->mines[$game['bombs_count'] - 2][floor(($currentPosition) / 2) - 1][$index];
    }

    public function createGame($user, $win)
    {
        $game = json_decode($user->mines_game, true);
        $coef =  $this->getCoef($user, $win);

        if ($win) {
            $sum = round($game['bet'] * $coef,2);
        } else {
            $sum = 0;
        }

        $game = Game::query()->create([
            'user_id' => $user->id,
            'game' => 'mines',
            'bet' => $game['bet'],
            'chance' => $coef,
            'win' => $sum,
            'mine' => json_encode($game)
        ]);

        Redis::publish('newGame', json_encode([
            'id' => $game->id,
            'game' => $game->game,
            'bet' => $game->bet,
            'chance' => $game->chance,
            'username' => substr($user->username, 0, -2) . '...',
            'win' => $game->win
        ]));

        return $game;
    }
}
