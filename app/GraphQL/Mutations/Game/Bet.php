<?php

namespace App\GraphQL\Mutations\Game;

use App\Game;
use App\Setting;
use App\Profit;
use App\GraphQL\Helpers\Game\GenerateHash;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type as GraphqlType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Closure;
use Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

class Bet extends Mutation
{
    protected $attributes = [
        'name' => 'Bet'
    ];

    public function type(): GraphqlType
    {
        return GraphQL::type('Game');
    }

    public function args(): array
    {
        return [
            'type' => [
                'name' => 'type',
                'type' => Type::nonNull(Type::string())
            ],
            'bet' => [
                'name' => 'bet',
                'type' => Type::nonNull(Type::string())
            ],
            'chance' => [
                'name' => 'chance',
                'type' => Type::nonNull(Type::string())
            ],
            'hid' => [
                'name' => 'hid',
                'type' => Type::nonNull(Type::string())
            ],
            'a' => [
                'name' => 'a',
                'type' => Type::string()
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

        $secret_key = "oxyeli_g@ndoni?";

        $hash = md5($args['hid'].$user->id.$secret_key.$args['hid']);

        if($hash != $args['a']) {
            //throw new \Exception(json_encode($game));
            header('HTTP/1.1 500 Internal Server Error');
            exit;
        }

        if ($args['bet'] < 1) {
            throw new \Exception('Минимальная ставка 1 руб.');
        }

        if ($args['bet'] > 1000000) {
            throw new \Exception('Максимальная ставка 1000000 руб.');
        }

        if ($args['chance'] < 1) {
            throw new \Exception('Минимальный шанс 1%');
        }

        if ($args['chance'] > 92) {
            throw new \Exception('Максимальный шанс 92%');
        }

        if ($user->balance < $args['bet']) {
            throw new \Exception('Недостаточно средств на балансе');
        }

        if ($user->hid !== $args['hid']) {
            throw new \Exception('Игра с данным хешем уже была сыграна');
        }

        if ($user->ban) {
            throw new \Exception('Ваш аккаунт заблокирован');
        }

        $user->decrement('balance', $args['bet']);

        $min = round(($args['chance'] / 100) * 999999, 0);
        $max = 999999 - round(($args['chance'] / 100) * 999999, 0);
        $isWin = false;

        $totalwin = round((97 / $args['chance']) * $args['bet'], 2);
        $setting = Setting::query()->find(1);
        $profit = Profit::query()->find(1);
        
        /*if($setting->antiminus == 1 && !$user->is_youtuber || $user->is_loser) { // Антиминус
            if($totalwin - $args['bet'] > $profit->bank_dice || $user->is_loser) {
                $args['type'] === 'min' ? $random = rand( ($args['chance'] * 10000) - 1, 999999) : $random = rand(0, 1000000 - ($args['chance'] * 10000) );
                $loseGame = json_decode($user->dice);
                $loseGame->random = $random;
                $user->update([
                    'dice' => json_encode($loseGame)
                ]);
            }
        }*/

        $dice = json_decode($user->dice, true);
        $random = $dice['random'];

        if ($args['type'] === 'min') {
            if ($random <= $min) {
                $isWin = true;
            } else {
                $isWin = false;
            }
        } else if ($args['type'] === 'max') {
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
            $user->update(['stat_dice' => $user->stat_dice + ($win - $args['bet'])]);

            if(!$user->is_youtuber) {
                $profit->update([
                    //'bank_dice' => $profit->bank_dice - ($win - $args['bet']),
                    'earn_dice' => $profit->earn_dice - ($win - $args['bet'])
                ]);
            }
        } else {
            $text = 'Выпало ' . $random;
            $user->update(['stat_dice' => $user->stat_dice - $args['bet']]);
            if(!$user->is_youtuber) {
                $profit->update([
                    //'bank_dice' => $profit->bank_dice + ($args['bet'] / 100) * (100 - $coms),
                    'earn_dice' => $profit->earn_dice + $args['bet']
                ]);
            }
        }

        $newDice = GenerateHash::generate();

        $user->update([
            'dice' => json_encode($newDice),
            'hid' => $newDice['hid']
        ]);

        if($user->wager < 0 || $user->wager - $args['bet'] < 0) {
            $user->wager = 0;
            $user->save();
        } else {
            $user->decrement('wager', $args['bet']);
        }
        
        $game = Game::query()->create([
            'user_id' => $user->id,
            'game' => 'dice',
            'bet' => $args['bet'],
            'chance' => $args['chance'],
            'win' => $win,
            'type' => $args['type'],
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

        return [
            'success' => $isWin,
            'text' => $text,
            'win' => $win,
            'id' => $game->id,
            'bet' => $game->bet,
            'chance' => $game->chance,
            'balance' => $user->balance
        ];
    }
}
