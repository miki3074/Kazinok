<?php

namespace App\Http\Controllers;

use App\Game;
use App\GraphQL\Helpers\Game\GenerateHash;
use App\GraphQL\Helpers\Mines\Mine;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class FakeController extends Controller
{
    private $mine;

    public function __construct(
        Mine $mine
    )
    {
        parent::__construct();
        $this->mine = $mine;
    }

    public function fake()
    {
        $bot = User::query()->where('is_bot', 1)->inRandomOrder()->first();

        if (!$bot) {
            return 'bot not found';
        }

        $rnd = mt_rand(0, 1);

        if ($rnd === 0) {
            $this->createRandomDice($bot);
        } else {
            $this->createRandomMines($bot);
        }

        return 'ok';
    }

    private function createRandomDice($user)
    {
        $dice = GenerateHash::generate();
        $chance = mt_rand(1, 95);
        $bet = $this->random_float(1, 50);

        $type = 'min';
        if (mt_rand(0, 1) === 1) $type = 'max';

        $min = round(($chance / 100) * 999999, 0);
        $max = 999999 - round(($chance / 100) * 999999, 0);
        $random = $dice['random'];
        $isWin = false;

        if ($type === 'min') {
            if ($random <= $min) {
                $isWin = true;
            } else {
                $isWin = false;
            }
        } else if ($type === 'max') {
            if ($random >= $max) {
                $isWin = true;
            } else {
                $isWin = false;
            }
        }

        $win = 0;

        if ($isWin) {
            $win = round((100 / $chance) * $bet, 2);
        }

        $game = Game::query()->create([
            'user_id' => $user->id,
            'game' => 'dice',
            'bet' => $bet,
            'chance' => $chance,
            'win' => $win,
            'type' => $type,
            'dice' => json_encode($dice),
            'fake' => 1
        ]);

        Redis::publish('newGame', json_encode([
            'id' => $game->id,
            'game' => $game->game,
            'bet' => $game->bet,
            'chance' => $game->chance,
            'username' => substr($user->username, 0, -2) . '...',
            'win' => $game->win
        ]));
    }

    private function createRandomMines($user)
    {
        $bomb = mt_rand(2, 24);
        $bet = $this->random_float(1, 50);
        $generated = $this->mine->generate($bomb);

        $game = [
            'bombs' => $generated['bombs'],
            'used_positions' => [],
            'bombs_count' => $bomb,
            'bet' => $bet,
            'fair' => [
                'salt' => $generated['salt'],
                'string' => $generated['string'],
                'hash' => $generated['hash']
            ]
        ];

        $win = -1;

        while ($win == -1) {
            $path = mt_rand(1, 25);

            if (count($game['used_positions']) > 0) {
                $taked = mt_rand(0, 1);

                if ($taked) {
                    $win = 1;
                }
            }

            if (!isset($game['used_positions'][$path])) {
                if ($game['bombs'][$path]) {
                    $game['used_positions'][$path] = 0;
                    $win = 0;
                } else {
                    $game['used_positions'][$path] = 1;

                    if (25 - count($game['used_positions']) === $game['bombs_count']) {
                        $win = 1;
                    }
                }
            }
        }

        $this->createGameMine($user, $game, $win);
    }

    private function createGameMine($user, $game, $win)
    {
        $coef =  $this->getCoef($game, $win);

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
            'mine' => json_encode($game),
            'fake' => 1 
        ]);

        Redis::publish('newGame', json_encode([
            'id' => $game->id,
            'game' => $game->game,
            'bet' => $game->bet,
            'chance' => $game->chance,
            'username' => substr($user->username, 0, -2) . '...',
            'win' => $game->win
        ]));
    }

    private function getCoef($game, $win)
    {
        $currentPosition = count($game['used_positions']) + 1;
        $index = 0;

        // if ($currentPosition === 2 && !$win) {
        //     return 0;
        // }

        if (($currentPosition - 1) % 2 === 0) $index = 1;

        if (!isset($this->mine->mines[$game['bombs_count'] - 2][floor(($currentPosition) / 2) - 1][$index])) {
            if ($index === 0) {
                $index = 1;
            } else {
                $index = 0;
            }
        }

        return $this->mine->mines[$game['bombs_count'] - 2][floor(($currentPosition) / 2) - 1][$index];
    }

    private function random_float($min, $max) {
        return random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX );
    }
}
