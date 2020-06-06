<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['namespace' => 'App\Api\V1\Controllers', 'middleware' => 'bindings'], function ($api) {

    //
    // Public APIs
    //
    $api->get('/', function () {
        return ['message' => 'Welcome to Social Network Portal: API Version 1'];
    });
    $api->post('/register', 'AuthController@register');
    $api->post('/login', 'AuthController@login');
    $api->post('/refresh', 'AuthController@refresh');




    // Private APIs
    $api->group(['middleware' => 'api.auth'], function ($api){

        $api->group(['prefix' => 'auth'], function ($api) {
            $api->post('logout', 'AuthController@logout');
            $api->post('me', 'AuthController@me');
        });

        $api->group(['prefix' => 'user'], function ($api){
            $api->get('/', 'UserController@index');
            $api->post('/sent-request', 'UserController@sentFriendRequest');
            $api->post('/approve-reject-request', 'UserController@approveRejectFriendRequest');
            $api->get('/mutual-friends/{user}', 'UserController@getMutualFriends');
        });
    });
    
    
});
