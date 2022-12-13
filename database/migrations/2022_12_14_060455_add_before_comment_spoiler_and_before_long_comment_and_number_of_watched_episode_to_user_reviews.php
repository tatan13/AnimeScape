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
            $table->text('before_long_comment')->nullable();
            $table->boolean('before_comment_spoiler')->default(0);
            $table->integer('number_of_watched_episode')->nullable();
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
            $table->dropColumn('before_long_comment');
            $table->dropColumn('before_comment_spoiler');
            $table->dropColumn('number_of_watched_episode');
        });
    }
};
