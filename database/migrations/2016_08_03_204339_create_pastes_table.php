<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pastes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('link')->unique();
            $table->integer('userId');
            $table->integer('views');
            $table->string('title');
            $table->longText('content');
            $table->string('ip');
            $table->boolean('noSyntax');
            $table->string('expiration');
            $table->string('privacy');
            $table->string('password');
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
        Schema::drop('pastes');
    }
}
