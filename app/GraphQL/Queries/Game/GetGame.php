<?php

namespace App\GraphQL\Queries\Game;

use App\Game;
use App\Coinflip;
use App\GraphQL\Helpers\Game\GenerateHash;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type as GraphqlType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Closure;
use Auth;
use Illuminate\Support\Str;

class GetGame extends Mutation
{
    protected $attributes = [
        'name' => 'GetGame'
    ];

    public function type(): GraphqlType
    {
        return GraphQL::type('GameHistory');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
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
        $game = Game::query()->find($args['id']);

        if ($game->type == "dice" || $game->type == "mines") {
            $game = Coinflip::query()->find($args['id']);
        }

        if (!$game) {
            throw new \Exception('Игра не найдена');
        }

        if ($game->game == "dice" || $game->game == "mines") {

            $dice = json_decode($game->dice, true);

            return [
                'id' => $game->id,
                'game' => $game->game,
                'bet' => $game->bet,
                'chance' => $game->chance,
                'win' => $game->win,
                'type' => $game->type,
                'dice' => $dice,
                'mine' => $game->mine
            ];
        } else {
            return [
                'id' => $game->id,
                'game' => "coin",
                'bet' => $game->bet,
                'chance' => $game->coef,
                'win' => $game->coef * $game->bet,
            ];
        }
    }
}
