<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('freekassa_id')->nullable();
            $table->string('freekassa_secret1')->nullable();
            $table->string('freekassa_secret2')->nullable();
            $table->float('min_payment_sum')->nullable();
            $table->float('min_bonus_sum')->nullable();
            $table->float('min_withdraw_sum')->nullable();
            $table->float('bot_timer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
