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

    Route::post('login', 'AuthController@login')->name('api.login');
    Route::post('register', 'AuthController@register')->name('api.register');
    Route::post('logout', 'AuthController@logout')->name('api.logout');

    Route::post('{platform}/login', 'SocialLoginController@social_login')->name('social.login');

    Route::post('refresh', 'AuthController@refresh')->name('api.jwt.refresh');
    Route::post('get_profile_info', 'AuthController@getUserProfile')->name('api.getuserprofile');
    Route::post('edit_profile', 'AuthController@editUserProfile')->name('api.edituserprofile');
    Route::post('me', 'AuthController@me')->name('api.me');

    Route::get('validate_key/',['middleware'=>'cors','uses'=>'AuthController@validate_key'])->name('validate_key');
    
    Route::post('broadcasts/upload', 'BroadcastsController@upload')->name('api.broadcast.upload');
    Route::post('broadcasts/start', 'BroadcastsController@start')->name('api.broadcast.start');    
    Route::post('broadcasts/edit', 'BroadcastsController@edit')->name('api.broadcast.edit');
    
    Route::post('broadcasts/delete', 'BroadcastsController@delete')->name('api.broadcast.delete');
    Route::post('broadcasts/update/timestamp', 'BroadcastsController@update_timestamp')->name('api.broadcast.timestamp');
    Route::post('broadcasts/stop', 'BroadcastsController@stop_broadcast')->name('api.broadcast.stop');

    Route::post('broadcasts/all', 'BroadcastsController@all_user_broadcasts')->name('api.broadcast.all');
    
    Route::get('broadcasts/download', 'BroadcastsController@download')->name('api.broadcast.download');
});
