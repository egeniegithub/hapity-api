<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'Web\HomeController@index')->name('home');
Route::get('help', 'Web\HelpController@index');

Route::get('privacy-policy', 'Web\PrivacyController@index');

Route::get('about', 'Web\ContactusController@index');
Route::post('sendmail_contactus', 'Web\ContactusController@sendmail_contactus');

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('auth.social');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('auth.social.callback');

Route::get('/home', 'Web\MainController@index')->name('user_home');

Route::get('settings', 'Web\SettingController@settings');
Route::get('is_user_username{username}/{user_id}', 'Web\SettingController@is_user_username');
Route::post('user/save_setting', 'Web\SettingController@save_settings');

Route::get('webcast', 'Web\WebcastController@start_web_cast');
Route::get('create-content', 'Web\WebcastController@create_content');

Route::post('create_content_submission', 'Web\CreatecontentController@create_content_submission');
Route::post('edit_content_submission', 'Web\CreatecontentController@edit_content_submission');
Route::post('deleteBroadcast', 'Web\CreatecontentController@deleteBroadcast');
Route::get('view-broadcast/{id}', 'Web\CreatecontentController@view_broadcast');

Route::post('startwebbroadcast', 'Web\BroadcastsController@startwebbroadcast');
Route::post('update_timestamp_broadcast', 'Web\BroadcastsController@update_timestamp_broadcast');
Route::post('offline_broadcast', 'Web\BroadcastsController@offline_broadcast');
Route::get('edit-content/{broadcast_id}', 'Web\BroadcastsController@edit_broadcast_content');

Route::get('/broadcasts/view/{id}', 'Web\BroadcastsController@view')->name('view_broadcast');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes();
