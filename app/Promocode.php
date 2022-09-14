<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $fillable = [
        'name', 'sum', 'activation', 'act', 'wager', 'type', 'comment'
    ];
}
