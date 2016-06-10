<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProfession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professions',function (Blueprint $table) {
            $table->increments('id');
            $table->string('profession');
        });
        Schema::create('profession_user', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('profession_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('profession_id')->references('id')->on('professions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('profession_user');
        Schema::drop('professions');
    }
}
