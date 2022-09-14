<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'user_id', 'game', 'bet', 'chance', 'win', 'type', 'dice', 'mine', 'fake'
    ];
}
