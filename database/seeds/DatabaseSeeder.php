<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\Admin::query()->find(1)) {
            \App\Admin::query()->create([
                'username' => 'admin',
                'password' => hash('sha256', 'admin')
            ]);
        }

        if (!\App\Setting::query()->find(1)) {
            \App\Setting::query()->create([
                'min_payment_sum' => 0,
                'min_bonus_sum' => 0,
                'min_withdraw_sum' => 0,
                'bot_timer' => 1
            ]);
        }
    }
}
