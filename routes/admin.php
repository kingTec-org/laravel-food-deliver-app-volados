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

//Before login
Route::group(['middleware' => ['guest:admin','clear_cache']], function () {
	Route::get('/', 'AdminController@login')->name('login');
	Route::get('/login', 'AdminController@login')->name('login');
	Route::post('/authenticate', 'AdminController@authenticate')->name('authenticate');
});

//After login
Route::group(['middleware' => ['auth:admin','clear_cache']], function () {

	//admin Mnagement
	Route::get('/dashboard', 'AdminController@dashboard')->name('dashboard');
	Route::get('/logout', 'AdminController@logout')->name('logout');
	Route::get('/edit_admin/{id}', 'AdminController@show')->name('edit_admin');
	Route::post('/edit_admin/{id}', 'AdminController@update')->name('update_admin');
	//Site setting
	Route::match(array('GET', 'POST'), '/site_setting', 'SiteSettingController@site_setting')->name('site_setting');

	//User Management
	Route::match(array('GET', 'POST'), '/add_user', 'UserController@add_user')->name('add_user');
	Route::match(array('GET', 'POST'), '/edit_user/{id}', 'UserController@edit_user')->name('edit_user');
	Route::get('/view_user', 'UserController@view')->name('view_user');
	Route::get('/delete_user/{id}', 'UserController@delete')->name('delete_user');
	Route::get('/all_users', 'UserController@all_users')->name('all_users');
	Route::get('/penality', 'UserController@penality')->name('penality');

	//store Management
	Route::match(array('GET', 'POST'), '/add_store', 'StoreController@add_store')->name('add_store');
	Route::match(array('GET', 'POST'), '/edit_store/{id}', 'StoreController@edit_store')->name('edit_store');
	Route::get('/view_store', 'StoreController@view')->name('view_store');
	Route::get('/delete_store/{id}', 'StoreController@delete')->name('delete_store');
	Route::get('/all_stores', 'StoreController@all_stores')->name('all_stores');
	Route::get('/recommend/{id}', 'StoreController@recommend')->name('recommend');

	//driver Management
	Route::match(array('GET', 'POST'), '/add_driver', 'DriverController@add_driver')->name('add_driver');
	Route::match(array('GET', 'POST'), '/edit_driver/{id}', 'DriverController@edit_driver')->name('edit_driver');
	Route::get('/view_driver', 'DriverController@view')->name('view_driver');
	Route::get('/delete_driver/{id}', 'DriverController@delete')->name('delete_driver');
	Route::get('/all_drivers', 'DriverController@all_drivers')->name('all_drivers');
	Route::get('/owe_amount', 'DriverController@oweAmount')->name('owe_amount');
	Route::get('/driver_request/{id}', 'DriverController@driver_request')->name('driver_request');

	//promo management
	Route::get('/promo', 'PromoCodeController@view')->name('promo');
	Route::match(array('GET', 'POST'), '/add_promo', 'PromoCodeController@add')->name('add_promo');
	Route::match(array('GET', 'POST'), '/edit_promo/{id}', 'PromoCodeController@edit')->name('edit_promo');
	Route::match(array('GET', 'POST'), '/delete_promo/{id}', 'PromoCodeController@delete')->name('delete_promo');

	//Category management
	Route::get('/category', 'CategoryController@view')->name('category');
	Route::match(array('GET', 'POST'), '/add_category', 'CategoryController@add')->name('add_category');
	Route::match(array('GET', 'POST'), '/edit_category/{id}', 'CategoryController@edit')->name('edit_category');
	Route::match(array('GET', 'POST'), '/delete_category/{id}', 'CategoryController@delete')->name('delete_category');
	Route::get('/is_top/{id}/{column}', 'CategoryController@change_status')->name('is_top');
	Route::get('/most_popular/{id}/{column}', 'CategoryController@change_status')->name('most_popular');

	// Manage Help Routes
    Route::get('help_category', 'HelpCategoryController@index')->name('help_category');
    Route::match(array('GET', 'POST'), 'add_help_category', 'HelpCategoryController@add')->name('add_help_category');
    Route::match(array('GET', 'POST'), 'edit_help_category/{id}', 'HelpCategoryController@update')->where('id', '[0-9]+')->name('edit_help_category');
    Route::get('delete_help_category/{id}', 'HelpCategoryController@delete')->where('id', '[0-9]+')->name('delete_help_category');
    Route::get('help_subcategory', 'HelpSubCategoryController@index')->name('help_subcategory');
    Route::match(array('GET', 'POST'), 'add_help_subcategory', 'HelpSubCategoryController@add')->name('add_help_subcategory');
    Route::match(array('GET', 'POST'), 'edit_help_subcategory/{id}', 'HelpSubCategoryController@update')->where('id', '[0-9]+')->name('edit_help_subcategory');
    Route::get('delete_help_subcategory/{id}', 'HelpSubCategoryController@delete')->where('id', '[0-9]+')->name('delete_help_subcategory');
    Route::get('help', 'HelpController@index')->name('help');
    Route::match(array('GET', 'POST'), 'add_help', 'HelpController@add')->name('add_help');
    Route::match(array('GET', 'POST'), 'edit_help/{id}', 'HelpController@update')->where('id', '[0-9]+')->name('edit_help');
    Route::get('delete_help/{id}', 'HelpController@delete')->where('id', '[0-9]+')->name('delete_help');
    Route::post('ajax_help_subcategory/{id}', 'HelpController@ajax_help_subcategory')->where('id', '[0-9]+')->name('ajax_help_subcategory');


	//Static Page management
	Route::get('/static_page', 'PagesController@view')->name('static_page');
	Route::match(array('GET', 'POST'), '/add_static_page', 'PagesController@add')->name('add_static_page');
	Route::match(array('GET', 'POST'), '/edit_static_page/{id}', 'PagesController@edit')->name('edit_static_page');
	Route::match(array('GET', 'POST'), '/delete_static_page/{id}', 'PagesController@delete')->name('delete_static_page');

	//country Page management
	Route::get('/country', 'CountryController@view')->name('country');
	Route::match(array('GET', 'POST'), '/add_country', 'CountryController@add')->name('add_country');
	Route::match(array('GET', 'POST'), '/edit_country/{id}', 'CountryController@edit')->name('edit_country');
	Route::match(array('GET', 'POST'), '/delete_country/{id}', 'CountryController@delete')->name('delete_country');

	//currency Page management
	/*Route::get('/currency', 'CurrencyController@view')->name('currency');
	Route::match(array('GET', 'POST'), '/add_currency', 'CurrencyController@add')->name('add_currency');
	Route::match(array('GET', 'POST'), '/edit_currency/{id}', 'CurrencyController@edit')->name('edit_currency');
	Route::match(array('GET', 'POST'), '/delete_currency/{id}', 'CurrencyController@delete')->name('delete_currency');
*/
	//Metas
	Route::get('/metas', 'MetasController@view')->name('metas');
	Route::match(array('GET', 'POST'), '/metas/edit/{id}', 'MetasController@edit')->name('meta_edit');

	//order_cancel_reson  management
	Route::get('/cancel_reason', 'OrderCancelReasonController@view')->name('order_cancel_reason');
	Route::match(array('GET', 'POST'), '/add_cancel_reason', 'OrderCancelReasonController@add')->name('add_cancel_reason');
	Route::match(array('GET', 'POST'), '/edit_cancel_reason/{id}', 'OrderCancelReasonController@edit')->name('edit_cancel_reason');
	Route::match(array('GET', 'POST'), '/delete_cancel_reason/{id}', 'OrderCancelReasonController@delete')->name('delete_cancel_reason');

	//review_issue_types  management
	Route::get('/review_issue_type', 'IssueTypeController@view')->name('issue_type');
	Route::match(array('GET', 'POST'), '/add_issue_type', 'IssueTypeController@add')->name('add_issue_type');
	Route::match(array('GET', 'POST'), '/edit_issue_type/{id}', 'IssueTypeController@update')->name('edit_issue_type');
	Route::match(array('GET', 'POST'), '/delete_issue_type/{id}', 'IssueTypeController@delete')->name('delete_issue_type');

	//Recipient  management
	Route::get('/recipient', 'FoodReceiverController@view')->name('food_receiver');
	Route::match(array('GET', 'POST'), '/add_recipient', 'FoodReceiverController@add')->name('add_food_receiver');
	Route::match(array('GET', 'POST'), '/edit_recipient/{id}', 'FoodReceiverController@add')->name('edit_food_receiver');
	Route::match(array('GET', 'POST'), '/delete_recipient/{id}', 'FoodReceiverController@delete')->name('delete_food_receiver');

	//home_slider  management
	Route::get('/home_slider', 'SliderController@view_home_slider')->name('view_home_slider');
	Route::match(array('GET', 'POST'), '/add_home_slider', 'SliderController@home_slider')->name('add_home_slider');
	Route::match(array('GET', 'POST'), '/edit_home_slider/{id}', 'SliderController@home_slider')->name('edit_home_slider');
	Route::match(array('GET', 'POST'), '/delete_home_slider/{id}', 'SliderController@delete_home_slider')->name('delete_home_slider');


	//review_vehicle_types  management
	Route::get('review_vehicle_type', 'VehicleTypeController@view')->name('vehicle_type');
	Route::match(array('GET', 'POST'), '/add_vehicle_type', 'VehicleTypeController@add')->name('add_vehicle_type');
	Route::match(array('GET', 'POST'), '/edit_vehicle_type/{id}', 'VehicleTypeController@update')->name('edit_vehicle_type');
	Route::match(array('GET', 'POST'), '/delete_vehicle_type/{id}', 'VehicleTypeController@delete')->name('delete_vehicle_type');

	//Store order  management
	Route::get('/order', 'OrderController@orders')->name('order');
	Route::match(array('GET', 'POST'), '/view_order/{order_id}', 'OrderController@view_order')->name('view_order');
	Route::match(array('GET', 'POST'), '/all_orders', 'OrderController@all_orders')->name('all_orders');
	Route::match(array('GET', 'POST'), '/sort_order', 'OrderController@sort_order')->name('sort_order');
	Route::post('cancel_order', 'OrderController@cancel_order')->name('cancel_order');
	Route::post('admin_payout', 'OrderController@admin_payout')->name('admin_payout');

	//week payout
	Route::get('payout/{user_type}', 'PayoutController@payout')->name('payout')->where('user_type', '1|2');
	Route::get('all_payout', 'PayoutController@all_payout')->name('all_payout');
	Route::get('weekly_payout/{user_id}', 'PayoutController@weekly_payout')->name('weekly_payout');
	Route::get('driver_payout/{driver_id}', 'PayoutController@driver_payout')->name('driver_payout');
	Route::get('per_day_report/{user_id}/{start_date}/{end_date}', 'PayoutController@payout_per_day_report')->name('payout_per_day');
	Route::get('payout_day/{user_id}/{date}', 'PayoutController@payout_day')->name('payout_day');
	Route::get('payout_to/{user_id}/{order_id}', 'PayoutController@amount_payout')->name('amount_payout');
	Route::post('week_amount_payout', 'PayoutController@week_amount_payout')->name('week_amount_payout');

	//category
	Route::match(array('GET', 'POST'), '/menu/{id}', 'StoreController@menu_category')->name('menu_category');
	Route::post('update_category', 'StoreController@update_category')->name('update_category');
	Route::get('menu_time/{id}', 'StoreController@menu_time')->name('menu_time');
	Route::post('update_menu_time', 'StoreController@update_menu_time')->name('update_menu_time');
	Route::get('remove_menu_time/{id}', 'StoreController@remove_menu_time')->name('remove_menu_time');
	Route::post('update_menu_item', 'StoreController@update_menu_item')->name('update_menu_item');
	Route::post('delete_menu', 'StoreController@delete_menu')->name('delete_menu');
	Route::get('preparation', 'StoreController@preparation')->name('preparation');
	Route::post('update_preparation_time', 'StoreController@update_preparation_time')->name('update_preparation_time');
	//open time
	Route::match(array('GET','POST'),'edit_open_time/{store_id}', 'StoreController@open_time')->name('edit_open_time');
	//preparation time
	Route::match(array('GET','POST'),'edit_preparation_time/{store_id}', 'StoreController@preparation_time')->name('edit_preparation_time');
	
});