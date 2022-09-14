<?php

namespace App\GraphQL\Types\Game;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GameHash extends GraphQLType
{
    protected $attributes = [
        'name' => 'GameHash',
        'description' => 'Хэш dice'
    ];

    public function fields(): array
    {
        return [
            'hash' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Hash'
            ],
            'hid' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'HID'
            ]
        ];
    }
}
