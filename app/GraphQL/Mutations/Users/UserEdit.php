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

class UserEdit extends Mutation
{
    protected $attributes = [
        'name' => 'UserEdit'
    ];

    public function type(): Type
    {
        return GraphQL::type('Success');
    }

    public function args(): array
    {
        return [
            'username' => [
                'name' => 'username',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'username' => ['required', 'min:4', 'max:12'];
        ];
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'username.required' => 'Введите новый ник',
            'username.min' => 'Минимальное кол-во символов у ника: 4',
            'username.max' => 'Минимальное кол-во символов у ника: 12',
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

        $user->update([
            'username' => $args['username']
        ]);

        return [
            'success' => true
        ];
    }
}
