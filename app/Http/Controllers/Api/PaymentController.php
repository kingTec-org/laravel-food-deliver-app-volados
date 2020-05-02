<?php

/**
 * Payment Controller
 *
 * @package    GoferEats
 * @subpackage Controller
 * @category   Payment
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\PlaceOrder;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use JWTAuth;
use Stripe;

class PaymentController extends Controller {

	use PlaceOrder;

	/**
	 * Eater Place order and payment
	 *
	 * @param Get method request inputs
	 *
	 * @return Response Json
	 */

	public function place_order(Request $request) {

		$user_details = JWTAuth::parseToken()->authenticate();

		return $this->PlaceOrder($request, $user_details);
	}

	/**
	 * Refund when the store not accept the order
	 *
	 * @param Get method request inputs
	 *
	 * @return Response Json
	 */

	public function cron_refund(Request $request) {

		$order = Order::status('pending')->get();

		if (isset($order)) {

			foreach ($order as $order) {

				date_default_timezone_set($order->user->user_address->default_timezone);

				$before_minutes = Carbon::now()->subMinutes(2)->format('Y-m-d H:i');

				$updated_at = date('Y-m-d H:i', strtotime($order->updated_at));

				if (strtotime($updated_at) <= strtotime($before_minutes)) {

					$request['order_id'] = $order->id;

					$this->refund($request, '', $order->user);
				}
			}

		}

	}

	/**
	 * Refund when the store not accept the order
	 *
	 * @param Get method request inputs
	 *
	 * @return Response Json
	 */

	public function refund(Request $request, $status = '', $user_id = '',$cancel_by='') {

		if ($user_id) {

			$user_details = $user_id;

		} else {

			$user_details = JWTAuth::parseToken()->authenticate();

		}

		$order_id = $request->order_id;

		if ($status == 'Cancelled') {

			$order = Order::find($order_id);

		} else {

			$order = Order::where('id', $order_id)->where('status', '1')->first();

		}

		if ($order) {

			$wallet_amount = $order->wallet_amount;

			if ($wallet_amount != 0) {

				$this->wallet_amount($wallet_amount, $order->user_id);

			}

			$update_order_details = $order;

			//Revert Penality amount if exists

			$penality_Revert = revertPenality($order->id);

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

						$user = $order->user;

						if ($order->status == $order->statusArray['pending']) {

							$update_order_details->status = $order->statusArray['declined'];

						}

						/* Refund Notification */

						$push_notification_title = trans('api_messages.refund.amount_refunded') . $order->id;
						$push_notification_data = [
							'type' => 'Amount Refund',
							'order_id' => $order->id,

						];

						push_notification($user->device_type, $push_notification_title, $push_notification_data, 0, $user->device_id);

						/* Cancel Notification */

						$user = $order->user;
						$push_notification_title = trans('api_messages.refund.store_not_accepted');
						$push_notification_data = [
							'type' => 'order_cancelled',
							'order_id' => $update_order_details->id,
							'order_data' => [
								'id' => $update_order_details->id,
								'user_name' => $update_order_details->user->name,
								'status_text' => $update_order_details->status_text,
							],
						];

						$update_order_details->declined_at = date('Y-m-d H:i:s');

						$update_order_details->schedule_status = 0;

						$update_order_details->save();

						push_notification($user->device_type, $push_notification_title, $push_notification_data, 0, $user->device_id);

						return response()->json(
							[

								'status_message' => trans('api_messages.refund.refund_successfully'),

								'status_code' => '1',

								'refund' => $refund,

							]
						);

					} else {

						return response()->json(
							[

								'status_message' => trans('api_messages.refund.refund_failed'),

								'status_code' => '1',

								'refund' => $refund,

							]
						);

					}

				} catch (\Exception $e) {

					return response()->json(
						[

							'status_message' => $e->getMessage(),

							'status_code' => '0',

						]
					);
				}

			}

			if ($order->status == $order->statusArray['pending']) {

				$update_order_details->declined_at = date('Y-m-d H:i:s');
				$update_order_details->status = $order->statusArray['declined'];

			}

			$update_order_details->schedule_status = 0;
			$update_order_details->save();

			/* Cancel Notification */

			if($cancel_by!="eater")
			{
				$user = $order->user;
				$push_notification_title =trans('api_messages.refund.store_not_accept');
				$push_notification_data = [
				'type' => 'order_cancelled',
				'order_id' => $update_order_details->id,
				'order_data' => [
				'id' => $update_order_details->id,
				'user_name' => $update_order_details->user->name,
				'status_text' => $update_order_details->status_text,
				],
				];

				push_notification($user->device_type, $push_notification_title, $push_notification_data, 0, $user->device_id);

			}

		

			return response()->json(
				[

					'status_message' => trans('api_messages.refund.cash_order'),

					'status_code' => '1',

				]
			);

		}
	}

	/**
	 * Return amount to wallet when the store not accept the order
	if using wallet amount
	 *
	 * @param Get method request inputs
	 *
	 * @return Response Json
	 */

	public function wallet_amount($amount, $user_id) {

		$wallet = Wallet::where('user_id', $user_id)->first();

		if ($wallet) {
			$wallet->amount = $wallet->amount + $amount;
			$wallet->save();
		}

		return;
	}
}
