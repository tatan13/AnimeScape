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
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->integer('will_watch')->default(0)->change();
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->boolean('spoiler')->default(0)->change();
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->boolean('give_up')->default(0);
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->integer('number_of_interesting_episode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->boolean('will_watch')->default(0);
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->boolean('spoiler')->nullable();
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->dropColumn('give_up');
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->dropColumn('number_of_interesting_episode');
        });
    }
};
