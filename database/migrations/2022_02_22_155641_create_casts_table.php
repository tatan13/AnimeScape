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
        Schema::create('casts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('furigana')->nullable();
            $table->boolean('sex')->nullable();
            $table->string('office')->nullable();
            $table->string('url')->nullable();
            $table->string('twitter')->nullable();
            $table->string('blog')->nullable();
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
        Schema::dropIfExists('casts');
    }
};
