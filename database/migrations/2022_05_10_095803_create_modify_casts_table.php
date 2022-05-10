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
        Schema::create('modify_casts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cast_id')->unsigned();
            $table->string('name');
            $table->string('furigana')->nullable();
            $table->boolean('sex')->nullable();
            $table->string('office')->nullable();
            $table->string('url')->nullable();
            $table->string('twitter')->nullable();
            $table->string('blog')->nullable();
            $table->timestamps();

            if (DB::getDriverName()!== 'sqlite') {
                $table->foreign('cast_id')->references('id')->on('casts')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('modify_casts');
    }
};
