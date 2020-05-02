<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $redirect_to = 'home';
        if($guard=='admin')
            $redirect_to = 'admin.dashboard';
        if($guard=='store')
            $redirect_to = 'store.dashboard';
        if($guard=='driver')
            $redirect_to = 'driver.profile';
        if (Auth::guard($guard)->check()) {
            return redirect()->route($redirect_to);
        }

        return $next($request);
    }
}
