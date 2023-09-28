<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStadiumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stadiums', function (Blueprint $table) {
            $table->id();

        $table->string('name');
        $table->string('name_cn')->nullable();
        $table->longText('description')->nullable();
        $table->longText('description_cn')->nullable();
        $table->unsignedBigInteger('img_id')->nullable();
        $table->double('location_long');
        $table->double('location_lat');
        $table->string('address')->nullable();
        $table->unsignedBigInteger('owner_user_id');

        $table->string('available_sports')->nullable();
        $table->string('amenities')->nullable();

        $table->timestamps();

        $table->foreign('img_id')->references('id')->on('images');
        $table->foreign('owner_user_id')->references('id')
          ->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stadia');
    }
}
