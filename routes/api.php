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

    Route::post('{platform}/login', 'SocialLoginController@social_login')->name('social.login');



    Route::post('refresh', 'AuthController@refresh');
    Route::post('get_profile_info', 'AuthController@getUserProfile');
    Route::post('edit_profile', 'AuthController@editUserProfile');
    Route::post('me', 'AuthController@me');
    
    Route::post('broadcasts/upload', 'BroadcastsController@upload');
    Route::post('broadcasts/start', 'BroadcastsController@start');    
    Route::post('broadcasts/edit', 'BroadcastsController@edit');
    
    Route::post('broadcasts/delete', 'BroadcastsController@delete');
    Route::post('broadcasts/update/timestamp', 'BroadcastsController@update_timestamp');

    Route::post('getallbroadcastsforuser', 'BroadcastsController@getAllBroadcastsforUser');
    
});
