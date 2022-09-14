<?php

namespace App\GraphQL\Mutations\Mines;

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

class Game extends Mutation
{
    protected $attributes = [
        'name' => 'MinesGame'
    ];

    protected $mine;

    public function __construct(Mine $mine)
    {
        $this->mine = $mine;
    }

    public function type(): Type
    {
        return GraphQL::type('MinesGame');
    }

    public function resolve(
        $root,
        $args,
        $context,
        ResolveInfo $resolveInfo,
        Closure $getSelectFields
    ) {
        $user = Auth::user();

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
}
