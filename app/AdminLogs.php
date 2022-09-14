<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminLogs extends Model
{
    protected $fillable = [
        'user_id', 'action', 'role','request'
    ];
}
