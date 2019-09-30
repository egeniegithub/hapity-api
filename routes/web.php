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

Route::get('checkauth',function(){
	dd(auth::user()->id);
});
Route::get('/', 'Web\HomeController@index')->name('home');
Route::get('help','Web\HelpController@index');
Route::get('about','Web\ContactusController@index');
Route::get('privacy-policy','Web\privacyController@privacy_policy');
Route::post('sendmail_contactus','Web\ContactusController@sendmail_contactus');
// facebook routes
Route::post('fbloginUser','Web\FacebookController@facebook_login');


// Auth::routes(['register' => false]);
// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/broadcasts/view/{id}', 'Web\BroadcastsController@view')->name('view_broadcast');

Auth::routes();

Route::get('/home', 'Web\MainController@index')->name('home');
Route::get('webcast','Web\WebcastController@start_web_cast');
Route::get('create-content','Web\WebcastController@create_content');
Route::get('settings','Web\SettingController@settings');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');