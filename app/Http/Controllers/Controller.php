<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\User;
use App\Task;
use App\Participant;
use App\Participants_schedule;
use App\Participants_forbidden_task;

class Controller extends BaseController
{

    public function destroy_users_participants_tasks(){

      $users = User::all();

      foreach($users as $user){
        $user->delete();
      }

      $schedules = Participants_schedule::all();
      foreach($schedules as $schedule){
        $schedule->delete();
      }

      $forbiddens = Participants_forbidden_task::all();
      foreach($forbiddens as $forbidden){
        $forbidden->delete();
      }

      $tasks = Task::all();
      foreach($tasks as $task){
        $task->delete();
      }

      $participants = Participant::all();
      foreach($participants as $participant){
        $participant->delete();
      }

    }
}
