<?php

namespace App\Http\Middleware;
use App;
use Closure;
use Request;
use Session;
use App\Models\Language;
use App\Models\Pages;
use View;
use Schema;
class Locale {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */

	public function handle($request, Closure $next) {
		
		$locale = Language::translatable()->where('default_language', '=', '1')->first()->value;
		
        $session_language = Language::translatable()->where('value', '=', Session::get('language'))->first();

        if ($session_language) {
            $locale = $session_language->value;
        }
        \Log::info('Locale Middleware'.$locale);
        App::setLocale($locale);
        Session::put('language', $locale);

         		$root = check_current_root();
                $page = $root == 'web' ? 'user' : $root;
                                  
            	if($page != 'admin' && $page != 'api') {                       
                    $static_pages_changes = Pages::User($page)->where('footer', 1)->where('status', '1')->get();
                    View::share('static_pages_changes', $static_pages_changes->split(2));
                }

		$timezone = Session::get('timezone');

		if (!$timezone) {

			$timezone = 'Pacific/Kwajalein';

		}
		date_default_timezone_set($timezone);

		return $next($request);

	}

}
