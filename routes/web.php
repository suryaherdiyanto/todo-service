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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api) {

    $api->group(['namespace' => 'App\Http\Controllers\Api\V1', 'middleware' => 'jwt.auth'], function($api) {

        $api->group(['prefix' => 'user'], function($api) {

            $api->post('auth', 'AuthController@login');
            $api->post('logout', 'AuthController@logout');
            $api->post('register', 'UserController@register');
            $api->put('verified/{user_id}', 'UserController@verifiedUser');
            
            $api->get('me', 'AuthController@me');
        });

        $api->group(['prefix' => 'tasks'], function($api) {
            $api->get('/', 'TaskController@index');
            $api->post('/', 'TaskController@store');
            $api->put('/{id}/update', 'TaskController@update');
            $api->delete('/{id}/delete', 'TaskController@delete');
            $api->get('/{id}', 'TaskController@show');

            $api->get('/{task_id}/subtasks', 'SubTaskController@index');
            $api->put('/subtasks/{id}/update', 'SubTaskController@update');
            $api->delete('/subtasks/{id}/delete', 'SubTaskController@delete');
        });

    });

});
