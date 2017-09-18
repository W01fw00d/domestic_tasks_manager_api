<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\User;
use Illuminate\Http\Request; //loads the Request class for retrieving inputs
use Illuminate\Support\Facades\Hash; //load this to use the Hash::make method

class UserController extends BaseController
{
    /*
    public function index(){ //Get all records from table
      return Participant::all();
    }
    */

    public function login(Request $request){ //Get a single record by name
      //return User::find($name);
      $user =
        User::where('name', $request->input('name'))->
        where('password', $request->input('password'))->get();


        //We do not want to send the password back
        $user->password = 'hidden';

        return $user;

    }

    public function store(Request $request){ //Insert new record to table

      $this->validate($request, [
          'name'  => 'required',
      ]);

      $user = new User;
      $user->name = $request->input('name');
      $user->password = $request->input('password');
      $user->admin = $request->input('admin') === 'true'? true: false;
      $user->participant = $request->input('participant');
      $user->save();

      //We do not want to send the password back
      $user->password = 'hidden';

      return $user;

    }

    public function update(Request $request, $id){ //Update a record

      /*
        $this->validate($request, [
          'name'  => 'required',
        ]);
        */
        $user = User::find($id);
        $user->password = $request->input('password');
        $user->save();

        //return $user;
    }

/*
    public function destroy_all(){

      $users = User::all();

      foreach($users as $user){
        $user->delete();
      }
    }
    */

}
