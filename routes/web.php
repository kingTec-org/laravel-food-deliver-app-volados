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

//home page
Route::group(['middleware' => ['installer', 'locale', 'clear_cache']], function () {
	
	Route::post('set_session', 'HomeController@set_session');
	Route::get('/', 'HomeController@home')->name('home');
	Route::get('about/{page}', 'HomeController@static_page')->name('page');

//search page

	Route::match(array('GET', 'POST'), '/stores', 'SearchController@index')->name('search');

	Route::post('store_location', 'SearchController@store_location_data')->name('store_location');

	Route::post('/search_result', 'SearchController@search_result')->name('search_result');

	Route::post('/search_data', 'SearchController@search_data')->name('search_data');

	Route::post('schedule_store', 'SearchController@schedule_store')->name('schedule_store');

//store details

	Route::match(array('GET', 'POST'), '/details/{store_id}', 'EaterController@detail')->name('details');

	Route::post('item_details', 'EaterController@menu_item_detail')->name('item_details');

	Route::post('category_details', 'EaterController@menu_category_details')->name('category_details');

	Route::post('orders_store', 'EaterController@orders_store')->name('orders_store');

	Route::post('orders_remove', 'EaterController@orders_remove')->name('orders_remove');

	Route::post('orders_change', 'EaterController@orders_change')->name('orders_change');

	Route::post('session_clear_data', 'EaterController@session_clear_data')->name('session_clear_data');

//payment page

	Route::get('/checkout', 'PaymentController@checkout')->name('checkout');

	Route::post('add_cart', 'PaymentController@add_cart')->name('add_cart');

	Route::get('order_track', 'PaymentController@order_track')->name('order_track');

	Route::post('card_details', 'PaymentController@add_card_details')->name('card_details');

	Route::post('place_order_details', 'PaymentController@place_order_details')->name('place_order_details');

	Route::post('place_order', 'PaymentController@place_order')->name('place_order');

	Route::post('location_check', 'EaterController@location_check')->name('location_check');

	Route::get('location_not_found', 'EaterController@location_not_found')->name('location_not_found');

	Route::post('cancel_order', 'PaymentController@cancel_order')->name('cancel_order');

	Route::post('order_invoice', 'EaterController@order_invoice')->name('order_invoice');

	Route::get('/privacy_policy', function () {
		return view('privacy_policy');
	});

	Route::get('help', 'HelpController@help')->name('help');

	Route::get('help/{page}', 'HelpController@help')->where(['page' => 'user|store|driver'])->name('help_page');

	Route::get('help/{page}/{category_id}', 'HelpController@help_category')->where(['category_id' => '[0-9]+', 'page' => 'user|store|driver'])->name('help_category');

	Route::get('help/{page}/{category_id}/{subcategory_id}', 'HelpController@help_subcategory')->where(['category_id' => '[0-9]+', 'page' => 'user|store|driver'])->name('help_subcategory');

	Route::get('help/{page}/{category_id}/{subcategory_id}/{question_id}', 'HelpController@help_question')->where(['category_id' => '[0-9]+', 'question_id' => '[0-9]+', 'page' => 'user|store|driver'])->name('help_question');

	Route::get('ajax_help_search', 'HelpController@ajax_help_search')->name('ajax_help_search');

	Route::get('/help_category', function () {
		return view('help_category');
	});

	Route::get('/help_detail', function () {
		return view('help_detail');
	});

	Route::get('/order_rating', function () {
		return view('order_rating');
	});

//login page

	Route::get('/login', 'HomeController@login')->name('login');

	Route::post('/authenticate', 'UserController@authenticate')->name('authenticate');

	Route::group(['middleware' => 'auth:web'], function () {

		Route::get('/orders', 'EaterController@order_history')->name('orders');

		Route::get('/logout', 'UserController@logout')->name('logout');

		Route::get('/user_profile', 'UserController@user_profile')->name('user_profile');

		Route::get('/user_payment', 'UserController@user_payment')->name('user_payment');

		Route::post('user_details_store', 'UserController@user_details_store')->name('user_details_store');

		Route::post('add_promo_code_data', 'EaterController@add_promo_code')->name('add_promo_code_data');

	});
	Route::post('password_change', 'HomeController@password_change')->name('password_change');

	//signup page
	Route::group(['middleware' => ['guest:web', 'clear_cache']], function () {

		Route::get('/signup', 'HomeController@signup')->name('signup');

		Route::get('/signup_confirm', 'HomeController@signup_confirm')->name('signup2');

		Route::post('signup_data', 'HomeController@store_signup_data')->name('signup_data');

		Route::post('store_signup_data', 'HomeController@store_user_data')->name('store_signup_data');

		Route::match(array('POST', 'GET'), 'forgot_password', 'HomeController@forgot_password')->name('forgot_password');

		Route::match(array('POST', 'GET'), 'otp_confirm', 'HomeController@otp_confirm')->name('otp_confirm');

		Route::match(array('POST', 'GET'), 'reset_password', 'HomeController@reset_password')->name('reset_password');

	});
});