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
        Schema::table('occupations', function (Blueprint $table) {
            $table->integer('main_sub')->nullable();
        });
        Schema::table('occupations', function (Blueprint $table) {
            $table->string('character')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('occupations', function (Blueprint $table) {
            $table->dropColumn('main_sub');
        });
        Schema::table('occupations', function (Blueprint $table) {
            $table->dropColumn('character');
        });
    }
};
