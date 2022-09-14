<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = [
        'payment_id', 'user_id', 'sum', 'sumNoCom', 'wallet', 'system', 'status', 'fake', 'is_auto', 'reason', 'username', 'info'
    ];
    
    protected $casts = ['reason' => 'array'];
}
