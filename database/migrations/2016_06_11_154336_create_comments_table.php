<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->string('slug', 100);
            $table->boolean('is_public')->default(0);
            $table->char('status', 1)->default('P'); // P (Posé) - T (En traitement) - R (Résolu) - A (Accepté par l'utilisateur) - N (Non accepté) - B (Black listé)
            $table->string('tag', 50); // Catégorie de conversations (type de médecine ...)
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->timestamp('date');
            $table->integer('user_id')->unsigned();
            $table->integer('conversation_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('conversation_id')->references('id')->on('conversations')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('comments_opinions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mark');
            $table->integer('comment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('comment_id')->references('id')->on('comments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('conversation');
        Schema::drop('comment');
        Schema::drop('comments_opinions');
    }
}
