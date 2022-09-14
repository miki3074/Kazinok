<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    protected $fillable = [
        'user_id', 'is_admin', 'message'
    ];
}
