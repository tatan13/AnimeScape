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
        Schema::table('anime_recommends', function (Blueprint $table) {
            if (DB::getDriverName()!== 'sqlite') {
                $table->dropForeign('anime_recommends_anime_id_foreign');
                $table->dropForeign('anime_recommends_user_id_foreign');
            }
            $table->foreign('anime_id')->references('id')->on('animes')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('anime_recommends', function (Blueprint $table) {
            $table->dropForeign('anime_recommends_anime_id_foreign');
            $table->dropForeign('anime_recommends_user_id_foreign');
        });
    }
};
