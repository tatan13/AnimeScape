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
        Schema::create('tag_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('anime_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->integer('score');
            $table->string('comment', 400)->nullable();
            $table->timestamps();

            if (DB::getDriverName()!== 'sqlite') {
                $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreign('anime_id')->references('id')->on('animes')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreign('tag_id')->references('id')->on('tags')->onUpdate('CASCADE')->onDelete('CASCADE');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_reviews');
    }
};
