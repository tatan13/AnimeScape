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
        Schema::table('animes', function (Blueprint $table) {
            $table->integer('before_median')->nullable();
        });            
        Schema::table('animes', function (Blueprint $table) {
            $table->integer('before_average')->nullable();
        });            
        Schema::table('animes', function (Blueprint $table) {
            $table->float('before_stdev', 8, 2)->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->integer('before_count')->default(0);
        });   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('before_median');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('before_average');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('before_stdev');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('before_count');
        });
    }
};
