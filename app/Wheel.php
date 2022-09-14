<?php

namespace App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Wheel extends Model
{

    protected $table = 'wheel';

    protected $fillable = ['winner_color', 'price', 'status', 'hash', 'profit', 'ranked', 'salt1', 'salt2', 'rotate'];

    protected $hidden = ['created_at', 'updated_at'];
}
