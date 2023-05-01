<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->enum('dominate_foot', ['LEFT', 'RIGHT']);
            $table->double('weight');
            $table->double('height');
            $table->enum (
                'position',
                ['GK', 'DEF_CB', 'DEF_SW', 'DEF_FB', 'MID_CM', 'MID_DM', 'MID_ATK', 'FW_SS', 'FW_CF', 'FW_W']
            );
            $table->integer('year_active')->nullable();

            $table->string('first_name');
            $table->string('last_name');
            $table->date('birthdate')->nullable();
            $table->double('location_long');
            $table->double('location_lat');
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('img_id')->nullable();
            $table->unsignedBigInteger('creator_user_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('creator_user_id')->references('id')->on('users');
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
        Schema::dropIfExists('players');
    }
}
