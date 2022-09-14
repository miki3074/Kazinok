<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $table = 'coinflip';

    protected $fillable = [
        'user_id', 'coef', 'bet', 'step', 'status'
    ];
}
