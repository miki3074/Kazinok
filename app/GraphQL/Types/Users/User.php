<?php


namespace App\GraphQL\Types\Users;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class User extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'Информация пользователя',
        'model' => \App\User::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::Id()),
                'description' => 'ID пользователя'
            ],
            'username' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Имя пользователя'
            ],
            'balance' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Баланс пользователя'
            ],
            'is_vk' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Авторизация через ВК'
            ],
            'vk_id' => [
                'type' => Type::Id(),
                'description' => 'ID вк'
            ],
            'vk_username' => [
                'type' => Type::string(),
                'description' => 'Имя пользователя вк'
            ],
            'vk_only' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Только ВК'
            ],
            'is_tg' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Авторизация через ВК'
            ],
            'tg_id' => [
                'type' => Type::Id(),
                'description' => 'ID tg'
            ],
            'tg_username' => [
                'type' => Type::string(),
                'description' => 'Имя пользователя tg'
            ],
            'tg_only' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Только tg'
            ],
            'is_admin' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Привилегия Администратор'
            ],
            'is_moder' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Привилегия Модератор'
            ],
            'is_promocoder' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Привилегия Промокодер'
            ],
            'bonus_use' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Бонус за привязку'
            ],
            'ban' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Блокировка аккаунта'
            ],
            'ban_reason' => [
                'type' => Type::string(),
                'description' => 'Причина блокировки'
            ],
            'wallet_qiwi' => [
                'type' => Type::string(),
                'description' => 'Причина блокировки'
            ],
            'wallet_fk' => [
                'type' => Type::string(),
                'description' => 'Причина блокировки'
            ],
            'wallet_yoomoney' => [
                'type' => Type::string(),
                'description' => 'Причина блокировки'
            ],
            'wallet_card' => [
                'type' => Type::string(),
                'description' => 'Причина блокировки'
            ],
            'wallet_piastrix' => [
                'type' => Type::string(),
                'description' => 'Причина блокировки'
            ],
            'current_rang' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Текущий ранг'
            ],
            'rang_points' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Очки ранга'
            ],
        ];
    }
}
