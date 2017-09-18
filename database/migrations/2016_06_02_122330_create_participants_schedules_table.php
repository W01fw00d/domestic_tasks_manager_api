<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants_schedules', function (Blueprint $table) {
          $table->increments('id');
          $table->timestamps();
          $table->integer('participant')->unsigned();
          $table->foreign('participant')->references('id')->on('participants');
          $table->integer('day');
          $table->boolean('morning');
          $table->boolean('afternoon');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('participants_schedules');
    }
}
