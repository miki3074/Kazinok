<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromocodeActivation extends Model
{
    protected $fillable = [
        'user_id', 'promo_id', 'type'
    ];
}
