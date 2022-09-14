<?php


namespace App\GraphQL\Mutations\Users;

use Closure;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\Type as GraphqlType;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Auth;

class ResetPassword extends Mutation
{
    protected $attributes = [
        'name' => 'ResetPassword'
    ];

    public function type(): Type
    {
        return GraphQL::type('Success');
    }

    public function args(): array
    {
        return [
            'old_password' => [
                'name' => 'old_password',
                'type' => Type::nonNull(Type::string())
            ],
            'new_password' => [
                'name' => 'new_password',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'old_password' => ['required'],
            'new_password' => ['required', 'min:6']
        ];
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'old_password.required' => 'Введите старый пароль',
            'new_password.required' => 'Введите новый пароль',
            'new_password.min' => 'Минимальное кол-во символов у пароля: 6',
        ];
    }

    public function resolve(
        $root,
        $args,
        $context,
        ResolveInfo $resolveInfo,
        Closure $getSelectFields
    ) {
        $user = Auth::user();

        if ($user->password !== $args['old_password']) {
            throw new \Exception('Старый пароль не совпадает');
        }

        $user->update([
            'password' => $args['new_password']
        ]);

        return [
            'success' => true
        ];
    }
}
