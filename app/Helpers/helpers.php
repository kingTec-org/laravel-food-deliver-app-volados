<?php

use App\Models\Country;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\DriverOweAmount;
use App\Models\File;
use App\Models\MenuItem;
use App\Models\MenuTime;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderItem;
use App\Models\Penality;
use App\Models\PenalityDetails;
use App\Models\Store;
use App\Models\StoreOffer;
use App\Models\StoreTime;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UsersPromoCode;
use App\Models\Wallet;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Classes\LaravelExcelWorksheet;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
if (!function_exists('store_images')) {

	/**
	 * Fetch store images from File
	 *
	 * @param  int    $source_id Store Id
	 * @param  string $type      Type of the image
	 * @return string               file name
	 */
	function store_images($source_id, $type) {
		$image = File::where('type', $type)->where('source_id', $source_id)->first();

		if ($image) {
			return $image->image_name;
		}
		return getEmptyStoreImage();

	}
}

if (!function_exists('sample_image')) {

	/**
	 * Get sample image
	 */

	function sample_image() {
		return url('/images/sample.png');
	}
}

if (!function_exists('day_name')) {

	/**
	 * Get array of day names
	 *
	 * @param  string $key Key of the day
	 * @return string      Name of the week day
	 */
	function day_name($key = '') {
		$arrayName = array('1' => trans('admin_messages.monday'), '2' => trans('admin_messages.tuesday'), '3' => trans('admin_messages.wednesday'), '4' => trans('admin_messages.thursday'), '5' => trans('admin_messages.friday'), '6' => trans('admin_messages.saturday'), '7' => trans('admin_messages.sunday'));
		if ($key == '') {
			return $arrayName;
		}
		return $arrayName[$key];
	}
}

if (!function_exists('static_pages')) {
	function static_pages($key = '') {
		$data = resolve('static_page');
		if (isset($data[$key])) {
			return $data[$key];
		}
	}
}

if (!function_exists('site_setting')) {

	/**
	 * Checks if a value exists in an array in a case-insensitive manner
	 *
	 * @param string $key
	 * The searched value
	 *
	 * @return if key found, return particular value of key. Otherwise return full array.
	 */
	function site_setting($key = '', $value = '') {
		$setting = resolve('site_setting');
		//site_name translation process
		if($key == 'site_name'){
			$getLocale = App::getLocale();
			
					if($getLocale == 'ar'){
						
						if(!empty($setting['site_translation_name'])){
							$setting[$key] = $setting['site_translation_name'];	
						}						
					}
					if($getLocale == 'pt'){
						
						if(!empty($setting['site_pt_translation'])){
							$setting[$key] = $setting['site_pt_translation'];	
						}						
					}
			}
		//
		if ($key != '') {
			if ($value == '') {
				return $setting[$key];
			} else {
				$file = File::where('type', 1)->where('source_id', $value)->first();
				if ($file) {
					return url($file->site_image_url);
				}

			}
		} else {
			return $setting;
		}
	}
}

if (!function_exists('convertPHPToMomentFormat')) {

	/*
		     * Matches each symbol of PHP date format standard
		     * with jQuery equivalent codeword
		     * @author Tristan Jahier
	*/
	function convertPHPToMomentFormat($format) {
		$replacements = [
			'd' => 'DD',
			'D' => 'ddd',
			'j' => 'D',
			'l' => 'dddd',
			'N' => 'E',
			'S' => 'o',
			'w' => 'e',
			'z' => 'DDD',
			'W' => 'W',
			'F' => 'MMMM',
			'm' => 'MM',
			'M' => 'MMM',
			'n' => 'M',
			't' => '', // no equivalent
			'L' => '', // no equivalent
			'o' => 'YYYY',
			'Y' => 'YYYY',
			'y' => 'YY',
			'a' => 'a',
			'A' => 'A',
			'B' => '', // no equivalent
			'g' => 'h',
			'G' => 'H',
			'h' => 'hh',
			'H' => 'HH',
			'i' => 'mm',
			's' => 'ss',
			'u' => 'SSS',
			'e' => 'zz', // deprecated since version 1.6.0 of moment.js
			'I' => '', // no equivalent
			'O' => '', // no equivalent
			'P' => '', // no equivalent
			'T' => '', // no equivalent
			'Z' => '', // no equivalent
			'c' => '', // no equivalent
			'r' => '', // no equivalent
			'U' => 'X',
		];
		$momentFormat = strtr($format, $replacements);
		return $momentFormat;
	}
}

if (!function_exists('get_driving_distance')) {

	/**
	 * Getting driving distance
	 *
	 * @param  string $lat1  Start point latitude
	 * @param  string $lat2  Start point longitude
	 * @param  string $long1 End point latitude
	 * @param  string $long2 End point longitude
	 * @return array        Array of status, distance, time
	 */
	function get_driving_distance($lat1, $lat2, $long1, $long2) {

		$url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $lat1 . "," . $long1 . "&destinations=" . $lat2 . "," . $long2 . "&mode=driving&language=pl-PL&key=" . site_setting('google_api_key');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response, true);

		if ($response_a['status'] == "REQUEST_DENIED" || $response_a['status'] == "OVER_QUERY_LIMIT") {
			return array('status' => "fail", 'msg' => $response_a['error_message'], 'time' => '0', 'distance' => "0");
		} elseif ($response_a['status'] == "OK" && $response_a['rows'][0]['elements'][0]['status'] != "ZERO_RESULTS") {
			$dist_find = $response_a['rows'][0]['elements'][0]['distance']['value'];
			$time_find = $response_a['rows'][0]['elements'][0]['duration']['value'];

			$dist = $dist_find != '' ? $dist_find : '0';
			$time = $time_find != '' ? $time_find : '0';

			return array('status' => 'success', 'distance' => $dist, 'time' => (int) $time);
		} else {
			return array('status' => 'success', 'distance' => "1", 'time' => "1");
		}
	}
}

if (!function_exists('getSecondsFromTime')) {

	/**
	 * Checks if a value exists in an array in a case-insensitive manner
	 *
	 * @param string $key
	 * The searched value
	 *
	 * @return if key found, return particular value of key. Otherwise return full array.
	 */
	function getSecondsFromTime($time = '') {
		sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);
		$time_seconds = $hours * 3600 + $minutes * 60 + $seconds;

		return $time_seconds;
	}
}

if (!function_exists('getTimeFromSeconds')) {

	/**
	 * Checks if a value exists in an array in a case-insensitive manner
	 *
	 * @param string $key
	 * The searched value
	 *
	 * @return if key found, return particular value of key. Otherwise return full array.
	 */
	function getTimeFromSeconds($seconds = '') {

		$hours = floor($seconds / 3600);
		$mins = floor($seconds / 60 % 60);
		$secs = floor($seconds % 60);

		$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

		return $timeFormat;
	}
}

if (!function_exists('getEmptyUserImageUrl')) {

	/**
	 * Checks if a value exists in an array in a case-insensitive manner
	 *
	 * @param string $key
	 * The searched value
	 *
	 * @return if key found, return particular value of key. Otherwise return full array.
	 */
	function getEmptyUserImageUrl() {
		return url('images/user.png');
	}
}

//Restaurnt Default images

