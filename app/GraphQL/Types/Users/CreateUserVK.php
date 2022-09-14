<?php

namespace App\GraphQL\Types\Users;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CreateUserVK extends GraphQLType
{
    protected $attributes = [
        'name' => 'CreateUserVK',
        'description' => 'Регистрация пользователя через вк'
    ];

    public function fields(): array
    {
        return [
            'url' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Ссылка на авторизацию'
            ]
        ];
    }
}
