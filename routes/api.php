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

Route::group([

    'middleware' => 'api',

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('get_user_profile', 'AuthController@getUserProfile');
    Route::post('edit_user_profile', 'AuthController@editUserProfile');
    Route::post('me', 'AuthController@me');
    Route::post('facebook_login', 'FacebookController@facebook_login');
    Route::post('twitter_login', 'TwitterController@twitter_login');
    Route::post('uploadbroadcast', 'BroadcastController@uploadBroadcast');
    Route::post('editbroadcast', 'BroadcastController@editBroadcast');
    Route::post('deletebroadcast', 'BroadcastController@deleteBroadcast');
    Route::post('getallbroadcastsforuser', 'BroadcastController@getAllBroadcastsforUser');
});
