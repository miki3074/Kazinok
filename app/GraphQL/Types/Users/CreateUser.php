<?php

namespace App\GraphQL\Types\Users;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class CreateUser extends GraphQLType
{
    protected $attributes = [
        'name' => 'CreateUser',
        'description' => 'Токен авторизованного юзера'
    ];

    public function fields(): array
    {
        return [
            'token' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Токен авторизованного юзера'
            ]
        ];
    }
}
