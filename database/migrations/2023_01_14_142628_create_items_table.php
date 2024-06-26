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
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('anime_id')->unsigned();
            $table->integer('category');
            $table->integer('site_id');
            $table->string('url');
            $table->integer('number')->unsigned();
            $table->timestamps();

            if (DB::getDriverName()!== 'sqlite') {
                $table->foreign('anime_id')->references('id')->on('animes')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('items');
    }
};
