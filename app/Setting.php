<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'title',
        'description',
        'keywords',
        'kassa_id',
        'kassa_secret1',
        'kassa_secret2',
        'kassa_key',
        'xmpay_id',
        'xmpay_public',
        'xmpay_secret',
        'wallet_id',
        'wallet_secret',
        'wallet_desc',
        'swift_shop',
        'swift_api',
        'swift_shop_token',
        'rubpay_id',
        'rubpay_api',
        'min_payment_sum',
        'min_bonus_sum',
        'min_withdraw_sum',
        'max_qyc_withdraw_sum',
        'max_withdraw_sum',
        'ref_bonus',
        'xmpay_off',
        'ref_perc',
        'withdraws_on',
        'payments_on',
        'fkwallet_only',
        'deposit_per_n',
        'deposit_sum_n',
        'withdraw_request_limit',
        'bot_timer',
        'min_dep_withdraw',
        'vk_url',
        'vk_id',
        'vk_token',
        'refshare',
        'file_version',
        'connect_bonus',
        'antiminus',
        'piastrix_shop',
        'piastrix_secret',
        'gp_api'
    ];
}
