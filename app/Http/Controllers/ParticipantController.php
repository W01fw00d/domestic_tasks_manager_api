<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Participant;
use App\Participants_schedule;
use App\Participants_forbidden_task;
use App\Task;
use Illuminate\Http\Request; //loads the Request class for retrieving inputs
use Illuminate\Support\Facades\Hash; //load this to use the Hash::make method

class ParticipantController extends BaseController
{
    public function index(){ //Get all records from table
      return Participant::all();
    }

    public function store(Request $request){ //Insert new record to table
      $this->validate($request, [
          'name'  => 'required',
      ]);

      $participant   = new Participant;
      $participant->name = $request->input('name');
      $participant->save();

      $schedule_array = $request->input('available_schedule');

      // Available schedule for this participant
      $i = 0;
      foreach ($schedule_array as $schedule_data){

        $schedule = new Participants_schedule;
        $schedule->participant = $participant->id;
        $schedule->day = $i;
        //For some reason the database is not understanding the booleans, so we 'parse' them
        $schedule->morning = $request->input('available_schedule.'.$i.'.morning') === 'true'? true: false;
        $schedule->afternoon = $request->input('available_schedule.'.$i.'.afternoon') === 'true'? true: false;
        $schedule->save();

        $i++;
      }

      $tasks_array = $request->input('forbidden_tasks');

      // forbidden_tasks for this participant

      if ($tasks_array){

        $i = 0;
        foreach ($tasks_array as $task_aux){

          $task = new Participants_forbidden_task;
          $task->participant = $participant->id;
          $task->task = $request->input('forbidden_tasks.'.$i.'.id');
          $task->save();

          $i++;
        }
      }

      $result = $this->get_participant($participant->id);

      return $result;
    }

    public function update(Request $request, $id){ //Update a record
        $this->validate($request, [
          'name'  => 'required',
        ]);

        $participant = Participant::find($id);
        $participant->name = $request->input('name');
        $participant->save();

        $db_schedule = Participants_schedule::where('participant', $id)->get();

        //Translate de string to boolean and update
        for ($i = 0; $i < count($db_schedule); $i++){

          $db_schedule[$i]->morning = $request->input('available_schedule.'.$i.'.morning') === 'true'? true: false;
          $db_schedule[$i]->afternoon = $request->input('available_schedule.'.$i.'.afternoon') === 'true'? true: false;
          $db_schedule[$i]->save();
        }

        $participant->available_schedule = $db_schedule;


        $db_tasks = Participants_forbidden_task::where('participant', $id)->get();

        $new_tasks = $request->input('forbidden_tasks');

        //Check if the task is not forbidden anymore, then delete from DB
        for ($i = 0; $i < count($db_tasks); $i++){

          $found = false;
          for ($j = 0;  (($j < count($new_tasks)) && !$found); $j++){

            if ($new_tasks[$j]['id'] === $db_tasks[$i]->id){

              $found = true;

            }

          }

          if (!$found){
            $db_tasks[$i]->delete();

          }

          //$db_tasks[$i]->save();

        }

        $db_tasks = Participants_forbidden_task::where('participant', $id)->get();

        //Check if there is a new forbidden task, then add to DB
        for ($i = 0; $i < count($new_tasks); $i++){

          $found = false;
          for ($j = 0; ( ($j < count($db_tasks) ) && !$found); $j++){


            if ($new_tasks[$i]['id'] === $db_tasks[$j]->id){

              $found = true;

            }


          }

          if (!$found){

            $task = new Participants_forbidden_task;
            $task->participant = $participant->id;
            $task->task = $new_tasks[$i]['id'];
            $task->save();

          }

        }


        $forbidden_tasks = Participants_forbidden_task::where('participant', $id)->get();
        $participant->forbidden_tasks = $this->forbidden_tasks_addapt($forbidden_tasks);

        //$participant->forbidden_tasks = $db_tasks;

        return $participant;
    }

    /*
    public function destroy(Request $request){
        $this->validate($request, [
            'id' => 'required|exists:tasks'
        ]);
        $task = Task::find($request->input('id'));
        $task->delete();
    }
    */

    public function destroy_all(){

      //Task::all()->delete();

      //DB::table('users')->delete();


      $participants = Participant::all();

      foreach($participants as $participant){
        $participant->delete();
      }

    }

    public function get_participant($id){

      $participant = Participant::find($id);
      $participant->available_schedule = Participants_schedule::where('participant', $id)->get();

      //Translate de tinyint to boolean
      foreach ($participant->available_schedule as $schedule){
         $schedule->morning = $schedule->morning === 1? true: false;
         $schedule->afternoon = $schedule->afternoon === 1? true: false;
      }

      //$participant->forbidden_tasks = Participants_forbidden_task::where('participant', $id)->get();

      $forbidden_tasks = Participants_forbidden_task::where('participant', $id)->get();
      $participant->forbidden_tasks = $this->forbidden_tasks_addapt($forbidden_tasks);

      return $participant;

    }

    public function get_all(){

      $participants = Participant::all();

      foreach($participants as $participant){

        $id = $participant->id;
        $participant->available_schedule = Participants_schedule::where('participant', $id)->get();

        //Translate de tinyint to boolean
        foreach ($participant->available_schedule as $schedule){
           $schedule->morning = $schedule->morning === 1? true: false;
           $schedule->afternoon = $schedule->afternoon === 1? true: false;
        }

        $forbidden_tasks = Participants_forbidden_task::where('participant', $id)->get();
        $participant->forbidden_tasks = $this->forbidden_tasks_addapt($forbidden_tasks);

      }

      return $participants;
    }

    //We addapt the forbidden_tasks array for being used in the App
    public function forbidden_tasks_addapt($forbidden_tasks){

      foreach ($forbidden_tasks as $forbidden_task){
        $forbidden_task->id = $forbidden_task->task;
        $task = Task::find($forbidden_task->id);
        $forbidden_task->name = $task->name;
        $forbidden_task->frequency = $task->frequency;
        $forbidden_task->difficulty = $task->difficulty;
      }
      return $forbidden_tasks;
    }

}
