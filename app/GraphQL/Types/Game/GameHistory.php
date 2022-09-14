<?php

namespace App\GraphQL\Types\Game;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GameHistory extends GraphQLType
{
    protected $attributes = [
        'name' => 'GameHistory',
        'description' => 'История игры'
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'ID'
            ],
            'game' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Игра'
            ],
            'bet' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Сумма ставки'
            ],
            'chance' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Коэфициент'
            ],
            'win' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Выигрыш'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'Тип игры'
            ],
            'dice' => [
                'type' => GraphQL::type('GameFullHash'),
                'description' => 'Хэш игры'
            ],
            'mine' => [
                'type' => Type::string(),
                'description' => 'Мины'
            ],
        ];
    }
}
