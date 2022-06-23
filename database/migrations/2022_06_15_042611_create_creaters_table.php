<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('creaters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('furigana')->nullable();
            $table->integer('sex')->nullable();
            $table->string('url')->nullable();
            $table->string('twitter')->nullable();
            $table->string('blog')->nullable();
            $table->string('blog_url')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('birth')->nullable();
            $table->string('birthplace')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('creaters');
    }
};
