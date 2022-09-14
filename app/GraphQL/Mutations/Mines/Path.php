<?php

namespace App\GraphQL\Mutations\Mines;

use App\Game;
use App\Setting;
use App\Profit;
use App\GraphQL\Helpers\Game\GenerateHash;
use App\GraphQL\Helpers\Mines\Mine;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type as GraphqlType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Closure;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class Path extends Mutation
{
    protected $attributes = [
        'name' => 'MinesPath'
    ];

    protected $mine;

    public function __construct(Mine $mine)
    {
        $this->mine = $mine;
    }

    public function type(): Type
    {
        return GraphQL::type('MinesPath');
    }

    public function args(): array
    {
        return [
            'bomb' => [
                'name' => 'bomb',
                'type' => Type::nonNull(Type::int())
            ]
        ];
    }

    public function resolve(
        $root,
        $args,
        $context,
        ResolveInfo $resolveInfo,
        Closure $getSelectFields
    ) {
        $user = Auth::user();

        if ($args['bomb'] < 1 || $args['bomb'] > 25) {
            throw new \Exception('Выбрана неправильная клетка');
        }

        if (!$user->mines_is_active) {
            throw new \Exception('У Вас нет активной игры');
        }

        $game = json_decode($user->mines_game, true);

        if (isset($game['used_positions'][$args['bomb']])) {
            throw new \Exception('Вы уже задействовали эту клетку');
        }
 
        $win = true; // Угадал клетку
        $instWin = false;

        $setting = Setting::query()->find(1);
        $profit = Profit::query()->find(1);

        if($setting->antiminus == 1 && !$game['bombs'][$args['bomb']] && !$user->is_youtuber || $user->is_loser) { // Антиминус
            $game['used_positions'][$args['bomb']] = 0;
            $user->update([
                'mines_game' => json_encode($game)
            ]);
            $totalwin = $this->mine->getCoef($user, true) * $game['bet'] - $game['bet'];
            if($totalwin > $profit->bank_mines || $user->is_loser) {
                $bombRemove = false;
                $i = 0;
                foreach($game['bombs'] as $i => $b) {
                    if($bombRemove == false) {
                        if($b == 1) {
                            $game['bombs'][$i] = 0;
                            $bombRemove = true;
                        }
                    }
                    $i += 1;
                }
                $game['bombs'][$args['bomb']] = 1;
                $user->update([
                    'mines_game' => json_encode($game)
                ]);
            }
        }

        if ($game['bombs'][$args['bomb']]) {
            $win = false; // Попал на бомбу
        }

        $bombs = null;
        $winSum = 0;
        $id = 0;
        $history = [];

        if ($win) {
            $game['used_positions'][$args['bomb']] = 1;

            $user->update([
                'mines_game' => json_encode($game)
            ]);

            if (25 - count($game['used_positions']) === $game['bombs_count']) {
                $instWin = true;

                $winSum = $this->mine->cashout($user);

                $user->update([
                    'mines_is_active' => 0
                ]);

                $bd = $this->mine->createGame($user, true);

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
            $game['used_positions'][$args['bomb']] = 0;

            $user->update([
                'mines_game' => json_encode($game)
            ]);

            $bombs = $game['bombs'];

            $user->update([
                'mines_is_active' => 0
            ]);

            $bd = $this->mine->createGame($user, false);

            $id = $bd->id;
            $history = [
                'id' => $bd->id,
                'bet' => $bd->bet,
                'chance' => $bd->chance,
                'username' => substr($user->username, 0, -2) . '...',
                'win' => $bd->win
            ];

            if($user->is_loser) {
                $profit->comission = $profit->loser_comission;
            }
            
            if(!$user->is_youtuber) {
                $profit->update([
                    'bank_mines' => $profit->bank_mines + ($game['bet'] / 100) * (100 - $profit->comission),
                    'earn_mines' => $profit->earn_mines + ($game['bet'] / 100) * $profit->comission
                ]);
            }
        }

        $user->update([
            'mines_game' => json_encode($game)
        ]);

        return [
            'win' => $win,
            'selected_bombs' => json_encode($game['used_positions']),
            'bombs' => json_encode($bombs),
            'insta_win' => $instWin,
            'win_sum' => $winSum,
            'id' => $id,
            'history' => json_encode($history)
        ];
    }
}
