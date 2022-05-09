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
        Schema::table('modify_occupations', function (Blueprint $table) {
            if (DB::getDriverName()!== 'sqlite') {
                $table->dropForeign('modify_occupations_anime_id_foreign');
            }
            $table->foreign('anime_id')->references('id')->on('animes')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modify_occupations', function (Blueprint $table) {
            $table->dropForeign('modify_occupations_anime_id_foreign');
        });
    }
};
