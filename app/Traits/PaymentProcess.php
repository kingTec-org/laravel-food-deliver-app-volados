<?php 

/**
 * PaymentProcess Trait
 *
 * @package     Gofereats
 * @subpackage  PaymentProcess Trait
 * @category    PaymentProcess
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Traits;
use App\Models\Wallet;
use App\Models\Payout;


trait PaymentProcess{

		/**
	 * Payout yo user
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function admin_payout_to_user($user_id,$order_id) {

		$payout = Payout::where('user_id', $user_id)->where('order_id', $order_id)->first();
		$data = $this->payout_to_users((float) $payout->amount, currency_symbol(), $payout->user->payout_id);
		if ($data['success'] == true) {
			$payout->status = 1;
			$payout->transaction_id = $data['transaction_id'];
			$payout->save();
			$response['success'] = true;
			$response['message'] = trans('admin_messages.payment_sent_successfully');
		} else {
			$response['success'] = false;
			$response['message'] = $data['message'];
		}

		return $response;
	}

	public function payout_to_users($amount, $currency, $payout_account) {
		$amount = $amount * 100;
		$stripe_key = site_setting('stripe_secret_key');
		\Stripe\Stripe::setApiKey($stripe_key);
		try
		{

			$response = \Stripe\Transfer::create(array(
				"amount" => $amount,
				"currency" => $currency,
				"destination" => $payout_account,
				"transfer_group" => "ORDER_95",
			));

		} catch (\Exception $e) {
			$data['success'] = false;
			$data['message'] = $e->getMessage();
			return $data;
		}

		try
		{

			$response = \Stripe\Payout::create(
				array(
					"amount" => $amount,
					"currency" => $currency,
				),
				array("stripe_account" => $payout_account)
			);

		} catch (\Exception $e) {
			$data['success'] = false;
			$data['message'] = $e->getMessage();
			return $data;
		}

		$data['success'] = true;
		$data['transaction_id'] = $response->id;
		return $data;

	}

	public function refund_to_users($amount, $transaction_id) {
		$amount = $amount * 100;
		$stripe_key = site_setting('stripe_secret_key');
		\Stripe\Stripe::setApiKey($stripe_key);
		try
		{

			$refund = \Stripe\Refund::create([

				'charge' => $transaction_id,
				'amount' => $amount,
			]);

		} catch (\Exception $e) {
			$data['success'] = false;
			$data['message'] = $e->getMessage();
			return $data;
		}
		if ($refund->status == 'succeeded') {
			$data['success'] = true;
			$data['message'] = true;
			$data['transaction_id'] = $refund->id;
		} else {
			$data['success'] = false;
			$data['message'] = 'Refund Failed';
		}
		return $data;
	}

	public function refund_to_wallet($user_id, $amount) {
		$wallet = Wallet::where('user_id', $user_id)->first();

		if ($wallet == '') {
			$wallet = new Wallet;
		}
		$wallet->user_id = $wallet->user_id;
		$wallet->amount = $wallet->amount + $amount;
		$wallet->save();
	}


}
