<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'owner_id', 'post_id', 'type'
    ];
}
