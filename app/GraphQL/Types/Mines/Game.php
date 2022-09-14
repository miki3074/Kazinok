<?php

namespace App\GraphQL\Types\Mines;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class Game extends GraphQLType
{
    protected $attributes = [
        'name' => 'MinesGame',
        'description' => 'Игра mines'
    ];

    public function fields(): array
    {
        return [
            'success' => [
                'type' => Type::nonNull(Type::boolean())
            ],
            'hash' => [
                'type' => Type::string()
            ],
            'bet' => [
                'type' => Type::float()
            ],
            'bomb' => [
                'type' => Type::int()
            ],
            'selected_bombs' => [
                'type' => Type::string()
            ],
            'active_path' => [
                'type' => Type::int()
            ]
        ];
    }
}
