<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use App\Models\Driver;
use Tymon\JWTAuth\Middleware\BaseMiddleware;

class CheckDriverFromToken extends BaseMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, \Closure $next, $only_active = "true") {

		$driver = Driver::authUser()->first();

		$timezone = $driver->driver_timezone;
		date_default_timezone_set($timezone);
		date_default_timezone_get();

		if (!$driver) {
			return response()->json(
				[
					'status_message' => 'Add vehicle details before proceeding',
					'status_code' => '0',
				]
			);
		}

		if ($driver->user->status_text != "active" && $only_active == "true") {
			// return response()->json(
			//     [
			//         'status_message'    => 'Pending signup process / Inactive driver.',
			//         'status_code'   => '0'
			//     ]
			// );
		}

		return $next($request);
	}
}
