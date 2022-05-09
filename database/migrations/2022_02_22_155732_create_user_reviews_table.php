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
        Schema::create('user_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('anime_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('score')->nullable();
            $table->string('one_word_comment', 400)->nullable();
            $table->text('long_word_comment')->nullable();
            $table->boolean('spoiler')->nullable();
            $table->boolean('watch')->default(0);
            $table->boolean('will_watch')->default(0);
            $table->timestamps();

            if (DB::getDriverName()!== 'sqlite') {
                $table->foreign('anime_id')->references('id')->on('animes')->onUpdate('CASCADE');
                $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE');
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
        Schema::dropIfExists('user_reviews');
    }
};
