<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallets extends Model
{
    protected $fillable = ['user_id', 'wallet', 'system', 'is_included'];
}
