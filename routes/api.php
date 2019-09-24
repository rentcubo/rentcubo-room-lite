<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('pages/list' , 'ApplicationController@static_pages_api');


Route::any('categories' , 'ApplicationController@categories');

Route::any('sub_categories' , 'ApplicationController@sub_categories');


// User api's

Route::group(['prefix' => 'user' , 'middleware' => 'cors'], function() {

    Route::any('categories' , 'ApplicationController@categories');

    Route::post('home' , 'UserApiController@home');

	/***
	 *
	 * User Account releated routs
	 *
	 */

    Route::post('/register','UserApiController@register');
    
    Route::post('/login','UserApiController@login');

    Route::post('/forgot_password', 'UserApiController@forgot_password');

    Route::group(['middleware' => 'UserApiVal'] , function() {

        Route::post('/profile','UserApiController@profile'); // 1

        Route::post('/update_profile', 'UserApiController@update_profile'); // 2

        Route::post('/change_password', 'UserApiController@change_password'); // 3

        Route::post('/delete_account', 'UserApiController@delete_account'); // 4

        Route::post('/logout', 'UserApiController@logout'); // 7

        // Reviews 

        Route::post('reviews_for_you', 'UserApiController@reviews_for_you');

        Route::post('reviews_for_providers', 'UserApiController@reviews_for_providers');


    });

    Route::post('/project/configurations', 'UserApiController@configurations'); 

    Route::get('pages/list' , 'ApplicationController@static_pages_api');

    // Core api's 

    Route::post('hosts_view' , 'UserApiController@hosts_view');

    Route::post('hosts_availability' , 'UserApiController@hosts_availability');

    Route::post('reviews' , 'UserApiController@reviews_index');

    // Price calculator
    
    Route::group(['middleware' => 'UserApiVal'] , function() {

        // Pre bookings routes

        Route::post('bookings_create' , 'UserApiController@bookings_create');

        // Post bookings

        Route::post('bookings_view' , 'UserApiController@bookings_view');

        Route::post('bookings_cancel' , 'UserApiController@bookings_cancel');

        Route::post('bookings_rating_report' , 'UserApiController@bookings_rating_report');

    });

});


// Provider api's

Route::group(['prefix' => 'provider' , 'middleware' => 'cors'], function() {

    Route::any('categories' , 'ApplicationController@categories');

    /***
     *
     * Provider Account releated routs
     *
     */

    Route::post('/register','ProviderApiController@register');
    
    Route::post('/login','ProviderApiController@login');

    Route::post('/forgot_password', 'ProviderApiController@forgot_password');

    Route::group(['middleware' => 'ProviderApiVal'] , function() {
        
        Route::post('/profile','ProviderApiController@profile'); // 1

        Route::post('/update_profile', 'ProviderApiController@update_profile'); // 2

        Route::post('/change_password', 'ProviderApiController@change_password'); // 3

        Route::post('/delete_account', 'ProviderApiController@delete_account'); // 4

        Route::post('/logout', 'ProviderApiController@logout'); // 7

        // Reviews 

        Route::post('reviews_for_you', 'ProviderApiController@reviews_for_you');

        Route::post('reviews_for_users', 'ProviderApiController@reviews_for_users');

        // Hosts lists

        Route::any('sub_categories' , 'ProviderApiController@sub_categories');

        
        Route::post('hosts_index' , 'ProviderApiController@hosts_index');
        
        Route::post('hosts_save' , 'ProviderApiController@hosts_save');

        Route::post('hosts_upload_files' , 'ProviderApiController@hosts_upload_files');

        Route::post('hosts_remove_files' , 'ProviderApiController@hosts_remove_files');

        Route::post('hosts_status' , 'ProviderApiController@hosts_status');
        
        Route::post('hosts_delete' , 'ProviderApiController@hosts_delete');

        Route::post('hosts_view' , 'ProviderApiController@hosts_view');

        // Post bookings

        Route::post('bookings_view' , 'ProviderApiController@bookings_view');
        
        Route::post('bookings_cancel' , 'ProviderApiController@bookings_cancel');

        Route::post('bookings_rating_report' , 'ProviderApiController@bookings_rating_report');

    });

    Route::post('/project/configurations', 'ProviderApiController@configurations'); 

    Route::get('pages/list' , 'ApplicationController@static_pages_api');

});
