<?php

namespace App\GraphQL\Mutations\Mines;

use App\Game;
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

class Play extends Mutation
{
    protected $attributes = [
        'name' => 'MinesPlay'
    ];

    protected $mine;

    public function __construct(Mine $mine)
    {
        $this->mine = $mine;
    }

    public function type(): Type
    {
        return GraphQL::type('MinesPlay');
    }

    public function args(): array
    {
        return [
            'bet' => [
                'name' => 'bet',
                'type' => Type::nonNull(Type::float())
            ],
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

        if ($args['bet'] < 1 or !is_numeric($args['bet'])) {
            throw new \Exception('Минимальная ставка 1 руб.');
        }

        if ($args['bet'] > 1000000) {
            throw new \Exception('Максимальная ставка 1000000 руб.');
        }

        if ($args['bomb'] < 2) {
            throw new \Exception('Минимальный кол-во бомб: 2');
        }

        if ($args['bomb'] > 24) {
            throw new \Exception('Максимальный кол-во бомб: 24');
        }

        if ($user->balance < $args['bet']) {
            throw new \Exception('Недостаточно средств на балансе');
        }

        if ($user->mines_is_active) {
            throw new \Exception('У Вас уже есть активная игра');
        }

        if ($user->ban) {
            throw new \Exception('Ваш аккаунт заблокирован');
        }
        
        $generated = $this->mine->generate($args['bomb']);

        $game = [
            'bombs' => $generated['bombs'],
            'used_positions' => [],
            'bombs_count' => $args['bomb'],
            'bet' => $args['bet'],
            'fair' => [
                'salt' => $generated['salt'],
                'string' => $generated['string'],
                'hash' => $generated['hash']
            ]
        ];

        $user->decrement('balance', $args['bet']);

        $user->update([
            'mines_game' => json_encode($game),
            'mines_is_active' => 1
        ]);

        if($user->wager < 0 || $user->wager - $args['bet'] < 0) {
            $user->wager = 0;
            $user->save();
        } else {
            $user->decrement('wager', $args['bet']);
        }
        
        return [
            'hash' => $generated['hash'],
            'balance' => $user->balance
        ];
    }
}
