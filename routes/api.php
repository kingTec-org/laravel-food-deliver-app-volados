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

Route::middleware('auth:api')->get(
	'/user', function (Request $request) {
		return $request->user();
	}
);

 Route::match(array('GET', 'POST'),'ios','EaterController@ios');
 Route::match(array('GET', 'POST'),'add_user_review', 'EaterController@add_user_review');
 Route::match(array('GET', 'POST'),'upload_image', 'EaterController@upload_image');

//TokenAuthController
Route::get('language','TokenAuthController@language');
Route::match(array('GET', 'POST'),'register', 'TokenAuthController@register');
Route::get('number_validation', 'TokenAuthController@number_validation');
Route::match(array('GET', 'POST'),'login', 'TokenAuthController@login');
Route::get('forgot_password', 'TokenAuthController@forgot_password');
Route::get('reset_password', 'TokenAuthController@reset_password');
Route::get('search_drivers', 'DriverController@search_drivers');

//cron

Route::get('remain_schedule_order', 'StoreController@remainScheduleOrder');
Route::get('beforeseven', 'StoreController@beforeSevenMin');
Route::get('cron_refund', 'PaymentController@cron_refund');



   // without Login 
   if(!request()->token)
   {

   	Route::group(['middleware' => ['without_login']], function () {
		Route::get('home', 'EaterController@home');
		Route::get('get_store_details', 'EaterController@get_store_details');
		Route::get('add_to_cart', 'EaterController@add_to_cart');
		Route::get('categories', 'EaterController@categories');
		Route::get('search', 'EaterController@search');
		Route::get('filter', 'EaterController@filter');
		Route::get('info_window', 'EaterController@info_window');
		});

    }
    else
    {
    	Route::group(['middleware' => ['jwt.auth','apilocale']], function () {

		Route::get('home', 'EaterController@home');
		Route::get('get_store_details', 'EaterController@get_store_details');
		Route::get('add_to_cart', 'EaterController@add_to_cart');
		Route::get('categories', 'EaterController@categories');
		Route::get('search', 'EaterController@search');
		Route::get('filter', 'EaterController@filter');
		Route::get('info_window', 'EaterController@info_window');
		});
    }



// for Login check

Route::group(
	['middleware' => ['jwt.auth','apilocale']], function () {
	

		Route::get('logout', 'TokenAuthController@logout');
		Route::get('change_mobile', 'TokenAuthController@change_mobile');
		Route::get('get_profile', 'TokenAuthController@get_profile');
		Route::get('update_device_id', 'TokenAuthController@update_device_id');
		Route::get('get_cancel_reason', 'StoreController@get_cancel_reason');

		//EaterController

		Route::get('get_location', 'EaterController@get_location');
		Route::get('save_location', 'EaterController@saveLocation');
		Route::get('default_location', 'EaterController@defaultLocation');
		Route::get('remove_location', 'EaterController@remove_location');
		Route::get('update_profile', 'EaterController@update_profile');
	
	
		Route::get('more_store', 'EaterController@more_store');
	
		Route::get('add_promo_code', 'EaterController@add_promo_code');
		Route::get('get_promo_details', 'EaterController@get_promo_details');
      
		Route::get('add_wish_list', 'EaterController@add_wish_list');
		
		Route::get('view_cart', 'EaterController@view_cart');
		Route::get('clear_cart', 'EaterController@clear_cart');
		Route::get('order_list', 'EaterController@order_list');
		Route::get('clear_all_cart', 'EaterController@clear_all_cart');
		Route::get('add_card_details', 'EaterController@add_card_details');
		Route::get('get_card_details', 'EaterController@get_card_details');
		Route::get('cancel_order', 'EaterController@cancel_order');
		Route::get('user_review', 'EaterController@user_review');
		Route::get('add_wallet_amount', 'EaterController@add_wallet_amount');
		Route::get('wishlist', 'EaterController@wishlist');

		//paymentcontroller
		Route::get('place_order', 'PaymentController@place_order');
		Route::get('refund', 'PaymentController@refund');

		// Restauranrt API
		Route::get('orders', 'StoreController@orders');
		Route::get('accept_order', 'StoreController@accept_order');
		Route::get('store_order_details', 'StoreController@order_details');
		Route::get('food_ready', 'StoreController@food_ready');
		Route::get('store_cancel_order', 'StoreController@cancel_order');
		Route::get('delay_order', 'StoreController@delay_order');
		Route::get('store_menu', 'StoreController@menu');
		Route::get('toggle_visible', 'StoreController@toggle_visible');
		Route::get('order_history', 'StoreController@order_history');
		Route::get('store_availabilty', 'StoreController@store_availabilty');
		Route::get('review_store_to_driver', 'StoreController@review_store_to_driver');

		// Driver API
		Route::get('vehicle_details', 'DriverController@vehicle_details');
		Route::post('document_upload', 'DriverController@document_upload')->middleware('jwt.driver:false');
		Route::group(
			['middleware' => 'jwt.driver'], function () {
				Route::get('check_status', 'DriverController@check_status');
				Route::get('update_driver_location', 'DriverController@update_driver_location');
				Route::get('get_driver_profile', 'DriverController@get_driver_profile');
				Route::get('update_driver_profile', 'DriverController@update_driver_profile');
				Route::get('accept_request', 'DriverController@accept_request');
				Route::get('cancel_request', 'DriverController@cancel_request');
				Route::get('driver_order_details', 'DriverController@driver_order_details');
				Route::get('dropoff_data', 'DriverController@dropoff_data');
				Route::get('pickup_data', 'DriverController@pickup_data');
				Route::post('add_payout_perference', 'DriverController@add_payout_perference');
				Route::get('payout_details', 'DriverController@payout_details');
				Route::get('payout_changes', 'DriverController@payout_changes');
				Route::get('confirm_order_delivery', 'DriverController@confirm_order_delivery');
				Route::get('start_order_delivery', 'DriverController@start_order_delivery');
				Route::get('drop_off_delivery', 'DriverController@drop_off_delivery');
				Route::post('complete_order_delivery', 'DriverController@complete_order_delivery');
				Route::get('cancel_order_delivery', 'DriverController@cancel_order_delivery');
				Route::get('get_owe_amount', 'DriverController@get_owe_amount');
				Route::get('pay_to_admin', 'DriverController@pay_to_admin');
				Route::get('earning_list', 'DriverController@earning_list');
				Route::get('order_delivery_history', 'DriverController@order_delivery_history');
				Route::get('particular_order', 'DriverController@particular_order');
				Route::get('weekly_trip', 'DriverController@weekly_trip');
				Route::get('weekly_statement', 'DriverController@weekly_statement');
				Route::get('daily_statement', 'DriverController@daily_statement');
				Route::get('static_map', 'DriverController@static_map');
			}
		);
	}
);