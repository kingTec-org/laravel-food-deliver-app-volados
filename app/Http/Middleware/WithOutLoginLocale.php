<?php

namespace App\Http\Middleware;

use Closure;
use Log;
use Auth;
use JWTAuth;
use App;
use Session;
use Request;
class WithOutLoginLocale
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
        Log::info('without login');

        if(Request::segment(1) == 'api'){            
            if($request->token ==''){
               App::setLocale($request->language);
                $language = $request->language;
                }
            else
            {
                App::setLocale('en');
                $language = 'en';
            }
                Session::put('language', $language);
                
                   
            }

            return $next($request);
        }
        
    }
