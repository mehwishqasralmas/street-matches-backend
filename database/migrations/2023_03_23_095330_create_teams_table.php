<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('logo_img_id')->nullable();
            $table->double('location_long');
            $table->double('location_lat');
            $table->string('address')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('creator_user_id');
            $table->timestamps();

            $table->foreign('logo_img_id')->references('id')->on('images');
            $table->foreign('creator_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
