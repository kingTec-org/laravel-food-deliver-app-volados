<?php

/*
|--------------------------------------------------------------------------
| Driver Routes
|--------------------------------------------------------------------------
|
| Here is where you can register driver routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "driver" middleware group. Now create something great!
|
 */
Route::group(['middleware' => ['guest:driver','clear_cache', 'locale']], function () {

	Route::match(array('GET', 'POST'), '/', 'DriverController@signup')->name('home');

	Route::match(array('GET', 'POST'), '/signup', 'DriverController@signup')->name('signup');

	Route::match(array('GET', 'POST'), '/login', 'DriverController@login')->name('login');

	Route::match(array('GET', 'POST'), '/password', 'DriverController@password')->name('password');

	Route::get('forgot_password', 'DriverController@forgot_password')->name('forgot_password');

	Route::match(array('GET', 'POST'), '/otp_confirm', 'DriverController@otp_confirm')->name('otp_confirm');

	Route::match(array('GET', 'POST'), '/reset_password', 'DriverController@reset_password')->name('reset_password');

	Route::match(array('GET', 'POST'), '/password_change', 'DriverController@password_change')->name('password_change');

});

Route::group(['middleware' => ['auth:driver','clear_cache', 'locale']], function () {

	Route::get('logout', 'DriverController@logout')->name('logout');

	Route::match(array('GET', 'POST'), '/profile', 'DriverController@profile')->name('profile');

	Route::match(array('GET', 'POST'), '/documents/{id}', 'DriverController@documents')->name('documents');

	Route::match(array('GET', 'POST'), '/vehicle_details', 'DriverController@vehicle_details')->name('vehicle_details');

	Route::match(array('GET', 'POST'), '/invoice', 'DriverController@invoice')->name('invoice');

	Route::post('invoice_filter', 'DriverController@invoice_filter')->name('invoice_filter');

	Route::match(array('GET', 'POST'), '/payment', 'DriverController@payment')->name('payment');

	Route::match(array('GET', 'POST'), '/daily_payment/{date}', 'DriverController@daily_statement')->name('daily_payment');

	Route::match(array('GET', 'POST'), '/detail_payment/{date}', 'DriverController@detail_payment')->name('detail_payment');

	Route::match(array('GET', 'POST'), '/particular_order', 'DriverController@particular_order')->name('particular_order');

	Route::match(array('GET', 'POST'), '/trips', 'DriverController@trips')->name('trips');

	Route::match(array('GET', 'POST'), '/profile_upload', 'DriverController@profile_upload')->name('profile_upload');

});

Route::get('login_session', function () {
	return view('driver/login_session');
});

Route::get('trip_detail', function () {
	return view('driver/trip_detail');
});
