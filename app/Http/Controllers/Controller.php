<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\OrderCancelReason;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	public $view_data;

	public function __construct() {

		$installedLogFile = storage_path('installed');
		if (env('DB_DATABASE') && file_exists($installedLogFile)) {

			config()->set('fcm.http', [
				'server_key' => site_setting('fcm_server_key'),
				'sender_id' => site_setting('fcm_sender_id'),
				'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
				'server_group_url' => 'https://android.googleapis.com/gcm/notification',
				'timeout' => 10,
			]);
			$this->view_data['currency'] = Currency::where('status', 1)->pluck('code', 'code');
			$this->view_data['phone_code_country'] = Country::where('status', 1)->get();
			$this->view_data['country'] = Country::where('status', 1)->get();
			$this->view_data['address_country'] = Country::where('status', 1)->pluck('name','code');
			$this->view_data['default_img'] = sample_image();
			$this->view_data['cancel_reason'] = OrderCancelReason::where('status', 1)->get();

		}
	}

}
