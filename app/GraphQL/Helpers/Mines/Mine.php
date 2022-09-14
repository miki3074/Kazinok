<?php

namespace App\GraphQL\Helpers\Mines;

use App\Game;
use App\Profit;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class Mine
{
    public $mines = [
        [[1.09, 1.19], [1.30, 1.43], [1.58, 1.75], [1.96, 2.21], [2.50, 2.86], [3.30, 3.85], [4.55, 5.45], [6.67, 8.33], [10.71, 14.29], [20, 30], [50, 100], [300]],
        [[1.14, 1.3], [1.49, 1.73], [2.02, 2.37], [2.82, 3.38], [4.11, 5.05], [6.32, 8.04], [10.45, 13.94], [19.17, 27.38], [41.07, 65.7], [115, 230], [575, 2300]],
        [[1.19, 1.43], [1.73, 2.11], [2.61, 3.26], [4.13, 5.32], [6.95, 9.27], [12.64, 17.69], [25.56, 38.33], [60.24, 100.40], [180.71, 361.43], [843.33, 2530], [12650]],
        [[1.25, 1.58], [2.02, 2.61], [3.43, 4.57], [6.20, 8.59], [12.16, 17.69], [26.54, 41.28], [67.08, 115], [210.83, 421.67], [948.75, 2530], [8855, 53130]],
        [[1.32, 1.75], [2.37, 3.26], [4.57, 6.53], [9.54, 14.31], [22.12, 35.38], [58.97, 103.21], [191.67, 383.33], [843.33, 2108.33], [6325, 25300], [177100]],
        [[1.39, 1.96], [2.82, 4.13], [6.20, 9.54], [15.10, 24.72], [42.02, 74.70], [140.06, 280.13], [606.94, 1456.67], [4005.83, 13352.78], [60087.50, 480700]],
        [[1.47, 2.21], [3.38, 5.32], [8.59, 14.31], [24.72, 44.49], [84.04, 168.08], [360.16, 840.38], [2185, 6555], [24035, 120175], [1081575]],
        [[1.56, 2.50], [4.11, 6.95], [12.16, 22.12], [42.02, 84.04], [178.58, 408.19], [1020.47, 2857.31], [9286.25, 37145], [204297.50, 2042975]],
        [[1.67, 2.86], [5.05, 9.27], [17.69, 35.38], [74.70, 168.08], [408.19, 1088.50], [3265.49, 11429.23], [49526.67, 297160], [3268760]],
        [[1.79, 3.30], [6.32, 12.64], [26.54, 58.97], [140.06, 360.16], [1020.47, 3265.49], [12245.60, 57146.15], [371450, 4457400]],
        [[1.92, 3.85], [8.04, 17.69], [41.28, 103.21], [280.13, 840.38], [2857.31, 11429.23], [57146.14, 400023.08], [5200300]],
        [[2.08, 4.55], [10.45, 25.56], [67.08, 191.67], [606.94, 2185], [9286.25, 49526.67], [371450, 5200300]],
        [[2.27, 5.45], [13.94, 38.33], [115, 383.33], [1456.67, 6555], [37145, 297160], [4457400]],
        [[2.50, 6.67], [19.17, 60.24], [210.83, 843.33], [4005.83, 24035], [204297.50, 3268760]],
        [[2.78, 8.33], [27.38, 100.40], [421.67, 2108.33], [13352.78, 120175], [2042975]],
        [[3.13, 10.71], [41.07, 180.71], [948.75, 6325], [60087.50, 1081575]],
        [[3.57, 14.29], [65.71, 361.43], [2530, 25300], [480700]],
        [[4.17, 20], [115, 843.33], [8855, 177100]],
        [[5, 30], [230, 2530], [53130]],
        [[6.25, 50], [575, 12650]],
        [[8.33, 100], [2300]],
        [[12.50, 300]],
        [[25]]
    ];

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

        $user->increment('balance', $winSum);
        $user->update(['mines_game' => null]); // try
        if(!$user->is_youtuber) {
            $profit->update([
                'bank_mines' => $profit->bank_mines - ($winSum - $game['bet']),
            ]);
        }
        return $winSum;
    }

    public function getCoef($user, $win)
    {
        $game = json_decode($user->mines_game, true);
        $currentPosition = count($game['used_positions']) + 1;
        $index = 0;

        if ($currentPosition === 2 && !$win) {
            return 0;
        }

        if (($currentPosition - 1) % 2 === 0) $index = 1;

        if (!isset($this->mines[$game['bombs_count'] - 2][floor(($currentPosition) / 2) - 1][$index])) {
            if ($index === 0) {
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
