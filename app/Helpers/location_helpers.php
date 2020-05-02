<?php

use App\Models\Order;

if (!function_exists('check_location')) {

	function check_location($order_id) {

		$order_details = Order::find($order_id);
		$store_id = get_store_user_id($order_details->store_id);
		$resaddress = get_store_address($store_id);
		$useraddress = get_user_address($order_details->user_id);
		list('latitude' => $pickup_latitude, 'longitude' => $pickup_longitude, 'address' => $pickup_location) = collect($useraddress)->only(['latitude', 'longitude', 'address'])->toArray();

		list('latitude' => $drop_latitude, 'longitude' => $drop_longitude, 'address' => $drop_location) = collect($resaddress)->only(['latitude', 'longitude', 'address'])->toArray();

		$km = distance($pickup_latitude, $pickup_longitude, $drop_latitude, $drop_longitude);

		$site_km = site_setting('store_km');
		if ($km <= $site_km) {
			return 1;
		} else {
			return 0;
		}

	}
}

if (!function_exists('check_store_location')) {

	function check_store_location($order_id = '', $user_lat, $user_long, $store_id = '') {

		if ($order_id != '') {

			$order_details = Order::find($order_id);

			$store_id = $order_details->store_id;
		}

		$store_user_id = get_store_user_id($store_id);
		$resaddress = get_store_address($store_user_id);

		list('latitude' => $res_latitude, 'longitude' => $res_longitude, 'address' => $drop_location) = collect($resaddress)->only(['latitude', 'longitude', 'address'])->toArray();

		$km = distance($res_latitude, $res_longitude, $user_lat, $user_long);

		$site_km = site_setting('store_km');
		if ($km <= $site_km) {
			return 1;
		} else {
			return 0;
		}

	}
}

function distance($lat1, $lon1, $lat2, $lon2) {

	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	$dist = acos($dist);
	$dist = rad2deg($dist);
	$miles = $dist * 60 * 1.1515;

	return ($miles * 1.609344);

}

?>