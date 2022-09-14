<?php

declare(strict_types=1);

use example\Mutation\ExampleMutation;
use example\Query\ExampleQuery;
use example\Type\ExampleRelationType;
use example\Type\ExampleType;

return [

    // The prefix for routes
    'prefix' => 'graphql',

    // The routes to make GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Route
    //
    // Example:
    //
    // Same route for both query and mutation
    //
    // 'routes' => 'path/to/query/{graphql_schema?}',
    //
    // or define each route
    //
    // 'routes' => [
    //     'query' => 'query/{graphql_schema?}',
    //     'mutation' => 'mutation/{graphql_schema?}',
    // ]
    //
    'routes' => '{graphql_schema?}',

    // The controller to use in GraphQL request. Either a string that will apply
    // to both query and mutation or an array containing the key 'query' and/or
    // 'mutation' with the according Controller and method
    //
    // Example:
    //
    // 'controllers' => [
    //     'query' => '\Rebing\GraphQL\GraphQLController@query',
    //     'mutation' => '\Rebing\GraphQL\GraphQLController@mutation'
    // ]
    //
    'controllers' => \Rebing\GraphQL\GraphQLController::class.'@query',

    // Any middleware for the graphql route group
    'middleware' => [],

    // Additional route group attributes
    //
    // Example:
    //
    // 'route_group_attributes' => ['guard' => 'api']
    //
    'route_group_attributes' => [],

    // The name of the default schema used when no argument is provided
    // to GraphQL::schema() or when the route is used without the graphql_schema
    // parameter.
    'default_schema' => 'default',

    // The schemas for query and/or mutation. It expects an array of schemas to provide
    // both the 'query' fields and the 'mutation' fields.
    //
    // You can also provide a middleware that will only apply to the given schema
    //
    // Example:
    //
    //  'schema' => 'default',
    //
    //  'schemas' => [
    //      'default' => [
    //          'query' => [
    //              'users' => 'App\GraphQL\Query\UsersQuery'
    //          ],
    //          'mutation' => [
    //
    //          ]
    //      ],
    //      'user' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\ProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //      'user/me' => [
    //          'query' => [
    //              'profile' => 'App\GraphQL\Query\MyProfileQuery'
    //          ],
    //          'mutation' => [
    //
    //          ],
    //          'middleware' => ['auth'],
    //      ],
    //  ]
    //
    'schemas' => [
        'default' => [
            'mutation' => [
                'CreateUser' => \App\GraphQL\Mutations\Users\CreateUser::class,
                'LoginUser' => \App\GraphQL\Mutations\Users\LoginUser::class,
                'CreateUserVK' => \App\GraphQL\Mutations\Users\CreateUserVK::class,
                'GetHashGuest' => \App\GraphQL\Mutations\Game\GetHashGuest::class
            ],
            'query' => [
                'GetGame' => \App\GraphQL\Queries\Game\GetGame::class
            ]
        ],
        'user' => [
            'query' => [
                'User' => \App\GraphQL\Queries\Users\User::class
            ],
            'mutation' => [
                'ResetPassword' => \App\GraphQL\Mutations\Users\ResetPassword::class,
                'LinkUserVK' => \App\GraphQL\Mutations\Users\LinkUserVK::class
            ],
            'middleware' => ['auth:api']
        ],
        'game' => [
            'mutation' => [
                'GetHash' => \App\GraphQL\Mutations\Game\GetHash::class,
                'Bet' => \App\GraphQL\Mutations\Game\Bet::class
            ],
            'middleware' => ['auth:api']
        ],
        'mines' => [
            'mutation' => [
                'MinesPlay' => \App\GraphQL\Mutations\Mines\Play::class,
                'MinesPath' => \App\GraphQL\Mutations\Mines\Path::class,
                'MinesCashOut' => \App\GraphQL\Mutations\Mines\Cashout::class,
                'MinesGame' => \App\GraphQL\Mutations\Mines\Game::class
            ],
            'middleware' => ['auth:api']
        ]
    ],

    // The types available in the application. You can then access it from the
    // facade like this: GraphQL::type('user')
    //
    // Example:
    //
    // 'types' => [
    //     'user' => 'App\GraphQL\Type\UserType'
    // ]
    //
    'types' => [
        'User' => \App\GraphQL\Types\Users\User::class,
        'CreateUserVK' => \App\GraphQL\Types\Users\CreateUserVK::class,
        'CreateUser' => \App\GraphQL\Types\Users\CreateUser::class,
        'Success' => \App\GraphQL\Types\Other\Success::class,
        'GameHash' => \App\GraphQL\Types\Game\GameHash::class,
        'Game' => \App\GraphQL\Types\Game\Game::class,
        'GameFullHash' => \App\GraphQL\Types\Game\GameFullHash::class,
        'GameHistory' => \App\GraphQL\Types\Game\GameHistory::class,
        'MinesPlay' => \App\GraphQL\Types\Mines\Play::class,
        'MinesPath' => \App\GraphQL\Types\Mines\Path::class,
        'MinesCashout' => \App\GraphQL\Types\Mines\Cashout::class,
        'MinesGame' => \App\GraphQL\Types\Mines\Game::class
    ],

    // The types will be loaded on demand. Default is to load all types on each request
    // Can increase performance on schemes with many types
    // Presupposes the config type key to match the type class name property
    'lazyload_types' => false,

    // This callable will be passed the Error object for each errors GraphQL catch.
    // The method should return an array representing the error.
    // Typically:
    // [
    //     'message' => '',
    //     'locations' => []
    // ]
    'error_formatter' => ['\Rebing\GraphQL\GraphQL', 'formatError'],

    /*
     * Custom Error Handling
     *
     * Expected handler signature is: function (array $errors, callable $formatter): array
     *
     * The default handler will pass exceptions to laravel Error Handling mechanism
     */
    'errors_handler' => ['\Rebing\GraphQL\GraphQL', 'handleErrors'],

    // You can set the key, which will be used to retrieve the dynamic variables
    'params_key' => 'variables',

    /*
     * Options to limit the query complexity and depth. See the doc
     * @ https://webonyx.github.io/graphql-php/security
     * for details. Disabled by default.
     */
    'security' => [
        'query_max_complexity' => null,
        'query_max_depth' => 10,
        'disable_introspection' => true,
    ],

    /*
     * You can define your own pagination type.
     * Reference \Rebing\GraphQL\Support\PaginationType::class
     */
    'pagination_type' => \Rebing\GraphQL\Support\PaginationType::class,

    /*
     * Config for GraphiQL (see (https://github.com/graphql/graphiql).
     */
    'graphiql' => [
        'prefix' => '/graphiql',
        'controller' => \Rebing\GraphQL\GraphQLController::class.'@graphiql',
        'middleware' => [],
        'view' => 'graphql::graphiql',
        'display' => env('ENABLE_GRAPHIQL', true),
    ],

    /*
     * Overrides the default field resolver
     * See http://webonyx.github.io/graphql-php/data-fetching/#default-field-resolver
     *
     * Example:
     *
     * ```php
     * 'defaultFieldResolver' => function ($root, $args, $context, $info) {
     * },
     * ```
     * or
     * ```php
     * 'defaultFieldResolver' => [SomeKlass::class, 'someMethod'],
     * ```
     */
    'defaultFieldResolver' => null,

    /*
     * Any headers that will be added to the response returned by the default controller
     */
    'headers' => [],

    /*
     * Any JSON encoding options when returning a response from the default controller
     * See http://php.net/manual/function.json-encode.php for the full list of options
     */
    'json_encoding_options' => 0,
];
