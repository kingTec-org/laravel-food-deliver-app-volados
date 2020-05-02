<?php

/*
|--------------------------------------------------------------------------
| Store Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::group(['middleware' => ['guest:store', 'clear_cache','locale']], function () {

	Route::match(array('GET', 'POST'), 'signup', 'StoreController@signup')->name('signup');
	Route::match(array('GET', 'POST'), 'login', 'StoreController@login')->name('login');
	Route::match(array('GET', 'POST'), '/', 'StoreController@signup')->name('signup');
	Route::get('thanks', 'StoreController@thanks')->name('thanks');
	Route::match(array('GET', 'POST'), 'password', 'StoreController@password')->name('password');

	Route::get('forget_password', 'StoreController@forget_password')->name('forget_password');

	Route::match(array('GET', 'POST'), 'mail_confirm', 'StoreController@mail_confirm')->name('mail_confirm');

	Route::match(array('GET', 'POST'), 'set_password', 'StoreController@set_password')->name('set_password');

	Route::post('change_password', 'StoreController@change_password')->name('change_password');
});
//After login

Route::group(['middleware' => ['clear_cache', 'auth:store','locale']], function () {

	Route::get('logout', 'StoreController@logout')->name('logout');

	Route::get('dashboard', 'StoreController@dashboard')->name('dashboard');
	Route::get('menu', 'StoreController@menu')->name('menu');
	Route::post('update_category', 'StoreController@update_category')->name('update_category');
	Route::get('menu_time/{id}', 'StoreController@menu_time')->name('menu_time');
	Route::post('update_menu_time', 'StoreController@update_menu_time')->name('update_menu_time');
	Route::get('remove_menu_time/{id}', 'StoreController@remove_menu_time')->name('remove_menu_time');
	Route::post('update_menu_item', 'StoreController@update_menu_item')->name('update_menu_item');
	Route::post('delete_menu', 'StoreController@delete_menu')->name('delete_menu');
	Route::get('preparation', 'StoreController@preparation')->name('preparation');
	Route::post('update_preparation_time', 'StoreController@update_preparation_time')->name('update_preparation_time');
	Route::post('remove_time', 'StoreController@remove_time')->name('remove_time');

//Profile controller

	Route::match(array('GET', 'POST'), 'profile', 'ProfileController@index')->name('profile');
	Route::post('send_message', 'ProfileController@send_message')->name('send_message');
	Route::post('confirm_phone_no', 'ProfileController@confirm_phone_no')->name('confirm_phone_no');
	Route::match(array('GET', 'POST'), 'documents', 'ProfileController@documents')->name('documents');
	Route::match(array('GET', 'POST'), 'offers', 'ProfileController@offers')->name('offers');
	Route::get('remove_offer/{id}', 'ProfileController@remove_offer')->name('remove_offer');
	Route::post('offers_status', 'ProfileController@offers_status')->name('offers_status');

	Route::get('payout_preference', 'StoreController@payout_preference')->name('payout_preference');

	Route::get('export_data/{week}', 'StoreController@get_export')->name('export_data');

	Route::get('get_order_export/{date}', 'StoreController@get_order_export')->name('get_order_export');

	Route::match(array('GET', 'POST'), '/payout_details/{week}', 'StoreController@payout_daywise_details')->name('payout_details');

	Route::post('get_payout_preference', 'StoreController@get_payout_preference')->name('get_payout_preference');

	Route::post('update_payout_preferences/{id}', 'StoreController@update_payout_preferences')->name('update_payout_preferences');
	Route::post('update_open_time', 'ProfileController@update_open_time')->name('update_open_time');
	Route::post('update_documents', 'ProfileController@update_documents')->name('update_documents');
	Route::match(array('GET', 'POST'), 'show_comments', 'ProfileController@show_comments')->name('show_comments');
	Route::match(array('GET', 'POST'), 'status_update', 'ProfileController@status_update')->name('status_update');

});