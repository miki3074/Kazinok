<?php

namespace App\GraphQL\Types\Mines;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class Play extends GraphQLType
{
    protected $attributes = [
        'name' => 'MinesPlay',
        'description' => 'Игра mines'
    ];

    public function fields(): array
    {
        return [
            'hash' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Хэш игры'
            ],
            'balance' => [
                'type' => Type::nonNull(Type::float())
            ]
        ];
    }
}
