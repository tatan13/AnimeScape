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
        Schema::create('animes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('title_short')->nullable();
            $table->integer('year');
            $table->integer('coor');
            $table->string('public_url')->nullable();
            $table->string('twitter')->nullable();
            $table->string('hash_tag')->nullable();
            $table->boolean('sex')->nullable();
            $table->integer('sequel')->nullable();
            $table->string('company')->nullable();
            $table->string('city_name')->nullable();
            $table->integer('average')->nullable();
            $table->integer('median')->nullable();
            $table->integer('max')->nullable();
            $table->integer('min')->nullable();
            $table->integer('count')->default(0);
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
        Schema::dropIfExists('animes');
    }
};
