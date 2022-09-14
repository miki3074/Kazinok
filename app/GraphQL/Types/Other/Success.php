<?php

namespace App\GraphQL\Types\Other;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class Success extends GraphQLType
{
    protected $attributes = [
        'name' => 'Success',
        'description' => 'Ответ да или нет'
    ];

    public function fields(): array
    {
        return [
            'success' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Ответ да или нет'
            ]
        ];
    }
}
