<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['SEARCH_PLAYERS', 'SEARCH_TEAM', 'CHALLENGE_TEAM']);
            $table->double('location_long');
            $table->double('location_lat');
            $table->string('address')->nullable();
            $table->unsignedBigInteger('img_id')->nullable();
            $table->longText('description');
            $table->string('players_positions')->nullable();
            $table->string('players_cnts')->nullable();
            $table->unsignedBigInteger('creator_user_id');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->dateTime('schedule_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();

            $table->foreign('creator_user_id')->references('id')->on('users')
              ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')
              ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('img_id')->references('id')->on('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
