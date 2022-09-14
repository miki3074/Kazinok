<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopRefovodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('top_refovods', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('user_id');
            $table->double('sum', 16, 2);
            $table->bigInteger('ref_cnt');
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
        Schema::dropIfExists('top_refovods');
    }
}
