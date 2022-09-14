<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable = [
        'username',
        'old_username',
        'password', 
        'api_token', 
        'game_token',
        'game_token_date',
        'balance',
	    'comment',
        'is_vk', 
        'vk_id', 
        'vk_username',
        'vk_only',
        'is_tg',
        'tg_username',
        'tg_only',
        'tg_id',
        'dice', 
        'hid', 
        'mines_game', 
        'mines_is_active', 
        'referral_use', 
        'referral_use_promo', 
        'link_trans',
        'link_reg',
        'is_bot', 
        'created_ip', 
        'used_ip', 
        'is_admin', 
        'is_moder',
        'is_promocoder',
        'is_youtuber',
        'is_loser',
        'is_ref_bonus',
        'ban',
        'daily_bonus',
        'ban_reason',
        'wager',
        'ref_perc',
        'ref_bonus_cnt',
        'ref_active_cnt',
        'ref_bonus_received',
        'ref_fake',
        'promo_dep_sum',
        'ref_fake_cnt',
        'ref_fake_reason',
        'available_ref_balance',
        'bonus_use', 
        'coinflip',
        'wheel',
        'slots',
        'mines',
        'stat_dice',
        'httpref',
        'auto_withdraw_cnt',
        'promo_bal_sum',
        'wallet_qiwi',
        'wallet_fk',
        'wallet_yoomoney',
        'wallet_card',
        'stairs_game',
        'stairs_is_active',
        'wallet_piastrix',
        'stairs',
        'rang_points',
        'rang_ref',
        'rang_dep',
        'current_rang',
        'rang_1',
        'rang_2',
        'rang_3',
        'rang_4',
        'rang_5',
        'rang_6'
    ];

    protected $hidden = [
        'remember_token',
    ];
}
