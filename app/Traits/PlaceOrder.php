<?php

/**
 * Place order Trait
 *
 * @package     Gofereats
 * @subpackage  Place order Trait

 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Traits;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Penality;
use App\Models\PenalityDetails;
use App\Models\Store;
use App\Models\UserAddress;
use App\Models\UserPaymentMethod;
use App\Models\UsersPromoCode;
use App\Models\Wallet;
use Carbon\Carbon;

trait PlaceOrder {

	public function placeOrder($request, $user_details) {

		$address = UserAddress::where('user_id', $user_details->id)->default()->first();
		$address->order_type = $request->order_type;
		$address->delivery_time = $request->delivery_time;
		$address->save();

		//promo apply if user promo add for this Order

		$update_promo = promo_calculation();

		$order_details = Order::find($request->order_id);

		$order_details->schedule_status = $request->order_type;
		$order_details->schedule_time = $request->delivery_time;
		$order_details->save();

		$check_address = check_location($request->order_id);

		if ($check_address == 0) {

			return response()->json(['status_message' => trans('api_messages.orders.store_location_unavailable'), 'status_code' => '0']);

		}

		$unavailable = Store::where('id', $order_details->store_id)->status()->first();

		if (!$unavailable) {

			return response()->json(['status_message' =>trans('api_messages.orders.store_unavailable'), 'status_code' => '0']);

		}

		//promo apply if user promo add for this Order

		$promo_codes = UsersPromoCode::whereUserId($user_details->id)->where('order_id', 0)->with('promo_code_many')->whereHas('promo_code_many')->orderBy('created_at', 'asc')->first();

		if ($promo_codes) {

			UsersPromoCode::whereId($promo_codes->id)->update(['order_id' => $request->order_id]);
		}

		$owe_amount = 0;

		// Wallet Amount Apply

		$is_wallet = $request->isWallet;
		$use_wallet_amount = use_wallet_amount($request->order_id, $is_wallet);
		$amount = $use_wallet_amount['amount'];
		$remaining_wallet = $use_wallet_amount['remaining_wallet_amount'];

		$currency_code = $order_details->currency_code;
		$payment_type = $request->payment_method;

		if ($payment_type == 1) {

			// stripe

			if ($amount != 0) {

				try {

					$customer_id = UserPaymentMethod::where('user_id', $user_details->id)->first()->stripe_customer_id;
					$stripe_key = site_setting('stripe_secret_key');

					\Stripe\Stripe::setApiKey($stripe_key);

					$charge = \Stripe\Charge::create(
						[
							"amount" => $amount * 100,
							'currency' => $currency_code,
							"customer" => $customer_id,
						]
					);

					$payment = new Payment;
					$payment->user_id = $user_details->id;
					$payment->order_id = $request->order_id;
					$payment->transaction_id = $charge->id;
					$payment->type = 0;
					$payment->amount = $amount;
					$payment->status = 1;
					$payment->currency_code = $currency_code;
					$payment->save();

				} catch (\Exception $e) {
					return response()->json(
						[

							'status_message' => $e->getMessage(),

							'status_code' => '0',

						]
					);
				}

			} else {

				$payment = new Payment;
				$payment->user_id = $user_details->id;
				$payment->order_id = $request->order_id;
				$payment->transaction_id = '1';
				$payment->type = 0;
				$payment->amount = $amount;
				$payment->status = 1;
				$payment->currency_code = $currency_code;
				$payment->save();

			}

		} else {

			// cash payment

			$payment = new Payment;
			$payment->user_id = $user_details->id;
			$payment->order_id = $request->order_id;
			$payment->transaction_id = '0';
			$payment->type = 0;
			$payment->amount = $amount;
			$payment->status = 0;
			$payment->currency_code = $currency_code;
			$payment->save();

			// owe amount

			$driver_to_admin = site_setting('driver_commision_fee');
			$pay_to_admin = ($order_details->delivery_fee / 100) * $driver_to_admin;
			$owe_amount = $amount - ($order_details->delivery_fee - $pay_to_admin);
			if ($owe_amount < 0) {
				$owe_amount = 0;
			}

		}

		$user_address = $user_details->user_address;
		$store_address = $order_details->store->user_address;
		$driving_distance = get_driving_distance($user_address->latitude, $store_address->latitude, $user_address->longitude, $store_address->longitude);

		if ($driving_distance['status'] == 'fail') {

			return response()->json(
				[
					'status' => '0',
					'messages' => $driving_distance['msg'],
					'status_message' => 'Some technical issue contact admin',
				]);

		}

		$order = Order::find($request->order_id);
		$order->status = $order->statusArray['pending'];
		$order->payment_type = $payment_type;
		$order->total_amount = $amount;
		$order->owe_amount = $owe_amount;
		$order->est_preparation_time = $order->store->getStorePreparationTime(Carbon::now());
		$order->est_travel_time = gmdate("H:i:s", $driving_distance['time']);

		//Estimation Delivery time

		$time = getTimeFromSeconds($driving_distance['time']);
		$secs = strtotime($order->est_preparation_time) - strtotime("00:00:00");
		$result = date("H:i:s", strtotime($time) + $secs);

		if ($order->schedule_status == 0) {

			$secs = strtotime($result) - strtotime("00:00:00");
			$est_time = date("H:i:s", time() + $secs);

		} else {

			$data['total_time'] = date("H:i:s", strtotime($order->est_preparation_time) + $secs);
			$secs = strtotime($result) - strtotime("00:00:00");
			$est_time = date("H:i:s", strtotime($order->schedule_time) - $secs);

		}

		$order->est_delivery_time = $est_time;
		$order->notes = $request->notes;
		$order->created_at = date('Y-m-d H:i:s');
		$order->save();

		if ($request->isWallet == 1) {

			$wallet = Wallet::where('user_id', $user_details->id)->first();
			if ($wallet) {

				$wallet->amount = $remaining_wallet;
				$wallet->save();
			}
		}

		push_notification_for_store($order);

		//user penality

		$user_penality = Penality::where('user_id', $order->user_id)->first();

		if ($user_penality) {

			$penality_details = PenalityDetails::where('order_id', $order->id)->first();
			$previous = 0;
			if ($penality_details) {
				$previous = $penality_details->previous_user_penality;
			}

			$user_penality->remaining_amount = 0;
			$user_penality->paid_amount = $user_penality->paid_amount + $previous;
			$user_penality->save();

		}
		//clear_schedule data from session
			schedule_data_update('clear_schedule');
		$order = Order::find($request->order_id);

		return response()->json(
			[

				'status_message' => 'Successfully',

				'status_code' => '1',

				'order_details' => $order,

			]
		);

	}

	public function wallet_amount($amount, $user_id) {

		$wallet = Wallet::where('user_id', $user_id)->first();

		if ($wallet) {
			$wallet->amount = $wallet->amount + $amount;
			$wallet->save();
		}

		return;
	}
}
