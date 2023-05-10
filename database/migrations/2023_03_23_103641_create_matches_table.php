<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('home_team_id');
            $table->unsignedBigInteger('away_team_id');
            $table->double('location_long')->nullable();
            $table->double('location_lat')->nullable();
            $table->string('address');
            $table->dateTime('schedule_time');
            $table->dateTime('start_time')->nullable();
            $table->enum (
              'status',
              ['NOT_STARTED', '1_TERM', '2_TERM', 'FINISHED']
            )->default('NOT_STARTED');
            $table->unsignedBigInteger('creator_user_id');
            $table->timestamps();

            $table->foreign('home_team_id')->references('id')->on('teams')
              ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('away_team_id')->references('id')->on('teams')
              ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')
              ->onDelete('cascade')->onUpdate('cascade');
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
