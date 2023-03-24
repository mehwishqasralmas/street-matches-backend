<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lineups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('match_id');
            $table->unsignedBigInteger('player_id');
            $table->string('position');
            $table->integer('shirt_number')->nullable();

            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('match_id')->references('id')->on('matches');
            $table->foreign('player_id')->references('id')->on('players');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lineups');
    }
}
