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

Route::get('main/view_broadcast/{id}','Web\BroadcastsController@handle_old_view_broadcast')->name('redirect.old.view.broadcast');
Route::get('view-broadcast/{id}', 'Web\BroadcastsController@view_broadcast')->name('broadcast.view');
Route::get('broadcast/views/update-count/{id}', 'Web\BroadcastsController@update_view_count')->name('broadcast.update.view.count');

Route::group([
    'middleware' => 'user.access',
], function ($router) {
    Route::get('/dashboard', 'Web\DashboardController@index')->name('user.dashboard');

    Route::get('settings', 'Web\SettingController@settings')->name('settings');
    Route::post('user/save_setting', 'Web\SettingController@save_settings')->name('settgins.save');
    Route::get('check_username','Web\SettingController@check_username')->name('check_username');
    Route::get('check_email','Web\SettingController@check_email')->name('check_email');
    Route::get('resetpassword','Web\SettingController@resetpassword')->name('resetpassword');
    Route::post('reset_password','Web\SettingController@reset_password')->name('reset.password');


    Route::get('webcast', 'Web\BroadcastsController@start_web_cast')->name('webcast');
    Route::get('create-content', 'Web\BroadcastsController@create_content')->name('create-content');
    Route::post('create_content_submission', 'Web\BroadcastsController@create_content_submission')->name('create_content_submission');
    Route::post('edit_content_submission', 'Web\BroadcastsController@edit_content_submission')->name('edit_content_submission');
    Route::post('deleteBroadcast', 'Web\BroadcastsController@deleteBroadcast')->name('deleteBroadcast');
    
    Route::post('startwebbroadcast', 'Web\BroadcastsController@startwebbroadcast')->name('startwebbroadcast');
    Route::post('update_timestamp_broadcast', 'Web\BroadcastsController@update_timestamp_broadcast')->name('update_timestamp_broadcast');
    Route::post('offline_broadcast', 'Web\BroadcastsController@offline_broadcast')->name('offline_broadcast');
    Route::get('edit-content/{broadcast_id}', 'Web\BroadcastsController@edit_broadcast_content')->name('edit_broadcast_content');

});

////  admin routes
Route::group([
    'middleware' => 'admin.access',
], function ($router) {

    Route::get('admin/broadcast', 'Web\Admin\AdminBroadcastController@index')->name('admin.broadcast');
    Route::post('admin/deletebroadcast', 'Web\Admin\AdminBroadcastController@deleteBroadcast')->name('admin.deletebroadcast');

    Route::post('admin/deleteuser', 'Web\Admin\UsersController@deleteuser')->name('admin.deleteuser');
    Route::post('admin/approveduser/', 'Web\Admin\UsersController@approveduser')->name('admin.approveduser');

    Route::get('admin/dashboard', 'Web\Admin\AdminController@index')->name('admin.dashboard');
    Route::get('admin/settings', 'Web\Admin\AdminController@adminSetting')->name('admin.settings');
    Route::post('admin/changepassword', 'Web\Admin\AdminController@changePassword')->name('admin.changepassword');

    Route::get('admin/users', 'Web\Admin\UsersController@index')->name('admin.users');

    Route::get('admin/reported-broadcast', 'Web\Admin\ReportedController@reportedBroadcasts')->name('admin.reportedBroadcasts');
    Route::get('admin/reported-users', 'Web\Admin\ReportedController@reportedUsers')->name('admin.reportedUsers');
    Route::post('admin/reportBroadcastDelete', 'Web\Admin\ReportedController@reportBroadcastDelete')->name('admin.reportBroadcastDelete');
    Route::post('admin/reportBroadcastApproved', 'Web\Admin\ReportedController@reportBroadcastApproved')->name('admin.reportBroadcastApproved');

});


Route::get('widget','Web\WidgetController@index')->name('widget.index');

Route::get('/test', 'Web\HomeController@test')->name('test');