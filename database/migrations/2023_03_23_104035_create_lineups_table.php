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
            $table->enum(
              'position',
              ['GK', 'DEF_CB', 'DEF_SW', 'DEF_FB', 'MID_CM', 'MID_DM', 'MID_ATK', 'FW_SS', 'FW_CF', 'FW_W']
            );
            $table->integer('shirt_number')->nullable();

            $table->timestamps();

            $table->foreign('team_id')->references('id')->on('teams')
              ->onDelete('cascade')->onUpdate('cascade');;
            $table->foreign('match_id')->references('id')->on('matches')
              ->onDelete('cascade')->onUpdate('cascade');;
            $table->foreign('player_id')->references('id')->on('players')
              ->onDelete('cascade')->onUpdate('cascade');;

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
