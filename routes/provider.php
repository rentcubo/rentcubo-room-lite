<?php 

Route::group(['middleware' => 'web'], function() {

    Route::group(['as' => 'provider.'], function(){

        Route::get('signup', 'ProviderController@signup_form')->name('signup_form');

        Route::post('signup', 'ProviderController@signup')->name('signup');


        Route::get('login', 'Auth\ProviderLoginController@showLoginForm')->name('login');

        Route::post('login', 'Auth\ProviderLoginController@login')->name('login.post');


        Route::get('forgot-password', 'ProviderController@forgot_password')->name('forgot_password');

        Route::post('forgot-password', 'ProviderController@forgot_password_send')->name('send_mail');

    });

    Route::group(['as' => 'provider.', 'middleware' => ['auth:provider']], function() 
    {

        Route::get('logout', 'Auth\ProviderLoginController@logout')->name('logout');

        Route::get('/', 'ProviderController@profile')->name('home');

        Route::get('/profile', 'ProviderController@profile')->name('profile');

        Route::get('/profile/update', 'ProviderController@update_profile')->name('update_profile');

        Route::post('/profile/update', 'ProviderController@update_profile_save')->name('update_profile_save');


        Route::get('change-password', 'ProviderController@change_password')->name('change_password');

        Route::post('change-password', 'ProviderController@change_password_save')->name('change_password_save');

        Route::post('/delete_account', 'ProviderController@delete_account')->name('delete_account');

        Route::get('/provider_account', 'ProviderController@delete_account_form')->name('provider_account');

        // Hosts CRUD operations
       
        Route::get('hosts', 'ProviderController@hosts_index')->name('hosts.index');

        Route::get('hosts/create', 'ProviderController@hosts_create')->name('hosts.create');

        Route::get('hosts/edit', 'ProviderController@hosts_edit')->name('hosts.edit');

        Route::post('hosts/save', 'ProviderController@hosts_save')->name('hosts.save');

        Route::get('hosts/view', 'ProviderController@hosts_view')->name('hosts.view');

        Route::get('hosts/delete', 'ProviderController@hosts_delete')->name('hosts.delete');

        Route::get('hosts/status', 'ProviderController@hosts_status')->name('hosts.status'); 

        // Bookings CRUD operations
       
        Route::get('bookings', 'ProviderController@bookings_index')->name('bookings.index');

        Route::get('bookings/view', 'ProviderController@bookings_view')->name('bookings.view');

        Route::get('bookings/cancel', 'ProviderController@bookings_cancel')->name('bookings.cancel');

    });

});

?>