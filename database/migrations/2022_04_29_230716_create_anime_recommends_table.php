<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anime_recommends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('anime_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('recommendation_score');
            $table->foreign('anime_id')->references('id')->on('animes')->onUpdate('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE');
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
        Schema::dropIfExists('anime_recommends');
    }
};
