@include('common.head')

@if (Route::current()->uri() != 'login' && Route::current()->uri() != 'forgot_password' && Route::current()->uri() != 'signup' && Route::current()->uri() != 'signup_confirm' && Route::current()->uri() != 'otp_confirm' && Route::current()->uri() != 'reset_password')
	@include('common.header2')
@endif

@yield('main')

@include('common.footer')

@include('common.foot')