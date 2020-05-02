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

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use App;
use Request;
class GetUserFromToken extends BaseMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, \Closure $next) {

		if (!$token = $this->auth->setRequest($request)->getToken()) {
			return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
		}

		try {
			
			$user = $this->auth->authenticate($token);
			if(Request::segment(1) == 'api_payments' ||Request::segment(1) == 'api'){
			if(isset($request->language))
            {
                App::setLocale($request->language);
                
            }
            else
            {
                App::setLocale('en');
                
            }
        }
		} catch (TokenExpiredException $e) {

			//return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);

			$refreshed = JWTAuth::refresh(JWTAuth::getToken());

			return response()->json([

				'status_message' => "Token Expired",

				'status_code' => "0",

				'refresh_token' => $refreshed,

			]);

		} catch (JWTException $e) {
			return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
		}

		if (!$user) {

			return response()->json([

				'status_message' => trans('api_messages.driver.user_not_found') ,

				'status_code' => "0",

			], 400);
		}

		if ($user->status == '0') {

			return response()->json([

				'status_message' => trans('api_messages.driver.inactive_user') ,

				'status_code' => "0",

			], 401);
		}

		if ($user->user_address) {

			$timezone = $user->user_address->default_timezone;

			date_default_timezone_set($timezone);
			date_default_timezone_get();

		}

		if(isset($user->language))
            App::setLocale($user->language);
            else
            App::setLocale('en');

		$this->events->fire('tymon.jwt.valid', $user);

		return $next($request);
	}
}
