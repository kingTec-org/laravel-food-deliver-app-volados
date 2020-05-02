<?php

/**
 * PaymentController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Payment
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\OrderCancelReason;
use App\Models\Store;
use App\Models\StoreTime;
use App\Models\SiteSettings;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserPaymentMethod;
use App\Traits\FileProcessing;
use App\Traits\PlaceOrder;
use Auth;
use Illuminate\Http\Request;
use Session;
use Stripe;

class PaymentController extends Controller {
	use FileProcessing, PlaceOrder;

	//checkout detail page

	public function checkout() {

		$this->view_data['user_details'] = auth()->guard('web')->user();

		if (!isset($this->view_data['user_details']->id) && !session()->has('order_data')) {
			return redirect()->route('home');
		}

		$subtotal = 0;
		$tax = 0;
		if ($this->view_data['user_details']) {
			session::forget('url.intended');
			$this->view_data['payment_detail'] = UserPaymentMethod::where('user_id', $this->view_data['user_details']->id)->first();

			$this->view_data['order_id'] = Order::where('user_id', $this->view_data['user_details']->id)->status('cart')->first();
		}
		if (isset($this->view_data['user_details']->id)) {
			$already_cart = Order::where('user_id', $this->view_data['user_details']->id)->status('cart')->first();
			if ($already_cart) {

				/*$check = check_location($already_cart->id);
				if ($check != 1) {
					session_clear_all_data();
					return redirect()->route('location_not_found');
				}*/
				$store_id = $already_cart->store_id;
			} else {
				return redirect()->route('home');
			}

		} else {
			$order_data = session::get('order_data');
			$store_id = $order_data['store_id'];
		}
		$id = $store_id;

		$store = Store::find($id);

		$this->view_data['order_detail_data'] = get_user_order_details($store_id, @$this->view_data['user_details']->id);

		if (!isset($this->view_data['user_details']->id)) {
			$this->view_data['user_id'] = '';
			$this->view_data['order_details'] = '';
			Session::put('url.intended', 'checkout');
		}

		$store_time = StoreTime::where('store_id', $store_id)->first();

		if ($store_time) {
			$store_time_data1 = $store_time->is_available;
		} else {
			$store_time_data1 = 0;
		}

		if ($this->view_data['order_detail_data'] == '') {
			return redirect()->route('details', $id);
		}

		$this->view_data['schedule_data'] = session('schedule_data');
		$this->view_data['store_details'] = Store::find($id);
		$this->view_data['map_key'] = site_setting('google_api_key');

		return view('checkout', $this->view_data);
	}

	//web payment card details

	public function add_card_details(Request $request) {

		$user_details = auth()->guard('web')->user();

		$card_number = request()->card_number;
		$expire_month = request()->expire_month;
		$expire_year = request()->expire_year;
		$cvv_number = request()->cvv_number;
		$country_card = request()->country_card;
		$card_code = request()->card_code;

		try {

			$stripe_key = site_setting('stripe_secret_key');

			\Stripe\Stripe::setApiKey($stripe_key);

			//token genrate

			$stripe = \Stripe\Token::create(array(
				"card" => array(
					"number" => $card_number,
					"exp_month" => $expire_month,
					"exp_year" => $expire_year,
					"cvc" => $cvv_number,
				),
			));

			//customer id genrate

			$customer = \Stripe\Customer::create(
				array(
					"description" => "Customer for daniel.jones@example.com",
					"source" => $stripe->id, // obtained with Stripe.js
				)
			);

			$payment_details = UserPaymentMethod::where('user_id', $user_details->id)->first();

			if ($payment_details) {
				//already have pamyent details

				$customer_details = \Stripe\Customer::retrieve($customer->id);

				$payment_details->stripe_customer_id = $customer->id;
				$payment_details->brand = $customer_details->sources->data[0]['brand'];
				$payment_details->last4 = $customer_details->sources->data[0]['last4'];
				$payment_details->save();
			} else {
				//new pamyent details

				$customer_details = \Stripe\Customer::retrieve($customer->id);

				$payment_details = new UserPaymentMethod;
				$payment_details->user_id = $user_details->id;
				$payment_details->stripe_customer_id = $customer->id;
				$payment_details->brand = $customer_details->sources->data[0]['brand'];
				$payment_details->last4 = $customer_details->sources->data[0]['last4'];
				$payment_details->save();
			}

			$customer_details = \Stripe\Customer::retrieve($customer->id);

			$result = $customer_details->sources->data;

			return response()->json(
				[

					'status_message' => 'Successfully',

					'status_code' => '1',

					'brand' => $result[0]['brand'],

					'last4' => $result[0]['last4'],

					'payment_details' => $payment_details,

				]
			);
		} catch (\Exception $e) {
			return response()->json(
				[

					'status_message' => $e->getMessage(),

					'status_code' => '0',

				]
			);
		}
	}

	public function add_cart() {

		$schedule_data = session('schedule_data');
		$schedule_status = 0;
		$schedule_datetime = null;

		$menu_item_id = request()->menu_item_id;
		$store_id = request()->store_id;
		$quantity = request()->item_count;
		$notes = request()->item_notes;
		$menu_data = request()->menu_data;
		if ($schedule_data['status'] == 'Schedule') {
			$schedule_status = 1;
			$schedule_datetime = $schedule_data['date'] . ' ' . $schedule_data['time'];
		}

		$user_details = auth()->guard('web')->user();
		if ($user_details) {
			$menu = MenuItem::find($menu_data['id']);

			$already_cart = Order::where('user_id', $user_details->id)->status('cart')->first();

			if ($already_cart) {

				if ($already_cart->store_id != $store_id) {

					$order1 = Order::where('user_id', $user_details->id)->status('cart')->where('store_id', $already_cart->store_id)->first();
					$order1->order_item()->delete();
					$order1->order_delivery()->delete();
					$order1->delete();

				}

			}

			$order = Order::where('user_id', $user_details->id)->where('store_id', $store_id)->status('cart')->first();

			$store = Store::find($store_id);

			if ($order == '') {
				$order = new Order;
				$order->store_id = $store_id;
				$order->user_id = $user_details->id;
				$order->currency_code = $store->currency_code;
				$order->schedule_status = $schedule_status;
				$order->schedule_time = $schedule_datetime;
				$order->status = 0;
				$order->save();
			}
			$menu_price = $menu->price;
			$total_amount = ($quantity * $menu_price);
			$tax = ($total_amount * $menu->tax_percentage / 100);

			$orderitem = new OrderItem;

			$orderitem->order_id = $order->id;
			$orderitem->menu_item_id = $menu_data['id'];
			$orderitem->price = $menu_price;
			$orderitem->quantity = $quantity;
			$orderitem->notes = $notes;
			$orderitem->total_amount = $total_amount;
			$orderitem->tax = $tax;
			$orderitem->save();

			// update order or cart sum price and tax

			$orderitem = OrderItem::where('order_id', $order->id)->get();

			$order_update = Order::find($order->id);

			$order_delivery = $order_update->order_delivery;
			//dd($order_delivery);
			if (!$order_delivery) {

				$order_delivery = new OrderDelivery;
				$order_delivery->order_id = $order_update->id;
				$order_delivery->save();
			}

			//dd($order_delivery);

			if (site_setting('delivery_fee_type') == 0) {

				$delivery_fee = site_setting('delivery_fee');
				$order_update->delivery_fee = $delivery_fee;

				$order_delivery->fee_type = 0;
				$order_delivery->total_fare = $delivery_fee;
				$order_delivery->save();

			} else {

				$pickup_fare = site_setting('pickup_fare');
				$drop_fare = site_setting('drop_fare');
				$distance_fare = site_setting('distance_fare');

				$lat1 = $order_update->user_location[0]['latitude'];
				$lat2 = $order_update->user_location[1]['latitude'];
				$long1 = $order_update->user_location[0]['longitude'];
				$long2 = $order_update->user_location[1]['longitude'];

				$result = get_driving_distance($lat1, $lat2, $long1, $long2);

				$km = round(floor($result['distance'] / 1000) . '.' . floor($result['distance'] % 1000));

				$delivery_fee = $pickup_fare + $drop_fare + ($km * $distance_fare);

				$order_delivery->fee_type = 0;
				$order_delivery->pickup_fare = $pickup_fare;
				$order_delivery->drop_fare = $drop_fare;
				$order_delivery->distance_fare = $distance_fare;
				$order_delivery->drop_distance = $km;
				$order_delivery->est_distance = $km;
				$order_delivery->total_fare = $delivery_fee;
				$order_delivery->save();
			}

			$subtotal = number_format_change($orderitem->sum('total_amount'));
			$order_tax = $orderitem->sum('tax');
			$order_quantity = $orderitem->sum('quantity');
			$booking_percentage = SiteSettings::where('name', 'booking_fee')->first()->value;
			$booking_fee = ($subtotal * $booking_percentage / 100);

			$order_update->subtotal = $subtotal;
			$order_update->tax = $order_tax;
			$order_update->booking_fee = $booking_fee;
			$order_update->delivery_fee = $delivery_fee;
			$order_update->wallet_amount = 0;
			$order_update->owe_amount = 0;
			$order_update->save();

			$offer_amount = offer_calculation($store_id, $order->id);

			$promo_amount = promo_calculation();

			$data = get_user_order_details($store_id, $user_details->id);
			return response()->json(
				[

					'success' => 'true',

					'cart_detail' => $data,

				]
			);
		} else {
			$data = $this->add_to_session_cart($store_id, $quantity, $notes, $menu_data);
			session::forget('order_data');
			session::put('order_data', $data);
			return response()->json(
				[

					'success' => 'true',

					'cart_detail' => $data,

				]
			);
		}
	}

	public function add_to_session_cart($store_id, $quantity, $notes, $menu_data) {
		// dd($store_id,$quantity,$notes,$menu_data);
		$store_detail = Store::find($store_id);
		$delivery_fee = get_delivery_fee($store_detail->user_address->latitude, $store_detail->user_address->longitude);

		$cart_detail = session('order_data');
		$price_sum = $menu_data['offer_price'] > 0 ? $menu_data['offer_price'] : $menu_data['price'];
		$price_tot = $price_sum * $quantity;
		$tax = calculate_tax($price_tot, $menu_data['tax_percentage']);
		@$cart_detail['store_id'] = @$store_id;
		@$cart_detail['delivery_fee'] = $delivery_fee;
		@$cart_detail['tax'] = @$cart_detail['tax'] + $tax;
		@$cart_detail['subtotal'] = @$cart_detail['subtotal'] + $price_tot;
		@$cart_detail['booking_fee'] = get_booking_fee(@$cart_detail['subtotal']);
		@$cart_detail['total_price'] = number_format_change(@$cart_detail['total_price'] + $price_tot + $tax+@$cart_detail['booking_fee']);
		@$cart_detail['total_item_count'] = @$cart_detail['total_item_count'] + $quantity;
		@$cart_detail['items'][] = array('name' => $menu_data['name'], 'item_notes' => $notes, 'item_id' => $menu_data['id'], 'item_count' => $quantity, 'tax' => $tax, 'item_total' => $price_tot, 'item_price' => $price_sum);
		return $cart_detail;
	}

	//order data store

	public function place_order_details() {

		$city = request()->confirm_address;

		$street = request()->street;
		$order_city = request()->city;
		$state = request()->state;
		$country = request()->country;
		$postal_code = request()->postal_code;
		$latitude = request()->latitude;
		$longitude = request()->longitude;
		$suite = request()->suite;
		$delivery_note = request()->delivery_note;
		$payment_method = request()->payment_method;
		$order_note = request()->order_note;

		$user_id = get_current_login_user_id();

		if ($user_id) {

			$order = Order::where('user_id', $user_id)->status('cart')->first();

			$schedule_data = session::get('schedule_data');

			$schedule_status = 0;
			$schedule_datetime = null;

			if ($schedule_data['status'] == 'Schedule') {

				$schedule_status = 1;
				$schedule_datetime = $schedule_data['date'] . ' ' . $schedule_data['time'];
			}

			$order->schedule_status = $schedule_status;
			$order->schedule_time = $schedule_datetime;

			$order->notes = ($order_note) ? $order_note : null;

			$order->payment_type = $payment_method;

			$order->save();

			$this->static_map_track($order->id);

			//user address store

			$user_address = UserAddress::where('user_id', $user_id)->first();

			if (!$user_address) {
				$user_address = new UserAddress;
			}
			$user_address->default = 1;
			$user_address->type = 0;
			$user_address->user_id = $user_id;
			$user_address->address = $city;
			$user_address->street = ($street != null) ? $street : $order_city;
			$user_address->city = $order_city;
			$user_address->state = $state;
			$user_address->postal_code = $postal_code;
			$user_address->country = $country;
			$user_address->latitude = $latitude;
			$user_address->longitude = $longitude;

			$user_address->apartment = ($suite) ? $suite : null;
			$user_address->delivery_note = ($delivery_note) ? $delivery_note : null;

			$user_address->save();

			return json_encode(['success' => 'true', 'order' => $order, 'address' => $user_address]);

		} else {
			return json_encode(['success' => '', 'order' => '', 'order_item' => '', 'address' => '']);
		}

	}

	/**
	 * Eater payment details
	 *
	 */

	public function place_order(Request $request) {

		$user_details = User::find(get_current_login_user_id());

		return $this->PlaceOrder($request, $user_details);

	}

	//order track

	public function order_track() {

		$order_id = request()->order_id;
		$order = Order::find($order_id);

		if ($order) {

			$order_delivery = OrderDelivery::where('order_id', $order_id)->first();

			$this->view_data['map_url'] = $order_delivery->trip_path;
			$this->view_data['cancel_reason'] = OrderCancelReason::where('status', 1)->get();
			$this->view_data['order_detail'] = Order::with('currency')->find($order_id);

			session::forget('order_data');
			session::forget('order_detail');

			return view('order_track', $this->view_data);
		}

		abort('404');

	}

	//map track

	public function static_map_track($order_id) {

		$order = Order::find($order_id);

		$user_id = get_store_user_id($order->store_id);

		$res_address = get_store_address($user_id);

		$user_address = get_user_address($order->user_id);

		$origin = $res_address->latitude . ',' . $res_address->longitude;
		$destination = $user_address->latitude . ',' . $user_address->longitude;

		$map_url = getStaticGmapURLForDirection($origin, $destination);

		// Trip Map upload //

		$directory = storage_path('app/public/images/map_image');

		if (!is_dir($directory = storage_path('app/public/images/map_image'))) {
			mkdir($directory, 0755, true);
		}

		$time = time();
		$imageName = 'map_' . $time . '.PNG';
		$imagePath = $directory . '/' . $imageName;
		if ($map_url) {
			file_put_contents($imagePath, file_get_contents($map_url));
			$this->fileSave('map_image', $order_id, $imageName, '1');
		}
	}

	/**
	 * Refund when the eater cancel the order
	 *
	 * @param Get method request inputs
	 *
	 * @return Response Json
	 */

	public function cancel_order() {

		$user_details = auth()->guard('web')->user();

		$order_id = request()->order_id;

		$reason = request()->reason;
		$cancel_message = request()->message;

		$order = Order::find($order_id);

		if ($order->status == $order->statusArray['pending']) {
			$wallet_amount = $order->wallet_amount;

			if ($wallet_amount != 0) {

				$return_wallet = $this->wallet_amount($wallet_amount, $order->user_id);

			}

			if ($order->payment_type == 1) {

				try {

					$stripe_key = site_setting('stripe_secret_key');

					\Stripe\Stripe::setApiKey($stripe_key);

					$payment = Payment::where('order_id', $order->id)->first();

					$amount = $payment->amount;

					$refund = \Stripe\Refund::create([

						'charge' => $payment->transaction_id,
						//'amount' => 1000,
					]);

					if ($refund->status == 'succeeded') {

						//Refund

						$payout = new Payout;
						$payout->amount = $amount;
						$payout->transaction_id = $payment->transaction_id;
						$payout->currency_code = strtoupper($refund->currency);
						$payout->order_id = $order_id;
						$payout->user_id = get_current_login_user_id();
						$payout->status = 1;
						$payout->save();

					} else {

						flash_message('danger', trans('messages.profile_orders.refund_failed'));
						return redirect()->route('order_track', ['order_id' => $order_id]); // Redirect

					}

				} catch (\Exception $e) {

					flash_message('danger', $e->getMessage());
					return redirect()->route('order_track', ['order_id' => $order_id]); // Redirect
				}

			}

			$order1 = Order::find($order->id);
			$order1->cancel_order("eater", $reason, $cancel_message);
			if ($order->payment_type == 1) {
				flash_message('success', trans('messages.profile_orders.amount_has_been_refunded'));
			} else {
				flash_message('success', trans('messages.profile_orders.order_canceled_successfully'));
			}

			return redirect()->route('order_track', ['order_id' => $order_id]); // Redirect

		}
		flash_message('danger', trans('messages.profile_orders.you_cant_cancel_this_order'));
		return redirect()->route('order_track', ['order_id' => $order_id]); // Redirect

	}

}