if (!function_exists('getEmptyStoreImage')) {

	/**
	 * Checks if a value exists in an array in a case-insensitive manner
	 *
	 * @param string $key
	 * The searched value
	 *
	 * @return if key found, return particular value of key. Otherwise return full array.
	 */
	function getEmptyStoreImage() {
		return url('images/default-store.jpg');
	}
}

//Custom sms send function

if (!function_exists('send_nexmo_message')) {

	/**
	 * custom sms
	 *
	 * @return success or fail
	 */
	function send_nexmo_message($to, $message) {

		//for live only start
		// return array('status' => 'Success', 'message' => 'Message send successfully');
		//for live only end

		//return array('status'=>'Success');
		$url = 'https://rest.nexmo.com/sms/json?' . http_build_query(
			[
				'api_key' => site_setting('nexmo_key'),
				'api_secret' => site_setting('nexmo_secret_key'),
				'to' => $to,
				'from' => site_setting('nexmo_from_number'),
				'text' => $message,
				'type' => 'unicode'
			]
		);

		$response = @file_get_contents($url);

		$response_data = json_decode($response, true);

		$status = 'Failed';
		$status_message = trans('messages.errors.internal_server_error');

		if (@$response_data['messages']) {
			foreach ($response_data['messages'] as $message) {
				if ($message['status'] == 0) {
					$status = 'Success';
				} else {
					$status = 'Failed';
				if($message['error-text'] == 'Non White-listed Destination - rejected' || $message['error-text'] == 'Quota Exceeded - rejected')
					$status_message = trans('messages.errors.'.$message['error-text']);
				else
					$status_message = $message['error-text'];
				}
			}
		}
		return array('status' => $status, 'message' => $status_message);
	}
}

//random key generation

if (!function_exists('random_num')) {
	function random_num($size) {
		$alpha_key = '';
		$keys = range('A', 'Z');

		for ($i = 0; $i < 2; $i++) {
			$alpha_key .= $keys[array_rand($keys)];
		}

		$length = $size - 2;

		$key = '';
		$keys = range(0, 9);

		for ($i = 0; $i < $length; $i++) {
			$key .= $keys[array_rand($keys)];
		}

		return $alpha_key . $key;
	}
}

if (!function_exists('get_current_login_user')) {
	function get_current_login_user() {
		if (request()->route()->getPrefix() == "admin") {
			if (auth()->guard('admin')->check()) {
				return 'admin';
			}
		} elseif (request()->route()->getPrefix() == "store") {
			if (auth()->guard('store')->check()) {
				return 'store';
			}
		} elseif (auth()->guard('web')->check()) {
			return 'web';
		} elseif (auth()->guard('driver')->check()) {
			return 'driver';
		}
	}
}
if (!function_exists('get_current_root')) {
	function get_current_root() {
		if (request()->route()->getPrefix() == "admin") {
			return 'admin';
		} elseif (request()->route()->getPrefix() == "store") {
			return 'store';
		} elseif (request()->route()->getPrefix() == "api") {
			return 'api';
		} else {
			return 'web';
		}

	}
}

if (!function_exists('check_current_root')) {
	function check_current_root() {
		if (Request::segment(1) == "admin") {
			return 'admin';
		} elseif (Request::segment(1) == "store") {
			return 'store';
		} elseif (Request::segment(1) == "api") {
			return 'api';
		} 
		elseif (Request::segment(1) == "driver") {
			return 'driver';
		}
		else {
			return 'web';
		}

	}
}

if (!function_exists('is_user')) {
	function is_user() {
		return (get_current_login_user() == 'web');
	}
}
if (!function_exists('current_page')) {
	function current_page() {
		if (request()->route()->getPrefix() == '') {
			return 'user';
		} else {
			return request()->route()->getPrefix();
		}

	}
}
if (!function_exists('home_page_link')) {
	function home_page_link() {
		if (request()->route()->getPrefix() == '')
			return route('home');
		else if(request()->route()->getPrefix()=='store')
			return route('store.signup');
		else if(request()->route()->getPrefix()=='driver')
			return route('driver.home');

	}
}

if (!function_exists('get_current_login_user_id')) {
	function get_current_login_user_id() {
		if (get_current_login_user() == 'admin') {
			return auth()->guard('admin')->user()->id;
		} elseif (get_current_login_user() == 'store') {
			return auth()->guard('store')->user()->id;
		} elseif (get_current_login_user() == 'web') {
			return auth()->guard('web')->user()->id;
		}
	}
}

if (!function_exists('get_current_login_user_language')) {
	function get_current_login_user_language() {
		if (get_current_login_user() == 'admin') {
			return auth()->guard('admin')->user()->language;
		} elseif (get_current_login_user() == 'store') {
			return auth()->guard('store')->user()->language;
		} elseif (get_current_login_user() == 'web') {
			return auth()->guard('web')->user()->language;
		}
	}
}


if (!function_exists('get_current_login_user_details')) {
	function get_current_login_user_details($detail) {
		if (get_current_login_user() == 'admin') {
			return auth()->guard('admin')->user()->$detail;
		} elseif (get_current_login_user() == 'store') {
			return auth()->guard('store')->user()->$detail;
		} elseif (get_current_login_user() == 'web') {
			return auth()->guard('web')->user()->$detail;
		}
	}
}

if (!function_exists('get_current_store_id')) {
	function get_current_store_id() {
		$user_id = auth()->guard('store')->user()->id;
		$store = $store = Store::where('user_id', $user_id)->first();
		return $store->id;
	}
}

if (!function_exists('get_store_user_id')) {
	function get_store_user_id($store_id, $column = 'user_id') {
		$user_id = Store::find($store_id)->$column;
		return $user_id;
	}
}

if (!function_exists('get_driver_user_id')) {
	function get_driver_user_id($driver_id, $column = 'user_id') {
		$user_id = Driver::find($driver_id)->$column;
		return $user_id;
	}
}

if (!function_exists('get_user_address')) {
	function get_user_address($user_id) {
		$address = UserAddress::where('user_id', $user_id)->where('default', '1')->first();
		return $address;
	}
}

if (!function_exists('get_store_address')) {
	function get_store_address($user_id) {
		$address = UserAddress::where('user_id', $user_id)->first();
		return $address;
	}
}

if (!function_exists('flash_message')) {
	/**
	 * Save Session
	 *
	 * @param String $class
	 * Class name for error mesage
	 *
	 * @param String $message
	 * Error messgae content
	 * */
	// Set Flash Message function
	function flash_message($class, $message) {
		\Session::flash('alert-class', 'alert-' . $class);
		\Session::flash('message', $message);
	}
}

if (!function_exists('time_data')) {

	/**
	 * Checks if a value exists in an array in a case-insensitive manner
	 *
	 * @param integer $key file type id
	 *                     The searched value
	 *
	 * @return if key found, return particular value of key. Otherwise return full array.
	 */
	function time_data($key = '') {
		$time_data = resolve('time_data');
		if ($key != '') {
			return $time_data[$key];
		} else {
			return '';
		}
	}
}

// one week date

if (!function_exists('date_data')) {

	function date_data() {
		$current_date = date("Y/m/d");
		$week_date = $current_date;
		$date;
		for ($i = 0; $i <= 6; $i++) {
			$date[date('Y-m-d', strtotime('+' . $i . ' day', strtotime($week_date)))] = date('Y, M d', strtotime('+' . $i . ' day', strtotime($week_date)));
			//dd($date);
		}

		return $date;
	}
}

