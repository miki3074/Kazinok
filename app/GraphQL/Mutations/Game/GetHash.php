<?php

namespace App\GraphQL\Mutations\Game;

use App\GraphQL\Helpers\Game\GenerateHash;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type as GraphqlType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Closure;
use Auth;
use Illuminate\Support\Str;

class GetHash extends Mutation
{
    protected $attributes = [
        'name' => 'GetHash'
    ];

    public function type(): GraphqlType
    {
        return GraphQL::type('GameHash');
    }

    public function resolve(
        $root,
        $args,
        $context,
        ResolveInfo $resolveInfo,
        Closure $getSelectFields
    ) {
        $user = Auth::user();
        $dice = GenerateHash::generate();

        $user->update([
            'dice' => json_encode($dice),
            'hid' => $dice['hid']
        ]);

        return [
            'hash' => $dice['hash'],
            'hid' => $dice['hid']
        ];
    }
}
