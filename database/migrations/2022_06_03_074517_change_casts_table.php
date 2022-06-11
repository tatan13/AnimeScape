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
        Schema::table('casts', function (Blueprint $table) {
            $table->string('blood_type')->nullable();
        });
        Schema::table('casts', function (Blueprint $table) {
            $table->string('birth')->nullable();
        });
        Schema::table('casts', function (Blueprint $table) {
            $table->string('birthplace')->nullable();
        });
        Schema::table('casts', function (Blueprint $table) {
            $table->string('blog_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('casts', function (Blueprint $table) {
            $table->dropColumn('blood_type');
        });
        Schema::table('casts', function (Blueprint $table) {
            $table->dropColumn('birth');
        });
        Schema::table('casts', function (Blueprint $table) {
            $table->dropColumn('birthplace');
        });
        Schema::table('casts', function (Blueprint $table) {
            $table->dropColumn('blog_url');
        });
    }
};
