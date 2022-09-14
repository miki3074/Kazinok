<?php

namespace App\GraphQL\Types\Game;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GameFullHash extends GraphQLType
{
    protected $attributes = [
        'name' => 'GameFullHash',
        'description' => 'Хэш игры'
    ];

    public function fields(): array
    {
        return [
            'hid' => [
                'type' => Type::string(),
                'description' => 'HID'
            ],
            'hash' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Хэш'
            ],
            'salt1' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Соль 1'
            ],
            'salt2' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Соль 2'
            ],
            'random' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Победное число'
            ],
            'string' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Полная строка'
            ]
        ];
    }
}
