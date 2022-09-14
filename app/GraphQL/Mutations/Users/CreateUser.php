<?php

namespace App\GraphQL\Mutations\Users;

use App\User;
use App\ReferralPayment;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type as GraphqlType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Closure;
use Auth;
use Illuminate\Support\Str;

class CreateUser extends Mutation
{
    protected $attributes = [
        'name' => 'CreateUser'
    ];

    public function type(): GraphqlType
    {
        return GraphQL::type('CreateUser');
    }

    public function args(): array
    {
        return [
            'username' => [
                'name' => 'username',
                'type' => Type::nonNull(Type::string())
            ],
            'password' => [
                'name' => 'password',
                'type' => Type::nonNull(Type::string())
            ],
            'password_confirm' => [
                'name' => 'password_confirm',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'username' => ['required', 'min:4', 'max:12'],
            'password' => ['required', 'min:6', 'max:16'],
            'password_confirm' => ['required', 'min:6', 'max:16']
        ];
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'username.required' => 'Введите имя пользователя',
            'password.required' => 'Введите пароль',
            'password_confirm.required' => 'Введите второй пароль',
            'username.min' => 'Минимальное кол-во символов в имени: 4',
            'username.max' => 'Максимальное кол-во символов в имени: 12',
            'password.min' => 'Минимальное кол-во символов у пароля: 6',
            'password.max' => 'Максимальное кол-во символов у пароля: 16',
            'password_confirm.min' => 'Минимальное кол-во символов у пароля: 6',
            'password_confirm.max' => 'Минимальное кол-во символов у пароля: 16'
        ];
    }

    public function resolve(
        $root,
        $args,
        $context,
        ResolveInfo $resolveInfo,
        Closure $getSelectFields
    ) {
        header('HTTP/1.1 500 Internal Server Error');
        exit;
    }
}
