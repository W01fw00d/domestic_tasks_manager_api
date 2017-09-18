<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->post('tasks/create',      'TaskController@store');
$app->get('tasks/read',        'TaskController@index');
//$app->get('tasks/read/{id}',    'TaskController@show');
$app->post('tasks/edit/{id}', 'TaskController@update');
//$app->post('tasks/delete/{id}', 'TaskController@destroy');
$app->post('tasks/delete-all', 'TaskController@destroy_all');

$app->post('participants/create',      'ParticipantController@store');
$app->get('participants/read',        'ParticipantController@get_all');
$app->post('participants/edit/{id}', 'ParticipantController@update');
$app->post('/delete-users-participants-tasks', 'Controller@destroy_users_participants_tasks');

$app->post('users/create',      'UserController@store');
$app->post('users/edit/{id}', 'UserController@update');
$app->post('users/login', 'UserController@login');

  //Eliminar las tablas en sí, destruirlas:
/*
  Schema::drop('participants_schedule');
  Schema::drop('participants_forbidden_tasks');
  Schema::drop('participants');
  Schema::drop('tasks');
*/

$app->get('/drop-tables', function () use ($app) {

  //Schema::drop('users');
  Schema::drop('participants_schedule');
});


/* Tasks */
/*
$app->get('/tasks', function () use ($app) {

  $tasks = DB::table('tasks')->get();

    return $tasks;

});

$app->put('task/{name, frequency, difficulty}', function () use ($app) {

  DB::table('tasks')->insert(
      ['name' => $name, 'frequency' => $frequency, 'difficulty' => $difficulty]
  );

});
*/


        //return view('user.index', ['users' => $users]);
