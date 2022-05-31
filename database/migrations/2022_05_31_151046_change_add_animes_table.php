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
        Schema::table('add_animes', function (Blueprint $table) {
            $table->integer('anime_id')->unsigned()->nullable();
        });
        Schema::table('add_animes', function (Blueprint $table) {
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
        Schema::table('add_animes', function (Blueprint $table) {
            $table->dropColumn('anime_id');
        });
        Schema::table('add_animes', function (Blueprint $table) {
            $table->dropForeign('add_animes_anime_id_foreign');
        });
    }
};
