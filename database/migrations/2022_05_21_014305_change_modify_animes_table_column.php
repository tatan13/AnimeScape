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
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('sex');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('sequel');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('company');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('furigana')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->integer('number_of_episode')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->text('summary')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('abema_id')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('unext_id')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('fod_id')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('amazon_prime_video_id')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('d_anime_store_id')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('disney_plus_id')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('company1')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('company2')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('company3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->boolean('sex')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->integer('sequel')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->string('company')->nullable();
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('furigana');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('number_of_episode');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('summary');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('company1');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('company2');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('company3');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('abema_id');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('unext_id');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('fod_id');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('amazon_prime_video_id');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('d_anime_store_id');
        });
        Schema::table('modify_animes', function (Blueprint $table) {
            $table->dropColumn('disney_plus_id');
        });
    }
};
