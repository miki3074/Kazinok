<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telegram extends Model
{
    protected $fillable = ['username', 'command', 'message', 'json', 'withdraw_id', 'user_id'];
}
