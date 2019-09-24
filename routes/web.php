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

Auth::routes();

Route::get('/', 'UserController@home')->name('user.home');

Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );

Route::get('pages' , 'ApplicationController@static_pages')->name('static_pages.view');

Route::group(['as' => 'user.'], function() {

    Route::get('/page/{page_type}', 'ApplicationController@static_pages')->name('static_pages');

    Route::get('/profile', 'UserController@profile')->name('profile');


    Route::get('/profile/update', 'UserController@update_profile')->name('update_profile');

    Route::post('/profile/update', 'UserController@update_profile_save')->name('update_profile_save');

    Route::get('/change-password', 'UserController@change_password')->name('change_password');

    Route::post('/change-password', 'UserController@change_password_save')->name('change_password_save');


    Route::get('/delete-account', 'UserController@delete_account')->name('delete_account');

    Route::post('/delete-account', 'UserController@delete_account_update')->name('delete_account_update');


    Route::get('/forgot-password', 'UserController@forgot_password')->name('forgot_password');

    Route::post('/forgot-password', 'UserController@forgot_password_send')->name('send_mail');


    Route::get('/signup', 'UserController@signup')->name('sign_up');

    Route::post('/signup', 'UserController@signup_save')->name('signup_save');


    Route::get('/hosts/index', 'UserController@hosts_index')->name('hosts.index');

    Route::get('/hosts/view', 'UserController@hosts_view')->name('hosts.view');
    

    Route::get('/bookings/index', 'UserController@bookings_index')->name('bookings.index');

    Route::get('/bookings/view', 'UserController@bookings_view')->name('bookings.view');

    Route::post('/bookings/save', 'UserController@bookings_create')->name('bookings.save');

    Route::get('/bookings/cancel', 'UserController@bookings_cancel')->name('bookings.cancel');

    Route::get('/bookings/checkin', 'UserController@bookings_checkin')->name('bookings.checkin');

    Route::get('/bookings/checkout', 'UserController@bookings_checkout')->name('bookings.checkout');

    Route::post('/bookings/review', 'UserController@bookings_review')->name('bookings.review');

});
