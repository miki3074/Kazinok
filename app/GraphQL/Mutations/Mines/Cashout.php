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

class Cashout extends Mutation
{
    protected $attributes = [
        'name' => 'MinesCashout'
    ];

    protected $mine;

    public function __construct(Mine $mine)
    {
        $this->mine = $mine;
    }

    public function type(): Type
    {
        return GraphQL::type('MinesCashout');
    }

    public function args(): array
    {
        return [
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
        //$hash = hash_hmac('md5', $user->id, $secret_key);
        $game = $this->mine->createGame($user, true);

        $gethash = json_decode($game->mine);
        $hash = md5($gethash->fair->hash.$user->id.$secret_key.$gethash->fair->hash);


        if($hash != $args['a']) {
            //throw new \Exception(json_encode($game));
            header('HTTP/1.1 500 Internal Server Error');
            exit;
        }
        

        if (!$user->mines_is_active) {
            throw new \Exception('У вас нет активной игры');
        }

        if ($user->mines_game == null) {
            throw new \Exception('У вас нет активной игры');
        }

        $winSum = $this->mine->cashout($user);

        $user->update([
            'mines_is_active' => 0
        ]);

        return [
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
}
