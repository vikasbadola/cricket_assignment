<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Players extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('players');
        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('playerId');
            $table->bigInteger('teamId');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('imageUri');
            $table->bigInteger('jerseyNo');
            $table->string('country');
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
        Schema::dropIfExists('players');
    }
}
