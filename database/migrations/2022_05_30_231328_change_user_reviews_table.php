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
            $table->boolean('now_watch')->default(0);
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->timestamp('watch_timestamp')->nullable();
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->timestamp('comment_timestamp')->nullable();
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
            $table->dropColumn('now_watch');
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->dropColumn('watch_timestamp');
        });
        Schema::table('user_reviews', function (Blueprint $table) {
            $table->dropColumn('comment_timestamp');
        });
    }
};