<?php

namespace App\GraphQL\Types\Game;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class Game extends GraphQLType
{
    protected $attributes = [
        'name' => 'Game',
        'description' => 'Игра dice'
    ];

    public function fields(): array
    {
        return [
            'success' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Победа или проигрыш'
            ],
            'text' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Текст сообщения'
            ],
            'win' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Выигрыш'
            ],
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID игры'
            ],
            'bet' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Ставка'
            ],
            'chance' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Шанс'
            ],
            'balance' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Баланс'
            ]
        ];
    }
}
