<?php

namespace App\Http\Middleware;

use App\Models\Country;
use Closure;
use Session;

class ClearCache {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		$ip = getenv("REMOTE_ADDR");

		$result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $ip));

		if (!$request->session()->get('time_zone')) {

			if ($result['geoplugin_currencyCode']) {

				Session::put('time_zone', $result['geoplugin_timezone']);
				Session::put('country_code', $result['geoplugin_countryCode']);
				$phone_code = Country::where('code', $result['geoplugin_countryCode'])->first()->phone_code;
				Session::put('phone_code', $phone_code);

			}
		}

		$timezone = $request->session()->get('time_zone');

		if (isset($timezone)) {
			date_default_timezone_set($timezone);
		} else {

			date_default_timezone_set('Asia/Calcutta');

			Session::put('time_zone', 'Asia/Calcutta');
			Session::put('country_code', 'IN');
			$phone_code = Country::where('code', 'IN')->first()->phone_code;
			Session::put('phone_code', $phone_code);

		}

		schedule_data_update();

		$response = $next($request);
		return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
			->header('Pragma', 'no-cache')
			->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
	}
}