if (!function_exists('convert_minutes')) {

	function convert_minutes($str_time) {
		$str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $str_time);

		sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

		return $minutes = ($hours * 3600 + $minutes * 60 + $seconds) / 60;
	}
}

if (!function_exists('convert_format')) {
	function convert_format($str_time) {
		$hours = (int) ($str_time / 60);
		$minutes = ($str_time % 60);
		$format = '%02d:%02d:00';

		return sprintf($format, $hours, $minutes);
	}
}

if (!function_exists('time_format')) {

	function time_format($str_time) {
		return date("g:i", strtotime($str_time)).' '.trans('api_messages.monthandtime.'.date("a", strtotime($str_time)));
	}
}

if (!function_exists('promo_calculation')) {
	function promo_calculation() {
		$user_id = auth()->guard()->user()->id;

		$order_id = Order::where('user_id', $user_id)->status('cart')->first();

		if (!$order_id) {
			return response()->json(
				[

					'status_message' => 'Cart Empty',

					'status_code' => '0',

				]
			);
		} else {

			$user_promocode = UsersPromoCode::WhereHas(
				'promo_code'

			)->where('user_id', $user_id)->where('order_id', '0')->first();

			$promo_amount = 0;
			$promo_code_id = 0;

			if (count($user_promocode) > 0) {
				$promo_code_id = $user_promocode->promo_code->id;
				if ($user_promocode->promo_code->promo_type == 0) {
					$promo_amount = $user_promocode->promo_code->price;
				} else {

					$promo_amount = ($order_id->subtotal + $order_id->tax + $order_id->delivery_fee + $order_id->booking_fee) / 100 * $user_promocode->promo_code->percentage;
				}

			}

			$update = Order::where('id', $order_id->id)->status('cart')->first();

			$total_amount = $update->user_total;

			$update->promo_id = $promo_code_id;
			$update->promo_amount = $promo_amount;
			$update->total_amount = $total_amount;
			$update->save();

			//Order::where('id', $order_id->id)->status('cart')->first();

			return number_format_change($update->promo_amount);
		}
	}
}

if (!function_exists('offer_calculation')) {

	function offer_calculation($store_id, $order_id) {

		$date = \Carbon\Carbon::today();

		$store_offer = StoreOffer::where('start_date', '<=', $date)->where('end_date', '>=', $date)->where('status', 1)
			->where('store_id', $store_id)->first();

		$offer = 0;

		if ($store_offer) {

			if ($store_offer->percentage != 0) {

				$offer = $store_offer->percentage;

			}

		}

		$item = DB::table('order_item')
			->selectRaw('sum(total_amount * ' . $offer . ' / 100 ) as total,sum(tax * ' . $offer . ' / 100 ) as tax,sum(total_amount) as total_amount,sum(tax) as total_tax')
			->whereRaw('order_id = ' . $order_id)
			->groupBy('id')
			->get();

		$offer_amount = $item->sum('total');
		$offer_tax = $item->sum('tax');
		$item_amount = $item->sum('total_amount');
		$item_tax = $item->sum('total_tax');

		$order = Order::find($order_id);
		$order->offer_percentage = $offer;
		$order->subtotal = $item_amount - $offer_amount;
		$order->tax = $item_tax - $offer_tax;
		$order->offer_amount = $offer_amount + $offer_tax;
		$order->save();

		$subtotal = number_format($order->subtotal, 2, '.', '');

		return $subtotal;

	}
}

if (!function_exists('use_wallet_amount')) {

	function use_wallet_amount($order_id, $is_wallet) {

		$order_details = Order::find($order_id);

		$order_amount = $order_details->user_total;

		$applied_wallet = 0;
		$remaining_wallet = 0;
		$wallet_amount = 0;
		$amount = $order_amount;

		if ($is_wallet == 1) {

			$user_id = auth()->guard()->user()->id;
			$wallet = Wallet::where('user_id', $user_id)->first();

			if (count($wallet) == 0) {
				return [
					'amount' => $amount,
					'applied_wallet_amount' => $applied_wallet,
					'remaining_wallet_amount' => $remaining_wallet,
					'wallet' => 0,
				];
			} else {

				$wallet_amount = $wallet->amount;

			}

			if ($order_amount >= $wallet_amount) {
				$amount = $order_amount - $wallet_amount;
				$remaining_wallet = 0;
				$applied_wallet = $wallet_amount;

			} else if ($order_amount < $wallet_amount) {

				$remaining_wallet = $wallet_amount - $order_amount;
				$amount = 0;
				$applied_wallet = $order_amount;

			} else {

				$amount = $order_amount;

			}
		}

		$order_details->wallet_amount = $applied_wallet;
		$order_details->total_amount = $order_details->user_total - $applied_wallet;
		$order_details->save();

		return [
			'amount' => $amount,
			'applied_wallet_amount' => $applied_wallet,
			'remaining_wallet_amount' => $remaining_wallet,
		];

	}

}

if (!function_exists('replace_null_value')) {

	function replace_null_value($array) {

		return array_map(function ($value) {
			return $value == null ? '' : $value;
		}, $array);

	}
}

if (!function_exists('getWeekDates')) {

	function getWeekDates($year, $week) {
		$from = date("Y-m-d", strtotime("{$year}-W{$week}-1")); //Returns the date of monday in week
		$to = date("Y-m-d", strtotime("{$year}-W{$week}-7")); //Returns the date of sunday in week

		return ['week_start' => $from, 'week_end' => $to];

		//return "Week {$week} in {$year} is from {$from} to {$to}.";
	}

}

function getStaticGmapURLForDirection($origin, $destination, $size = "1350x400") {

	$markers = array();

	$pickup = url('images/map_green.png');
	$drop = url('images/map.png');

	$markers[] = "markers=icon:" . $pickup . "|" . $origin;

	$markers[] = "markers=icon:" . $drop . "|" . $destination;

	$markers = implode($markers, '&');

	$url = "https://maps.googleapis.com/maps/api/directions/json?origin=" . $origin . "&destination=" . $destination . "&mode=driving&key=" . site_setting('google_api_key');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, false);
	$result = curl_exec($ch);
	curl_close($ch);
	$googleDirection = json_decode($result, true);

	if ($googleDirection['routes']) {
		$polyline = urlencode($googleDirection['routes'][0]['overview_polyline']['points']);
	} else {
		return;
	}

	$map = "https://maps.googleapis.com/maps/api/staticmap?size=$size&maptype=roadmap&path=color:0x000000ff|weight:4|enc:$polyline&$markers&key=" . site_setting('google_api_key');
	return $map;

}

if (!function_exists('get_status_text')) {

	function get_status_text($status) {
		return (($status === 0) ? trans('admin_messages.inactive') : ($status == 1 ? trans('admin_messages.active') : ''));
	}

}
if (!function_exists('get_status_yes')) {

	function get_status_yes($status) {
		return ($status === 1) ? trans('admin_messages.yes') : trans('admin_messages.no');
	}

}

