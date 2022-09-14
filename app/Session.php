<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['token', 'ref_id', 'httpref', 'auth_id', 'ip'];
}
