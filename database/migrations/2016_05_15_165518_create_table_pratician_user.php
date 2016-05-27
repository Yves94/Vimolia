<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTablePraticianUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pratician_users',function (Blueprint $table) {
            $table->increments('id');
            $table->integer('profession_id');
            $table->string('siret');
            $table->string('degree');
            $table->boolean('is_prenium');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pratician_users');
    }
}
