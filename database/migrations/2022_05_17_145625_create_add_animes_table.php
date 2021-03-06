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
        Schema::create('add_animes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('title_short')->nullable();
            $table->integer('year')->nullable();
            $table->integer('coor')->nullable();
            $table->string('public_url')->nullable();
            $table->string('twitter')->nullable();
            $table->string('hash_tag')->nullable();
            $table->boolean('sex')->nullable();
            $table->integer('sequel')->nullable();
            $table->string('company')->nullable();
            $table->string('city_name')->nullable();
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
        Schema::dropIfExists('add_animes');
    }
};
