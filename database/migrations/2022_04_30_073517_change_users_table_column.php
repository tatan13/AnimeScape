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
            $table->renameColumn('uid', 'name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('onewordcomment', 'one_comment');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('sex')->nullable()->change();
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
            $table->renameColumn('name', 'uid');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('one_comment', 'onewordcomment');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->bool('sex')->nullable()->change();
        });
    }
};
