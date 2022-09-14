<?php


namespace App\GraphQL\Queries\Users;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Auth;

class User extends Query
{
    protected $attributes = [
        'name' => 'User'
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function resolve($root, $args)
    {
        return Auth::user();
    }
}
