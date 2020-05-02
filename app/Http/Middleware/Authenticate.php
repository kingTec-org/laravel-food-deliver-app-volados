<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use App\Models\User;
use Illuminate\Contracts\Auth\Factory as Auth;
use Session;

class Authenticate
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guard = @$guards[0] ?: 'user';

        $redirect_to = 'login';

        if($guard == 'admin')
            $redirect_to = 'admin.login';
        if($guard == 'store')
            $redirect_to = 'store.login';
        if($guard == 'driver')
            $redirect_to = 'driver.signup';
        
        //automatic logout  inactive user 
        if(get_current_login_user_details('status')===0)
               $this->auth->guard($guards[0])->logout();

            if(get_current_login_user()=='store' || get_current_login_user()=='web') {
                    $user = User::find(get_current_login_user_details('id'));

                 if ($user->user_address) {

                    $timezone = $user->user_address->default_timezone;

                    date_default_timezone_set($timezone);

                }
            }

  
        if(!$this->auth->guard($guard)->check()){
            return redirect()->route($redirect_to);
        }

        return $next($request);
    }

}
