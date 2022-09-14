<?php

namespace App\GraphQL\Types\Mines;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class Path extends GraphQLType
{
    protected $attributes = [
        'name' => 'MinesPath',
        'description' => 'Игра mines'
    ];

    public function fields(): array
    {
        return [
            'win' => [
                'type' => Type::nonNull(Type::boolean())
            ],
            'selected_bombs' => [
                'type' => Type::nonNull(Type::string())
            ],
            'bombs' => [
                'type' => Type::string()
            ],
            'insta_win' => [
                'type' => Type::nonNull(Type::boolean())
            ],
            'win_sum' => [
                'type' => Type::nonNull(Type::float())
            ],
            'id' => [
                'type' => Type::int()
            ],
            'history' => [
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }
}
