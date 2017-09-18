<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsForbiddenTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants_forbidden_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('participant')->unsigned();
            $table->foreign('participant')->references('id')->on('participants');
            $table->integer('task')->unsigned();
            $table->foreign('task')->references('id')->on('tasks');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('participants_forbidden_tasks');
    }
}
