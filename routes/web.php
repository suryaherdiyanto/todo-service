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

    $api->group(['namespace' => 'App\Http\Controllers\Api\V1'], function($api) {

        $api->group(['prefix' => 'user'], function($api) {

            $api->post('auth', 'AuthController@login');
            $api->post('logout', 'AuthController@logout');
            $api->post('register', 'UserController@register');
            
            $api->get('me', 'AuthController@me');
        });

        $api->group(['prefix' => 'tasks'], function($api) {
            $api->get('/', 'TaskController@index');
            $api->get('/{id}', 'TaskController@show');
        });

    });

});
