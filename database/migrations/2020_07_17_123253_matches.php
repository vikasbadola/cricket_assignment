<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Matches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('matches');
        Schema::create('matches', function (Blueprint $table) {
            $table->bigIncrements('matchId');
            $table->bigInteger('teamA');
            $table->bigInteger('teamB');
            $table->bigInteger('winner');
            $table->tinyInteger('points');
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
        Schema::dropIfExists('matches');
    }
}
