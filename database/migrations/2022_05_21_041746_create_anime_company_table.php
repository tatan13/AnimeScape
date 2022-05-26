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
        Schema::create('anime_company', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('anime_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->timestamps();

            if (DB::getDriverName()!== 'sqlite') {
                $table->foreign('anime_id')->references('id')->on('animes')->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreign('company_id')->references('id')->on('companies')->onUpdate('CASCADE')->onDelete('CASCADE');
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
        Schema::dropIfExists('anime_company');
    }
};
