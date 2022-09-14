<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 256);
            $table->text('password');
            $table->string('api_token', 80)
                ->unique()
                ->nullable()
                ->default(null);
            $table->double('balance',255, 2)->default(0.00);
            $table->boolean('is_vk');
            $table->bigInteger('vk_id')->nullable();
            $table->string('vk_username')->nullable();
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
        Schema::dropIfExists('users');
    }
}
