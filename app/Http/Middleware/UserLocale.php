<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Auth;
use JWTAuth;
use App;
use Session;
use Request;
class UserLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('api url check'.Request::segment(1));

        if(Request::segment(1) == 'api_payments' ||Request::segment(1) == 'api'){            
            if($request->token !=''){
                $user = JWTAuth::parseToken()->authenticate();                
                $userLanguage = $user->language;
                \Log::info('username'.$user->name);
                Session::put('language', $userLanguage);
                App::setLocale($userLanguage);
                   
            }
        }
        return $next($request);
    }
}