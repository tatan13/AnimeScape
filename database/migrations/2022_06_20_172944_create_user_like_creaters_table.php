<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_like_creaters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('creater_id')->unsigned();
            $table->timestamps();

            if (DB::getDriverName()!== 'sqlite') {
                $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE');
                $table->foreign('creater_id')->references('id')->on('creaters')->onUpdate('CASCADE');
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
        Schema::dropIfExists('user_like_creaters');
    }
};
