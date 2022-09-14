<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'sum', 'username', 'status', 'fake', 'code','system', 'rubpay_id', 'rubpay_sum', 'to_wallet', 'from_wallet', 'rubpay_rec_id', 'our_com', 'rubpay_link'
    ];
}
