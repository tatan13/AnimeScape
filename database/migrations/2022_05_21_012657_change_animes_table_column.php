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
            $table->dropColumn('sex');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('sequel');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('company');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->string('furigana')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->integer('number_of_episode')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->integer('number_of_interesting_episode')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->text('summary')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->string('abema_id')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->string('unext_id')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->string('fod_id')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->string('amazon_prime_video_id')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->string('d_anime_store_id')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->string('disney_plus_id')->nullable();
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
            $table->boolean('sex')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->integer('sequel')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->string('company')->nullable();
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('furigana');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('number_of_episode');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('number_of_interesting_episode');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('summary');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('abema_id');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('unext_id');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('fod_id');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('amazon_prime_video_id');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('d_anime_store_id');
        });
        Schema::table('animes', function (Blueprint $table) {
            $table->dropColumn('disney_plus_id');
        });
    }
};
