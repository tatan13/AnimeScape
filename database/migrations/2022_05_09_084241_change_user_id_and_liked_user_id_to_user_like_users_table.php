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
        Schema::table('user_like_users', function (Blueprint $table) {
            if (DB::getDriverName()!== 'sqlite') {
                $table->dropForeign('user_like_users_user_id_foreign');
                $table->dropForeign('user_like_users_liked_user_id_foreign');
            }
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('liked_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_like_users', function (Blueprint $table) {
            $table->dropForeign('user_like_users_user_id_foreign');
            $table->dropForeign('user_like_users_liked_user_id_foreign');
        });
    }
};
