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
        Schema::create('occupations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('anime_id')->unsigned();
            $table->integer('cast_id')->unsigned();
            $table->timestamps();

            if (DB::getDriverName()!== 'sqlite') {
                $table->foreign('anime_id')->references('id')->on('animes')->onUpdate('CASCADE');
                $table->foreign('cast_id')->references('id')->on('casts')->onUpdate('CASCADE');
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
        Schema::dropIfExists('occupations');
    }
};
