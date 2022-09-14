<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopRefovods extends Model
{
    protected $fillable = ['id', 'user_id', 'username', 'sum', 'ref_cnt'];
}
