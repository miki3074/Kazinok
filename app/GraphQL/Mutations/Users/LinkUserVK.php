<?php


namespace App\GraphQL\Mutations\Users;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type as GraphqlType;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Closure;
use Auth;

class LinkUserVK extends Mutation
{
    protected $attributes = [
        'name' => 'LinkUserVK'
    ];

    public function type(): GraphqlType
    {
        return GraphQL::type('CreateUserVK');
    }

    public function resolve(
        $root,
        $args,
        $context,
        ResolveInfo $resolveInfo,
        Closure $getSelectFields
    )
    {
        if (Auth::check()) {
            session_start();
            $_SESSION['auth_id'] = Auth::user()->id;
        } else {
            session_unset();
        }

        $params = [
            'client_id' => \config('services.vk.id'),
            'redirect_uri' => \config('services.vk.uri'),
            'response_type' => 'code'
        ];

        return [
            'url' => 'http://oauth.vk.com/authorize?' . urldecode(http_build_query($params))
        ];
    }
}
