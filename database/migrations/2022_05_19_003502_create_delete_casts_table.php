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
        Schema::create('delete_casts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cast_id')->unsigned();
            $table->string('remark', 400)->nullable();
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
        Schema::dropIfExists('delete_casts');
    }
};
