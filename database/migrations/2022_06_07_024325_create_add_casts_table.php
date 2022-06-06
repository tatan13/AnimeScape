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
        Schema::create('add_casts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('furigana')->nullable();
            $table->integer('sex')->nullable();
            $table->string('office')->nullable();
            $table->string('url')->nullable();
            $table->string('twitter')->nullable();
            $table->string('blog')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('birth')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('blog_url')->nullable();
            $table->integer('cast_id')->unsigned()->nullable();
            $table->boolean('delete_flag')->default(0);
            $table->string('remark', 400)->nullable();
            $table->timestamps();

            if (DB::getDriverName()!== 'sqlite') {
                $table->foreign('cast_id')->references('id')->on('casts')->onUpdate('CASCADE')->onDelete('CASCADE');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_casts');
    }
};
