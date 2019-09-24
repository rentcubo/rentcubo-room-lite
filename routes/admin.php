<?php

Route::group(['middleware' => 'web'], function() {

    Route::group(['as' => 'admin.', 'prefix' => 'admin'], function() {
        
        Route::get('/clear-cache', function() {

            $exitCode = Artisan::call('config:cache');

            return back();

        })->name('clear-cache');

        Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('login');

        Route::post('login', 'Auth\AdminLoginController@login')->name('login.post');

        Route::get('logout', 'Auth\AdminLoginController@logout')->name('logout');

        Route::get('/profile', 'AdminController@profile')->name('profile');

        Route::post('/profile/save', 'AdminController@profile_save')->name('profile.save');

        Route::post('/change/password', 'AdminController@change_password')->name('change.password');

        Route::get('/', 'AdminController@index')->name('dashboard');

        /***
         *
         * Admin Account releated routes
         *
         */
        // Users CRUD Operations
       
        Route::get('/users/index', 'AdminController@users_index')->name('users.index');

        Route::get('/users/create', 'AdminController@users_create')->name('users.create');

        Route::get('/users/edit', 'AdminController@users_edit')->name('users.edit');

        Route::post('/users/save', 'AdminController@users_save')->name('users.save');

        Route::get('/users/view', 'AdminController@users_view')->name('users.view');

        Route::get('/users/delete', 'AdminController@users_delete')->name('users.delete');

        Route::get('/users/status', 'AdminController@users_status')->name('users.status');


        // Providers CRUD operations

        Route::get('/providers/index', 'AdminController@providers_index')->name('providers.index');

        Route::get('/providers/create', 'AdminController@providers_create')->name('providers.create');

        Route::get('/providers/edit', 'AdminController@providers_edit')->name('providers.edit');

        Route::post('/providers/save', 'AdminController@providers_save')->name('providers.save');

        Route::get('/providers/view/', 'AdminController@providers_view')->name('providers.view');

        Route::get('/providers/delete', 'AdminController@providers_delete')->name('providers.delete');

        Route::get('/providers/status', 'AdminController@providers_status')->name('providers.status');

        // categories CRUD operations

        Route::get('/categories/index', 'AdminController@categories_index')->name('categories.index');

        Route::get('/categories/create', 'AdminController@categories_create')->name('categories.create');

        Route::get('/categories/edit', 'AdminController@categories_edit')->name('categories.edit');

        Route::post('/categories/save', 'AdminController@categories_save')->name('categories.save');

        Route::get('/categories/view/', 'AdminController@categories_view')->name('categories.view');

        Route::get('/categories/delete/', 'AdminController@categories_delete')->name('categories.delete');

        Route::get('/categories/status/', 'AdminController@categories_status')->name('categories.status');   

        // Used for Ajax functions

        Route::post('/categories/get_sub_categories', 'ApplicationController@get_sub_categories')->name('get_sub_categories');


        // Sub Categories CRUD operations
       
        Route::get('/sub_categories/index', 'AdminController@sub_categories_index')->name('sub_categories.index');

        Route::get('/sub_categories/create', 'AdminController@sub_categories_create')->name('sub_categories.create');

        Route::get('/sub_categories/edit/', 'AdminController@sub_categories_edit')->name('sub_categories.edit');

        Route::post('/sub_categories/save', 'AdminController@sub_categories_save')->name('sub_categories.save');

        Route::get('/sub_categories/view/', 'AdminController@sub_categories_view')->name('sub_categories.view');

        Route::get('/sub_categories/delete/', 'AdminController@sub_categories_delete')->name('sub_categories.delete');

        Route::get('/sub_categories/status/', 'AdminController@sub_categories_status')->name('sub_categories.status'); 



        // Hosts CRUD operations
       
        Route::get('/hosts/index', 'AdminController@hosts_index')->name('hosts.index');

        Route::get('/hosts/create', 'AdminController@hosts_create')->name('hosts.create');

        Route::get('/hosts/edit', 'AdminController@hosts_edit')->name('hosts.edit');

        Route::post('/hosts/save', 'AdminController@hosts_save')->name('hosts.save');

        Route::get('/hosts/view', 'AdminController@hosts_view')->name('hosts.view');

        Route::get('/hosts/delete', 'AdminController@hosts_delete')->name('hosts.delete');

        Route::get('/hosts/status', 'AdminController@hosts_status')->name('hosts.status'); 

        Route::get('/hosts/verification', 'AdminController@hosts_verification_status')->name('hosts.verification_status');


        // Bookings CRUD operations

        Route::get('/bookings/dashboard', 'AdminController@bookings_dashboard')->name('bookings.dashboard');

        Route::get('/bookings/index', 'AdminController@bookings_index')->name('bookings.index');

        Route::post('/bookings/status/{id}', 'AdminController@bookings_status')->name('bookings.status'); 
        
        Route::get('/bookings/view', 'AdminController@bookings_view')->name('bookings.view');

        // Revenues
       
        Route::get('/revenues/dashboard', 'AdminController@revenues_dashboard')->name('revenues.dashboard');

        Route::get('/bookings/payments', 'AdminController@bookings_payments')->name('bookings.payments');

        // Reviews

        Route::get('/reviews/users','AdminController@reviews_users')->name('reviews.users');

        Route::get('/reviews/users/view', 'AdminController@reviews_users_view')->name('reviews.users.view');


        // settings

        Route::get('/settings', 'AdminController@settings')->name('settings'); 
     
        Route::get('/admin-control', 'AdminController@admin_control')->name('control'); 
     
        Route::post('/settings/save', 'AdminController@settings_save')->name('settings.save'); 

        Route::post('/env_settings','AdminController@env_settings_save')->name('env-settings.save');

        // STATIC PAGES

        Route::get('/static_pages' , 'AdminController@static_pages_index')->name('static_pages.index');

        Route::get('/static_pages/create', 'AdminController@static_pages_create')->name('static_pages.create');

        Route::get('/static_pages/edit', 'AdminController@static_pages_edit')->name('static_pages.edit');

        Route::post('/static_pages/save', 'AdminController@static_pages_save')->name('static_pages.save');

        Route::get('/static_pages/delete', 'AdminController@static_pages_delete')->name('static_pages.delete');

        Route::get('/static_pages/view', 'AdminController@static_pages_view')->name('static_pages.view');

        Route::get('/static_pages/status', 'AdminController@static_pages_status_change')->name('static_pages.status');


        Route::get('/help','AdminController@help')->name('help');


    });

});