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
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->dateTime('token_limit')->nullable();
            $table->string('unique_id')->after('id')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->change();
            $table->dropColumn('access_token');
            $table->dropColumn('refresh_token');
            $table->dropColumn('token_limit');
            $table->dropColumn('unique_id');
        });
    }
};
