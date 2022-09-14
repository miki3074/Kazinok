<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralPayment extends Model
{
    protected $fillable = [
        'user_id', 'referral_id', 'sum', 'shave'
    ];
}
