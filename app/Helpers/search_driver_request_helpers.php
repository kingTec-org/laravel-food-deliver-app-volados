<?php

use App\Models\Driver;
use App\Models\DriverRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;	

if (!function_exists("searchDrivers")) {

	function searchDrivers($order_id) {

		$order = Order::where('id', $order_id)->first();

		$store = $order->store;
		// dD($order->user, $order->user->user_address);

		list('latitude' => $pickup_latitude, 'longitude' => $pickup_longitude, 'address' => $pickup_location) = collect($order->store->user_address)->only(['latitude', 'longitude', 'address'])->toArray();

		list('latitude' => $drop_latitude, 'longitude' => $drop_longitude, 'address' => $drop_location) = collect($order->user->user_address)->only(['latitude', 'longitude', 'address'])->toArray();

		$group_id = $order->id . time();

		// $this->search_and_send_request_to_driver($order->id, $group_id, $pickup_latitude, $pickup_longitude, $pickup_location, $drop_latitude, $drop_longitude, $drop_location);
		// SearchRequestDriver::dispatch($order->id, $group_id, $pickup_latitude, $pickup_longitude, $pickup_location, $drop_latitude, $drop_longitude, $drop_location);
		searchRequestDriver($order->id, $group_id, $pickup_latitude, $pickup_longitude, $pickup_location, $drop_latitude, $drop_longitude, $drop_location);
	}
}

if (!function_exists("searchRequestDriver")) {

	/**
	 * To search and send request to the driver
	 *
	 * @param  integer $order            Id of the order
	 * @param  integer $group_id         Group id
	 * @param  string  $pickup_latitude  Pickup latitude
	 * @param  string  $pickup_longitude Pickup longitude
	 * @param  string  $pickup_location  Pickup location
	 * @param  string  $drop_latitude    Drop latitude
	 * @param  string  $drop_longitude   Drop longitude
	 * @param  string  $drop_location    Drop location
	 * @return null                      Empty
	 */
	function searchRequestDriver($order_id, $group_id, $pickup_latitude, $pickup_longitude, $pickup_location, $drop_latitude, $drop_longitude, $drop_location) {

		clearPending(); 
		
		$order = Order::where('id', $order_id)->first();
		$driver_request = new DriverRequest;
		$driver_search_radius = site_setting('driver_km');
		$sleep_time = 15;
		$active = true;

		if ($order->driver_id && $order->driver) {
			\Log::info("Request already accepted : " . $order->id);
			return;
		}

		$drivers = Driver::search($pickup_latitude, $pickup_longitude, $driver_search_radius, $group_id)->get();
		Log::info('check driver...'.$drivers->count());
		if ($drivers->count() == 0) {

			\Log::info("Sorry, No drivers found. : " . $order->id);

			$support_mobile = site_setting('site_support_phone');
			$store = $order->store->user;
			$push_notification_title = trans('api_messages.orders.no_drivers_found') . $order->id;
			$push_notification_data = [
				'type' => 'no_drivers_found',
				'order_id' => $order->id,
				'support_mobile' => $support_mobile,
			];

			push_notification($store->device_type, $push_notification_title, $push_notification_data, $store->type, $store->device_id);

			return false;
		}

		$nearest_driver = $drivers->first();

		$request_already = DriverRequest::where('driver_id', $nearest_driver->id)->where('group_id', $group_id)->get()->count();

		if (!$request_already) {

			$last_second = DriverRequest::where('driver_id', $nearest_driver->id)->where('status', '0')->get()->count();

			if (!$last_second) {

				$driver_request->order_id = $order->id;
				$driver_request->group_id = $group_id;
				$driver_request->driver_id = $nearest_driver->id;
				$driver_request->pickup_latitude = $pickup_latitude;
				$driver_request->pickup_longitude = $pickup_longitude;
				$driver_request->drop_latitude = $drop_latitude;
				$driver_request->pickup_location = $pickup_location;
				$driver_request->drop_longitude = $drop_longitude;
				$driver_request->drop_location = $drop_location;
				$driver_request->status = $driver_request->statusArray['pending'];
				$driver_request->save();

				$push_notification_title = "New order request data.";
				$driving_distance = get_driving_distance($nearest_driver->latitude, $pickup_latitude, $nearest_driver->longitude, $pickup_longitude);

				if ($driving_distance['status'] == 'fail') {

					return response()->json(
						[
							'status' => '0',
							'messages' => $driving_distance['msg'],
							'status_message' => 'Some technical issue contact admin',
						]);

				}
				$get_near_time = round(floor(round($driving_distance['time'] / 60)));

				$push_notification_data = [

					'type' => 'order_request',
					'request_id' => $driver_request->id,
					'request_data' => [
						'request_id' => $driver_request->id,
						'order_id' => $order->id,
						'pickup_location' => $pickup_location,
						'min_time' => $get_near_time,
						'pickup_latitude' => $pickup_latitude,
						'pickup_longitude' => $pickup_longitude,
						'store' => $order->store->name,
					],
				];

				push_notification($nearest_driver->user->device_type, $push_notification_title, $push_notification_data, $nearest_driver->user->type, $nearest_driver->user->device_id, true);

			} else {

				searchRequestDriver($order_id, $group_id, $pickup_latitude, $pickup_longitude, $pickup_location, $drop_latitude, $drop_longitude, $drop_location);

			}

		}

		$nexttick = time() + $sleep_time;

		while ($active) {

			if (time() >= $nexttick) {

				if ($driver_request) {

					$driver_request = DriverRequest::where('id', $driver_request->id)->first();

					if ($driver_request) {

						if ($driver_request->status_text == 'pending') {

							$driver_request->status = $driver_request->statusArray["cancelled"];
							$driver_request->save();
							searchRequestDriver($order_id, $group_id, $pickup_latitude, $pickup_longitude, $pickup_location, $drop_latitude, $drop_longitude, $drop_location);

						}
					}
				}
				$drivers = Driver::search($pickup_latitude, $pickup_longitude, $driver_search_radius, $group_id)->get();

				if ($drivers->count() == 0) {

					\Log::info("stop : " . $order->id);

					$active = false;
				}

			}

			$driver_accept = DriverRequest::where('group_id', $group_id)->where('status', '1')->first();

			if (count($driver_accept) > 0) {

				$order = Order::find($order_id);

				$order->status = $order->statusArray['delivery'];
				$order->delivery_at = date('Y-m-d H:i:s');
				$order->save();

				$user = $order->user;
				$push_notification_title = trans('api_messages.orders.your_item_preparation_done') . $order_id;
				$push_notification_data = [
					'type' => 'order_delivery_started',
					'order_id' => $order_id,
				];

				push_notification($user->device_type, $push_notification_title, $push_notification_data, $user->type, $user->device_id);

				$active = false;

			}

		}

	}

	function clearPending(){
	
		$request = DriverRequest::where('created_at', '<', Carbon::now()->subMinutes(2)->toDateTimeString())->where('status','0')->get();

        if($request)
        {
			foreach($request as $request_val)
			{

                 DriverRequest::where('id', $request_val->id)->update(['status' => '2']);

			}

	    }
  
	}
}
