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
Route::get('help', 'Web\HelpController@index')->name('help');
Route::get('privacy-policy', 'Web\PrivacyController@index')->name('privacy-policy');
Route::get('about', 'Web\ContactusController@index')->name('about');
Route::post('sendmail_contactus', 'Web\ContactusController@sendmail_contactus')->name('contact.us.send.email');

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('auth.social');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('auth.social.callback');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes();

Route::group([
    'middleware' => 'user.access',
], function ($router) {
    Route::get('/dasboard', 'Web\DashboardController@index')->name('user.dashboard');

    Route::get('settings', 'Web\SettingController@settings')->name('settings');
    Route::post('user/save_setting', 'Web\SettingController@save_settings')->name('settgins.save');

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
});

////  admin routes
Route::group([
    'middleware' => 'admin.access',
], function ($router) {

    Route::get('admin/broadcast', 'Web\Admin\AdminBroadcastController@index');
    Route::get('deletebroadcast/{id}', 'Web\Admin\AdminBroadcastController@deleteBroadcast');
    Route::get('approvedbroadcast/{id}', 'Web\Admin\AdminBroadcastController@approvedbroadcast');

    Route::get('admin/users', 'Web\Admin\UsersController@index');
    Route::get('admin/deleteuser/{id}', 'Web\Admin\UsersController@deleteuser');
    Route::get('admin/approveduser/{id}', 'Web\Admin\UsersController@approveduser');

    Route::get('admin/dashboard', 'Web\Admin\AdminController@index')->name('admin.dashboard');
    Route::get('admin/settings', 'Web\Admin\AdminController@adminSetting');
    Route::post('admin/changepassword', 'Web\Admin\AdminController@changePassword');

    Route::get('admin/users', 'Web\Admin\UsersController@index');

    Route::get('admin/reported-broadcast', 'Web\Admin\ReportedController@reportedBroadcasts');
    Route::get('admin/reported-users', 'Web\Admin\ReportedController@reportedUsers');
});
