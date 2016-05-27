<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('civility');
            $table->string('name');
            $table->string('firstname');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('phone');
            $table->integer('discover_method');
            $table->rememberToken();
            $table->timestamps();
            $table->integer('userable_id');
            $table->string('userable_type');
            $table->string('userable_type_readable');
            $table->boolean('enable')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
