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

class LoginUser extends Mutation
{
    protected $attributes = [
        'name' => 'LoginUser'
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
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'username' => ['required'],
            'password' => ['required']
        ];
    }

    public function resolve(
        $root,
        $args,
        $context,
        ResolveInfo $resolveInfo,
        Closure $getSelectFields
    ) {
        $user = User::query()->where([['username', $args['username']], ['password', $args['password']], ['is_bot', 0]])->first();

        if (!$user) {
            throw new \Exception('Неверный логин или пароль');
        }
        Auth::login($user, true);
        
        //$token = Str::random(60);
        $token = $user->api_token;

        $user->update([
            'used_ip' => $this->getIp()
        ]);

        return [
            'token' => $token
        ];
    }

    public function getIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