if (!function_exists('change_date_format')) {

	function change_date_format($value) {
		if (site_setting('site_date_format') == 'm-d-Y' || site_setting('site_date_format') == 'm/d/Y') {
			if ($value) {
				$date = str_replace('/', '-', $value);
				$date = explode('-', $date);
				return $date[1] . '-' . $date[0] . '-' . $date[2];
			}
		} else {
			return str_replace('/', '-', $value);
		}

	}
}

if (!function_exists('set_date_on_picker')) {

	function set_date_on_picker($value) {
		if ($value) {
			return date('d-m-Y', strtotime($value));
		}

		return '';
	}
}

if (!function_exists('navigation_active')) {

	function navigation_active($route_name, $type = '') {

		if (request()->route()->getName() == $route_name) {

			if ($type) {
				if (@request()->route()->parameters['user_type'] == $type) {
					return true;
				} else {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}

	}
}
if (!function_exists('get_image_size')) {
	/**
	 * Get Crop Image size
	 * @return Image size
	 *
	 * */
	function get_image_size($name) {
		$file['site_logo'] = array('width' => '130', 'height' => '65');
		$file['email_logo'] = array('width' => '130', 'height' => '65');
		$file['site_favicon'] = array('width' => '50', 'height' => '50');
		$file['store_logo'] = array('width' => '130', 'height' => '65');
		$file['footer_logo'] = array('width' => '130', 'height' => '65');
		$file['app_logo'] = array('width' => '140', 'height' => '140');
		$file['driver_logo'] = array('width' => '130', 'height' => '65');
		$file['driver_white_logo'] = array('width' => '130', 'height' => '65');
		$file['home_slider'] = array('width' => '1300', 'height' => '500');
		$file['dietary_icon_size'] = array('width' => '256', 'height' => '256');
		$file['item_image_sizes'] = [
			array('width' => '120', 'height' => '120'),
			array('width' => '600', 'height' => '350'),
			array('width' => '520', 'height' => '320'),
		];
		$file['category_image_size'] = array('width' => '250', 'height' => '140');
		$file['store_image_sizes'] = [
			array('width' => '520', 'height' => '320'),
			array('width' => '520', 'height' => '280'),
			array('width' => '480', 'height' => '320'),
			array('width' => '100', 'height' => '100'),
		];

		return $file[$name];
	}
}

if (!function_exists('driver_default_documents')) {
	/**
	 * Get Crop Image size
	 * @return Image size
	 *
	 * */
	function driver_default_documents() {
		return array(8 => 'licence_front',
			9 => 'licence_back',
			10 => 'registeration_certificate',
			11 => 'insurance',
			12 => 'motor_certiticate');
	}
}

if (!function_exists('currency_symbol')) {
	/**
	 * Get Crop Image size
	 * @return Image size
	 *
	 * */
	function currency_symbol() {
		return DEFAULT_CURRENCY;
	}
}

if (!function_exists('side_navigation')) {
	/**
	 * Get Crop Image size
	 * @return Image size
	 *
	 * */
	function side_navigation() {

		//side navigation
		$nav['dashboard'] = array('name' => trans('admin_messages.dashboard'), 'icon' => 'assessment', 'route' => route('admin.dashboard'), 'active' => navigation_active('admin.dashboard'));
		$nav['user_management'] = array('name' => trans('admin_messages.user_management'), 'icon' => 'account_circle', 'route' => route('admin.view_user'), 'active' => navigation_active('admin.view_user'));
		$nav['driver_management'] = array('name' => trans('admin_messages.driver_management'), 'icon' => 'drive_eta', 'route' => route('admin.view_driver'), 'active' => navigation_active('admin.view_driver'));
		$nav['store_management'] = array('name' => trans('admin_messages.store_management'), 'icon' => 'store', 'route' => route('admin.view_store'), 'active' => navigation_active('admin.view_store'));
		$nav['order_management'] = array('name' => trans('admin_messages.order_managemnt'), 'icon' => 'add_shopping_cart', 'route' => route('admin.order'), 'active' => navigation_active('admin.order'));
		$nav['store_payout_management'] = array('name' => trans('admin_messages.store_payout_management'), 'icon' => 'euro_symbol', 'route' => route('admin.payout', 1), 'active' => navigation_active('admin.payout', 1));
		$nav['driver_payout_management'] = array('name' => trans('admin_messages.driver_payout_management'), 'icon' => 'motorcycle', 'route' => route('admin.payout', 2), 'active' => navigation_active('admin.payout', 2));
		$nav['driver_owe_amount'] = array('name' => trans('admin_messages.owe_amount'), 'icon' => 'attach_money', 'route' => route('admin.owe_amount'), 'active' => navigation_active('admin.owe_amount'));
		$nav['penality'] = array('name' => trans('admin_messages.penalty'), 'icon' => 'thumb_down', 'route' => route('admin.penality'), 'active' => navigation_active('admin.penality'));
		$nav['category_management'] = array('name' => trans('admin_messages.category_management'), 'icon' => 'fastfood', 'route' => route('admin.category'), 'active' => navigation_active('admin.category'));
		$nav['promo_management'] = array('name' => trans('admin_messages.promo_management'), 'icon' => 'card_giftcard', 'route' => route('admin.promo'), 'active' => navigation_active('admin.promo'));
		$nav['static_page_management'] = array('name' => trans('admin_messages.static_page_management'), 'icon' => 'description', 'route' => route('admin.static_page'), 'active' => navigation_active('admin.static_page'));
		$nav['home_slider'] = array('name' => trans('admin_messages.home_slider'), 'icon' => 'description', 'route' => route('admin.view_home_slider'), 'active' => navigation_active('admin.view_home_slider'));
		// $nav['country_management'] = array('name' => trans('admin_messages.country_management'), 'icon' => 'language', 'route' => route('admin.country'), 'active' => navigation_active('admin.country'));
		// $nav['currency_management'] = array('name' => trans('admin_messages.currency_management'), 'icon' => 'euro_symbol', 'route' => route('admin.currency'), 'active' => navigation_active('admin.currency'));
		$nav['cancel_reason'] = array('name' => trans('admin_messages.cancel_reason'), 'icon' => 'cancel', 'route' => route('admin.order_cancel_reason'), 'active' => navigation_active('admin.order_cancel_reason'));
		$nav['review_issue_type'] = array('name' => trans('admin_messages.review_issue_type'), 'icon' => 'report_problem', 'route' => route('admin.issue_type'), 'active' => navigation_active('admin.issue_type'));
		$nav['review_vehicle_type'] = array('name' => trans('admin_messages.manage_vehicle_type'), 'icon' => 'drive_eta', 'route' => route('admin.vehicle_type'), 'active' => navigation_active('admin.vehicle_type'));
		$nav['food_receiver'] = array('name' => trans('admin_messages.item_receiver'), 'icon' => 'receipt', 'route' => route('admin.food_receiver'), 'active' => navigation_active('admin.food_receiver'));
		$nav['help_category'] = array('name' => trans('admin_messages.help_category'), 'icon' => 'help', 'route' => route('admin.help_category'), 'active' => navigation_active('admin.help_category'));
		$nav['help_subcategory'] = array('name' => trans('admin_messages.help_subcategory'), 'icon' => 'help', 'route' => route('admin.help_subcategory'), 'active' => navigation_active('admin.help_subcategory'));
		$nav['help'] = array('name' => trans('admin_messages.help'), 'icon' => 'help', 'route' => route('admin.help'), 'active' => navigation_active('admin.help'));
		$nav['site_setting'] = array('name' => trans('admin_messages.site_setting'), 'icon' => 'settings', 'route' => route('admin.site_setting'), 'active' => navigation_active('admin.site_setting'));
		return $nav;
	}
}

if (!function_exists('number_format_change')) {
	/**
	 * Currency Symbol
	 *
	 * @return int currency symbol
	 */
	function number_format_change($value) {
		return number_format((float) $value, 2, '.', '');
	}
}

//push notification function

if (!function_exists('push_notification_for_store')) {


	function push_notification_for_store($order) {
		
		if ($order->schedule_status == 0) {

			$push_notification_title = trans('api_messages.orders.order_created');
			$type = 'new_order';

		} else {

			$push_notification_title = trans('api_messages.orders.schedule_order_created');
			$type = 'schedule_order';
		}

		$store_user = $order->store->user;
		$push_notification_data = [
			'type' => $type,
			'order_id' => $order->id,
			'order_data' => [
				'id' => $order->id,
				'order_item_count' => $order->order_item->count(),
				'user_name' => $order->user->name,
				'user_image' => $order->user->user_image_url,
				'remaining_seconds' => $order->remaining_seconds,
				'total_seconds' => $order->total_seconds,
				'status_text' => $order->status_text,
			],
		];

		push_notification($store_user->device_type, $push_notification_title, $push_notification_data, 1, $store_user->device_id, true);

	}
}

//otp genrate function

if (!function_exists('otp_for_forget_password')) {

	function otp_for_forget_password($email) {

		$data['subject'] = 'Password Reset';
		$data['otp_code'] = random_num(4);
		$data['logo'] = site_setting(1, 4);

		\Session::put('password_code', $data['otp_code']);

		\Mail::send('emails.forgot_password', $data, function ($message) use ($email, $data) {
			$message->to($email, '')->subject($data['subject']);
		});

		return true;
	}
}

//otp genrate function

if (!function_exists('otp_for_forget_eater')) {

	function otp_for_forget_eater($email, $otp) {

		$data['subject'] = 'Password Reset';
		$data['otp_code'] = $otp;
		$data['logo'] = site_setting(1, 4);

		\Session::forget('password_code');
		\Session::put('password_code', $data['otp_code']);

		\Mail::send('emails.forgot_password', $data, function ($message) use ($email, $data) {
			$message->to($email, '')->subject($data['subject']);
		});

		return true;
	}
}

//Estimation for Delivery the order

if (!function_exists('est_time')) {

	function est_time($preparation, $delivery) {

		$secs = strtotime($preparation) - strtotime("00:00:00");
		$result = date("H:i:s", strtotime($delivery) + $secs);
		$secs = strtotime($result) - strtotime("00:00:00");
		$est_time = date("H:i:s", time() + $secs);

		return $est_time;
	}
}

if (!function_exists('buildExcelFile')) {

	function buildExcelFile($filename, $data, $width = array()) {
		/** @var \Maatwebsite\Excel\Excel $excel */
		$excel = app('excel');
		//dd($data);
		// $filename = "export_1527660200";

		$excel->getDefaultStyle()
			->getAlignment()
			->setHorizontal('left');
		foreach ($data as $key => $array) {
			foreach ($array as $k => $v) {
				if (!$v) {
					$data[$key][$k] = '0';
				}
			}
		}

		//dd($filename, $data, $width);
		return $excel->create($filename, function (LaravelExcelWriter $excel) use ($data, $width) {
			$excel->sheet('exported-data', function (LaravelExcelWorksheet $sheet) use ($data, $width) {
				//$sheet->fromArray($data, null, 'A2', false, false);
				$sheet->fromArray($data)->setWidth($width);
				$sheet->setAllBorders('thin');
			});
		});
	}
}

if (!function_exists('total_count_card')) {
	function total_count_card() {
		$user_details = auth()->guard('web')->user();
		if ($user_details) {
			$order_data = get_user_order_details('', $user_details->id);
		} else {
			$order_data = get_user_order_details();
		}
		if (isset($order_data['total_item_count'])) {
			return $order_data['total_item_count'];
		}

	}
}

if (!function_exists('get_booking_fee')) {
	function get_booking_fee($subtotal = 0) {
		$booking_percentage = site_setting('booking_fee');
		return number_format_change($subtotal * $booking_percentage / 100);
	}
}
if (!function_exists('calculate_tax')) {
	function calculate_tax($amount, $tax) {

		return number_format_change($amount * $tax / 100);
	}
}

if (!function_exists('get_delivery_fee')) {
	function get_delivery_fee($res_lat, $res_long) {

		if (site_setting('delivery_fee_type') == 0) {

			return number_format_change(site_setting('delivery_fee'));

		} else {

			$user_location = user_address_details();
			$pickup_fare = site_setting('pickup_fare');
			$drop_fare = site_setting('drop_fare');
			$distance_fare = site_setting('distance_fare');

			$lat1 = $res_lat;
			$lat2 = $user_location['latitude'];
			$long1 = $res_long;
			$long2 = $user_location['longitude'];

			$result = get_driving_distance($lat1, $lat2, $long1, $long2);

			$km = round(floor(@$result['distance'] / 1000) . '.' . floor(@$result['distance'] % 1000));

			return number_format_change($pickup_fare + $drop_fare + ($km * $distance_fare));
		}
	}
}
if (!function_exists('add_order_data')) {

	function add_order_data() {

		if (session('order_data') != null) {

			$store_id = Session::get('order_data')['store_id'];

			$store_detail = Store::find($store_id);

			$order_data = session('order_data');
			$schedule_data = session::get('schedule_data');
			$schedule_status = 0;
			$schedule_datetime = null;
			if ($schedule_data['status'] == 'Schedule') {
				$schedule_status = 1;
				$schedule_datetime = $schedule_data['date'] . ' ' . $schedule_data['time'];
			}
			$return_order_data = [];
			$data_id = '';
			$user_id = get_current_login_user_id();

			//create or update order
			$order = Order::where('user_id', $user_id)->status()->first();
			if ($order) {
				$order->order_item()->delete();
			} else {
				$order = new Order;
			}

			$order->store_id = $store_id;
			$order->user_id = $user_id;
			$order->currency_code = $store_detail->currency_code;
			$order->schedule_status = $schedule_status;
			$order->schedule_time = $schedule_datetime;
			$order->status = 0;
			$order->save();

			foreach ($order_data['items'] as $item) {

				$item_id = $item['item_id'];
				$menu_item = MenuItem::find($item_id);
				$price_sum = $menu_item->price;
				$price_tot = number_format_change($item['item_count'] * $price_sum);

				if ($menu_item->menu->menu_closed == 1) {
					$orderitem = new OrderItem;
					$orderitem->order_id = $order->id;
					$orderitem->menu_item_id = $menu_item->id;
					$orderitem->price = $menu_item->price;
					$orderitem->quantity = $item['item_count'];
					$orderitem->notes = $item['item_notes'];
					$orderitem->total_amount = $item['item_count'] * $menu_item->price;
					$orderitem->tax = calculate_tax($price_tot, $menu_item->tax_percentage);
					$orderitem->save();

				}

			}
			$order_detail_data = clone $order;
			$subtotal = number_format($order_detail_data->order_item->sum('total_amount'), 2, '.', '');
			$order_tax = $order_detail_data->order_item->sum('tax');
			$booking_fee = get_booking_fee($subtotal);
			$delivery_fee = get_delivery_fee($store_detail->user_address->latitude, $store_detail->user_address->longitude);

			$order_detail_data->subtotal = $subtotal;
			$order_detail_data->tax = $order_tax;
			$order_detail_data->booking_fee = $booking_fee;
			$order_detail_data->delivery_fee = $delivery_fee;
			$order_detail_data->total_amount = $subtotal + $order_tax + $booking_fee + $order_detail_data->delivery_fee;
			$order_detail_data->save();

			$user_id = get_current_login_user_id();
			//create user_address
			if ($user_id) {
				$user_address = UserAddress::where('user_id', $user_id)->first();
				if (!$user_address) {
					$user_address = new UserAddress;
					$user_address->user_id = $user_id;
					$user_address->default = 1;
					$user_address->type = 0;
				}
				$country_name = '';
				if (session('country')) {
					$country_name = Country::where('code', session('country'))->first()->name;
				}
				$user_address->address = session('country');
				$user_address->street = session('address1');
				$user_address->city = session('city');
				$user_address->state = session('state');
				$user_address->country = session('country');
				$user_address->country_code = session('country');
				$user_address->postal_code = session('postal_code');
				$user_address->latitude = session('latitude');
				$user_address->longitude = session('longitude');

				$user_address->save();
			}
			$order_delivery = OrderDelivery::where('order_id', $order->id)->first();
			if (!$order_delivery) {
				$order_delivery = new OrderDelivery;
			}

			$order_delivery->order_id = $order_detail_data->id;
			$order_delivery->save();

			if (site_setting('delivery_fee_type') == 0) {

				$delivery_fee = site_setting('delivery_fee');

				$order_delivery->fee_type = 0;
				$order_delivery->total_fare = $delivery_fee;
				$order_delivery->save();

			} else {

				$pickup_fare = site_setting('pickup_fare');
				$drop_fare = site_setting('drop_fare');
				$distance_fare = site_setting('distance_fare');

				$lat1 = $order_detail_data->user_location[0]['latitude'];
				$lat2 = $order_detail_data->user_location[1]['latitude'];
				$long1 = $order_detail_data->user_location[0]['longitude'];
				$long2 = $order_detail_data->user_location[1]['longitude'];

				$result = get_driving_distance($lat1, $lat2, $long1, $long2);

				$km = round(floor(@$result['distance'] / 1000) . '.' . floor(@$result['distance'] % 1000));

				$delivery_fee = $pickup_fare + $drop_fare + ($km * $distance_fare);

				$order_delivery->fee_type = 0;
				$order_delivery->pickup_fare = $pickup_fare;
				$order_delivery->drop_fare = $drop_fare;
				$order_delivery->distance_fare = $distance_fare;
				$order_delivery->drop_distance = $km;
				$order_delivery->save();
			}

			Session::forget('order_data');
			return 'success';
		}
	}

}
if (!function_exists('get_user_order_details')) {

	function get_user_order_details($store_id = null, $user_id = null) {

		$order_data = [];
		if ($user_id) {
			$order = Order::where('user_id', $user_id)->status('cart')->first();

			//check store is open or not
			if (isset($order->store->store_all_time[0])) {
				$is_available = $order->store->store_all_time[0]->is_available;
			} else {
				$is_available = 0;
			}

			if ($is_available != 1) {
				Session::forget('order_data');
				return '';
			}
			if ($order != '') {
				$order_detail_data = clone $order;
				foreach ($order_detail_data->order_item as $value) {

					if ($order->store->status != 0 && $value->menu_item->menu->menu_closed == 1 && $value->menu_item->status == 1 && $value->menu_item->is_visible == 1) {
						$price_sum = ($value->menu_item->offer_price != 0) ? $value->menu_item->offer_price : $value->price;
						$price_tot = $value->quantity * $price_sum;

						$order_data[] = array('order_item_id' => $value->id, 'name' => $value->menu_item->name, 'item_notes' => $value->notes, 'item_id' => $value->menu_item_id, 'item_count' => $value->quantity, 'tax' => calculate_tax($price_tot, $value->menu_item->tax_percentage), 'item_total' => number_format_change($price_tot), 'item_price' => $price_sum);
					} else {
						$value->delete();
					}
				}
				$order_detail_data = clone $order;
				$subtotal = number_format_change($order_detail_data->order_item->sum('total_amount'));
				$order_tax = $order_detail_data->order_item->sum('tax');
				$order_quantity = $order_detail_data->order_item->sum('quantity');
				$booking_fee = get_booking_fee($subtotal);
				$total_count = $order_detail_data->order_item->sum('quantity');
				$delivery_fee = get_delivery_fee($order->store->user_address->latitude, $order->store->user_address->longitude);

				$penalty = $order_detail_data->user->penalty ? $order_detail_data->user->penalty->remaining_amount : 0;
				$order_detail_data->subtotal = $subtotal;
				$order_detail_data->tax = $order_tax;
				$order_detail_data->booking_fee = $booking_fee;
				$order_detail_data->delivery_fee = $delivery_fee;
				$order_detail_data->total_amount = $subtotal + $order_tax + $booking_fee + $order_detail_data->delivery_fee;
				$order_detail_data->save();
				$promo_amount = promo_calculation();
				$promo_total_amount = $order_detail_data->total_amount - $promo_amount;
				$promo_total_amount = $promo_total_amount + $penalty;
				$order_detail_data->total_amount = $promo_total_amount > 0 ? $promo_total_amount : 0;
				if ($total_count > 0) {
					return array('order_id' => $order->id, 'store_id' => $order_detail_data->store_id, 'items' => $order_data, 'total_price' => $order_detail_data->total_amount, 'delivery_fee' => number_format_change($order_detail_data->delivery_fee), 'booking_fee' => $order_detail_data->booking_fee, 'tax' => $order_detail_data->tax, 'subtotal' => number_format_change($order_detail_data->subtotal), 'total_item_count' => $total_count, 'promo_amount' => $promo_amount, 'penalty' => $penalty);
				}
                  
                  if($order->order_delivery)
                  {
                    	$order->order_delivery->delete();
                    	
                  }

                   if($order->payment)
                  {
                    	$order->payment->delete();                    	
                  }

				   $order->delete();

			
				return '';

			}
		} else {

			if (session('order_data') != null) {

				$store_id = $store_id ? $store_id : Session::get('order_data')['store_id'];

				$store_detail = Store::find($store_id);

				$order_data = session('order_data');
				$return_order_data = [];
				$data_id = '';
				if (isset($store_detail->store_all_time[0])) {
					$is_available = $store_detail->store_all_time[0]->is_available;
				} else {
					$is_available = 0;
				}

				if ($is_available != 1) {
					Session::forget('order_data');
					return '';
				}
				foreach ($order_data['items'] as $item) {

					$item_id = $item['item_id'];
					$menu_item = MenuItem::find($item_id);
					if ($menu_item->menu->menu_closed == 1 && $menu_item->status != 0 && $menu_item->is_visible == 1 && $store_detail->status != 0) {
						$price_sum = $menu_item->offer_price != 0 ? $menu_item->offer_price : $menu_item->price;
						$price_tot = number_format_change($item['item_count'] * $price_sum);
						$return_order_data[] = array('name' => $menu_item->name, 'item_notes' => $item['item_notes'], 'item_id' => $menu_item->id, 'item_count' => $item['item_count'], 'tax' => calculate_tax($price_tot, $menu_item->tax_percentage), 'item_total' => $price_tot, 'item_price' => $price_sum);
					}
				}
				$delivery_fee = get_delivery_fee($store_detail->user_address->latitude, $store_detail->user_address->longitude);

				$subtotal = array_sum(array_map(function ($item) {
					return $item['item_total'];
				}, $return_order_data));
				$total_count = array_sum(array_map(function ($item) {
					return $item['item_count'];
				}, $return_order_data));
				$tax = array_sum(array_map(function ($item) {
					return $item['tax'];
				}, $return_order_data));
				$subtotal = number_format_change($subtotal);
				$tax = number_format_change($tax);
				$booking_fee = number_format_change(get_booking_fee($subtotal));
				$total = number_format_change($subtotal + $tax + $booking_fee + $delivery_fee);
				if ($total_count > 0) {
					$data =  array('store_id' => $order_data['store_id'], 'items' => $return_order_data, 'total_price' => $total, 'delivery_fee' => $delivery_fee, 'booking_fee' => $booking_fee, 'tax' => $tax, 'subtotal' => $subtotal, 'total_item_count' => $total_count);
					Session::put('order_data', $data);
					return $data;
				} else {
					Session::forget('order_data');
					return '';
				}

			}
		}
	}

}

if (!function_exists('schedule_data_update')) {

	function schedule_data_update($update = '') {
		// auto update schedule details
		$schedule_data = session('schedule_data');
		if ($schedule_data) {
			if ($schedule_data['status'] == 'Schedule') {
				$schedule_time = $schedule_data['date'] . ' ' . $schedule_data['time'];
				if (strtotime($schedule_time) < time() || $update != '') {
					$schedule_data = array('status' => 'ASAP', 'date' => '', 'time' => '');
					session::put('schedule_data', $schedule_data);
					$schedule_update = UserAddress::where('user_id', get_current_login_user_id())->default()->first();
					if ($schedule_update) {
						$schedule_update->delivery_time = '';
						$schedule_update->delivery_options = '';
						$schedule_update->order_type = '';
						$schedule_update->save();
					}
				}
			}
		}
	}
}
if (!function_exists('numberFormat')) {

	function numberFormat($amount) {

		return number_format($amount, 2, '.', '');

	}
}

if (!function_exists('priceRatingList')) {

	function priceRatingList() {
		$symbol = Currency::where('code', site_setting('default_currency'))->first()->original_symbol;
		$array[1] = $symbol;
		$array[2] = $symbol . $symbol;
		$array[3] = $symbol . $symbol . $symbol;
		$array[4] = $symbol . $symbol . $symbol . $symbol;
		return $array;

	}
}

if (!function_exists('default_currency_symbol')) {

	function default_currency_symbol() {
		$symbol = Currency::where('code', site_setting('default_currency'))->first()->original_symbol;

		return $symbol;

	}
}

if (!function_exists('check_menu_available')) {

	function check_menu_available($order_id, $date) {

		$timestamp = strtotime($date);

		$day = date('N', $timestamp);
		$time = date('h:i a', strtotime($date));

		$order_item = OrderItem::where('order_id', $order_id)->get();

		$unavailable = [];

		foreach ($order_item as $menu_item) {

			$menu = MenuItem::where('id', $menu_item->menu_item_id)->first();

			$store_time = StoreTime::where('day', $day)->where('store_id', $menu->menu->store_id)->where('status', 1)->first();

			if ($store_time) {

				$atleast = MenuTime::where('menu_id', $menu->menu_id)->first();

				$menu_time = MenuTime::where('day', $day)->where('menu_id', $menu->menu_id)->first();

				if ($menu_time) {

					if (strtotime($time) >= strtotime($menu_time->start_time) &&
						strtotime($time) <= strtotime($menu_time->end_time) && $menu->is_visible == 1) {

					} else {

						$unavailable[] = array('id' => $menu_item->id, 'name' => $menu->name, 'order_id' => $menu_item->order_id);
					}
				} else if ($atleast) {

					$unavailable[] = array('id' => $menu_item->id, 'name' => $menu->name, 'order_id' => $menu_item->order_id);

				} else {

					if (isset($store_time)) {
						
						
						if (strtotime($time) >= strtotime($store_time->start_time_for_english) &&
							strtotime($time) <= strtotime($store_time->end_time_for_english) && $menu->is_visible == 1) {

						} else {

							$unavailable[] = array('id' => $menu_item->id, 'name' => $menu->name, 'order_id' => $menu_item->order_id);

						}

					} else {

						$unavailable[] = array('id' => $menu_item->id, 'name' => $menu->name, 'order_id' => $menu_item->order_id);

					}

				}

			} else {

				$unavailable[] = array('id' => $menu_item->id, 'name' => $menu->name, 'order_id' => $menu_item->order_id);

			}
		}

		if (count($unavailable) > 0) {
			$input = array_map("unserialize", array_values(array_unique(array_map("serialize", $unavailable))));
		} else {
			$input = [];
		}

		return $input;
	}
}

if (!function_exists('store_search')) {

	function store_search($user_details, $address_details, $search = '') {
		// dd($address_details);

		$latitude = isset($address_details['latitude']) ? $address_details['latitude'] : '';
		$longitude = isset($address_details['longitude']) ? $address_details['longitude'] : '';
		$order_type = $address_details['order_type'];
		$delivery_time = $address_details['delivery_time'];

		$store = Store::where(
			function ($q) use ($search) {
				$q->whereHas(
					'store_category',
					function ($q) use ($search) {
						$q->WhereHas(
							'category',
							function ($q) use ($search) {
								$q->where('name', 'like', '%' . $search . '%')
								->orWhereHas('language_category',function($q) use ($search){
									$q->where('name', 'like', '%' . $search . '%')
									->where('locale',Session::get('language'));
								});
							}
						);
					}
				)->orWhereHas(
					'store_menu',
					function ($q) use ($search) {
						$q->WhereHas(
							'menu_category',
							function ($q) use ($search) {
								$q->WhereHas(
									'menu_item',
									function ($q) use ($search) {
										$q->where('name', 'like', '%' . $search . '%')
										->orWhereHas('language_menu',function($q) use ($search){
											$q->where('name', 'like', '%' . $search . '%')
											->where('locale',Session::get('language'));
										});
									}
								);
							}
						);
					}
				);
			}
		)->orWhere('name', 'like', '%' . $search . '%')->UserStatus()->location($latitude, $longitude)
			->whereHas('store_time', function ($query) {

			})->get()->pluck('id');

		$user = Store::where(
			function ($query) {
				$query->with(['store_category', 'store_time']);
			}
		)->whereIn('id', $store)->get();

		$user = $user->map(
			function ($item) use ($user_details, $delivery_time, $order_type) {
				$store_category = $item['store_category']->map(
					function ($item) {
						return $item['category_name'];
					}
				)->toArray();

				$return_data = [

					'order_type' => $order_type,
					'delivery_time' => $delivery_time,
					'store_id' => $item['id'],
					'name' => $item['name'],
					'category' => implode(',', $store_category),
					'banner' => $item['banner'],
					'min_time' => $item['convert_mintime'],
					'max_time' => $item['convert_maxtime'],
					'store_rating' => $item['review']['store_rating'],
					'price_rating' => $item['price_rating'],
					'average_rating' => $item['review']['average_rating'],

					'status' => $item['status'],
					'store_open_time' => $item['store_time']['start_time'],
					'store_closed' => $item['store_time']['closed'],
					'store_next_time' => $item['store_next_opening'],
					'store_offer' => $item['store_offer']->map(

						function ($item) {

							return [

								'title' => $item->offer_title,
								'description' => $item->offer_description,
								'percentage' => $item->percentage,

							];
						}
					),

				];
				if ($user_details) {
					$return_data['wished'] = $item->wishlist($user_details->id, $item['id']);
				}

				return $return_data;
			}
		);

		return response()->json(
			[

				'status_message' => "Success",

				'status_code' => '1',

				'category' => $user,

				'count' => count($user),

			]
		);
	}

}

if (!function_exists('user_address_details')) {
	function user_address_details() {

		if (auth()->guard('web')->user()) {

			$user_details = auth()->guard('web')->user();

			$user = User::where('id', $user_details->id)->first();
			if ($user->user_address) {
				return list('latitude' => $latitude, 'longitude' => $longitude, 'order_type' => $order_type, 'delivery_time' => $delivery_time) =
				collect($user->user_address)->only(['latitude', 'longitude', 'order_type', 'delivery_time'])->toArray();
			} else {
				$session = Session::all();
				$session['order_type'] = $session['schedule_data']['status'];
				$session['delivery_time'] = $session['schedule_data']['status'] == 'Schedule' ? $session['schedule_data']['date'] . ' ' . $session['schedule_data']['time'] : '';
				return $session;
			}
		} else {

			$session = Session::all();
			$session['order_type'] = $session['schedule_data']['status'];
			$session['delivery_time'] = $session['schedule_data']['status'] == 'Schedule' ? $session['schedule_data']['date'] . ' ' . $session['schedule_data']['time'] : '';
			return $session;
		}
	}
}

if (!function_exists('menu_category')) {

	function menu_category($key) {
		$data['most_popular'] = Category::Active()->where('most_popular', '1')->get();
		$data['recommended'] = Category::Active()->where('is_top', '1')->get();
		return $data[$key];

	}
}
if (!function_exists('penality')) {

	function penality($order_id) {

		$penality_amount = 0;

		$user = User::find(get_current_login_user_id());
		$order = Order::find($order_id);
		$penality = Penality::where('user_id', get_current_login_user_id())->first();

		if ($penality) {

			if ($penality->remaining_amount != 0) {

				$penality_amount = $penality->remaining_amount;

				$penality_apply_order = PenalityDetails::where('order_id', $order_id)->first();

				if ($penality_apply_order) {

					if ($user->type == 0) {

						$penality_apply_order->previous_user_penality = $penality_amount;

					} else if ($user->type == 1) {

						$store_total = $order->subtotal + $order->tax - $order->store_commision_fee;

						if ($penality_amount >= $store_total) {

							$penality_apply_order->previous_store_penality = $store_total;

							$remaining_penality = $penality_amount - $store_total;

							//penality table

							$penality->remaining_amount = $remaining_penality;
							$penality->paid_amount = $penality->paid_amount + $store_total;
							$penality->save();

							$penality_amount = $store_total;

						} else {

							$penality_apply_order->previous_store_penality = $penality_amount;

							//penality table
							$penality->remaining_amount = 0;
							$penality->paid_amount = $penality->paid_amount + $penality_amount;
							$penality->save();
						}

					} else {
						$penality_apply_order->previous_driver_penality = $penality_amount;
					}

				} else {

					$penality_apply_order = new PenalityDetails;

					if ($user->type == 0) {

						$penality_apply_order->previous_user_penality = $penality_amount;

					} else if ($user->type == 1) {

						$store_total = $order->subtotal + $order->tax - $order->store_commision_fee;

						if ($penality_amount >= $store_total) {

							$penality_apply_order->previous_store_penality = $store_total;

							$remaining_penality = $penality_amount - $store_total;

							//penality table

							$penality->remaining_amount = $remaining_penality;
							$penality->paid_amount = $penality->paid_amount + $store_total;
							$penality->save();

							$penality_amount = $store_total;

						} else {

							$penality_apply_order->previous_store_penality = $penality_amount;

							//penality table

							$penality->remaining_amount = 0;
							$penality->paid_amount = $penality->paid_amount + $penality_amount;
							$penality->save();
						}

					} else {
						$penality_apply_order->previous_driver_penality = $penality_amount;
					}

					$penality_apply_order->order_id = $order_id;

				}

				$penality_apply_order->save();

			}

		}

		return $penality_amount;

	}
}
if (!function_exists('revertPenality')) {

	function revertPenality($order_id) {

		//Revert Penality amount if exists

		$order = Order::find($order_id);

		$revert_penality = PenalityDetails::where('order_id', $order->id)->first();

		if ($revert_penality) {

			$user_penality = Penality::where('user_id', $order->user_id)->first();

			if ($user_penality) {

				$user_penality->remaining_amount = $revert_penality->previous_user_penality + $user_penality->remaining_amount;
				$user_penality->paid_amount = abs($revert_penality->previous_user_penality - $user_penality->paid_amount);

				$user_penality->save();

				if ($order->total_amount != 0 && $order->payment_type == 0) {

					if ($order->total_amount >= $revert_penality->previous_user_penality) {

						$order->total_amount = $order->total_amount - $revert_penality->previous_user_penality;
						$order->save();

					}

				}
			}

			// store penality

			$store_user_id = get_store_user_id($order->store_id);

			$store_penality = Penality::where('user_id', $store_user_id)->first();

			if ($store_penality) {

				$store_penality->remaining_amount = $revert_penality->previous_store_penality + $store_penality->remaining_amount;
				$store_penality->paid_amount = abs($revert_penality->previous_store_penality - $store_penality->paid_amount);

				$store_penality->save();
			}

			// Driver penality

			if ($order->driver_id) {

				$get_driver_user_id = get_store_user_id($order->driver_id);
				$owe_amount = DriverOweAmount::where('user_id', $get_driver_user_id)->first();

				if ($owe_amount) {

					$owe_amount->amount = $owe_amount->amount + $revert_penality->previous_driver_penality;

				} else {

					$owe_amount = new DriverOweAmount;
					$owe_amount->amount = $revert_penality->previous_driver_penality;

				}

				$owe_amount->save();

			}

			$revert_penality->delete();

		}

	}
}
//session clear for menu's

if (!function_exists('session_clear_all_data')) {
	function session_clear_all_data() {

		$session_data = session::get('order_data');
		$user_id = get_current_login_user_id();

		if ($user_id) {
			$order = Order::where('user_id', $user_id)->status('cart')->first();
			$order->order_item()->delete();
			$order->order_delivery()->delete();
			$order->delete();
		} else {
			Session::forget('order_data');
		}

		return 'success';

	}
}