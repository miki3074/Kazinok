<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $table = 'profit';

    protected $fillable = [
        'bank_dice', 'bank_mines', 'bank_coinflip', 'bank_wheel', 'comission', 'loser_comission', 'earn_dice', 'earn_mines', 'earn_coinflip', 'earn_wheel', 'jackpot_comission', 'old_earn_dice', 'old_earn_mines', 'old_earn_coinflip', 'old_earn_wheel', 'earn_slots', 'old_earn_slots', 'all_earn_dice', 'all_earn_mines', 'all_earn_coinflip', 'all_earn_wheel', 'all_earn_slots', 'earn_stairs', 'all_earn_slots', 'old_earn_stairs'
    ];
}
