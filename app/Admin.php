<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{

    protected $table = 'admins';

    protected $fillable = [
        'username', 'password'
    ];

    protected $hidden = [
        'remember_token',
    ];
}
