<?php

namespace App\GraphQL\Types\Mines;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class Cashout extends GraphQLType
{
    protected $attributes = [
        'name' => 'MinesCashout',
        'description' => 'Игра mines'
    ];

    public function fields(): array
    {
        return [
            'win' => [
                'type' => Type::nonNull(Type::float())
            ],
            'history' => [
                'type' => Type::nonNull(Type::string())
            ],
            'balance' => [
                'type' => Type::nonNull(Type::float())
            ]
        ];
    }
}
