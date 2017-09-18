<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants_schedule', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('participant')->unsigned();
            $table->foreign('participant')->references('id')->on('participants');
            $table->int('day');
            $table->int('time');
            $table->boolean('available');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('participants_schedule');
    }
}
