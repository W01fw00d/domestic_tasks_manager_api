<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Task; //loads the Task model
use Illuminate\Http\Request; //loads the Request class for retrieving inputs
use Illuminate\Support\Facades\Hash; //load this to use the Hash::make method

class TaskController extends BaseController
{
    public function index(){ //Get all records from table
      return Task::all();
    }

    /*
    public function show($id){ //Get a single record by ID
      return Task::find($id);
    }
    */

    public function store(Request $request){ //Insert new record to table
      $this->validate($request, [
          'name'  => 'required',
          'frequency' => 'required',
          'difficulty'  => 'required',
      ]);

      $task   = new Task;
      $task->name = $request->input('name');
      $task->frequency = $request->input('frequency');
      $task->difficulty = $request->input('difficulty');
      $task->save();

      return $task;
    }

    public function update(Request $request, $id){ //Update a record
        $this->validate($request, [
          'name'  => 'required',
          'frequency' => 'required',
          'difficulty'  => 'required',
        ]);

        $task = Task::find($id);
        $task->name = $request->input('name');
        $task->frequency = $request->input('frequency');
        $task->difficulty = $request->input('difficulty');
        $task->save();

        return $task;
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


      $tasks = Task::all();

      foreach($tasks as $task){
        $task->delete();
      }

    }


}
