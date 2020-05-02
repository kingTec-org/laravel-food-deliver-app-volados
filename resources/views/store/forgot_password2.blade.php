@extends('template')

@section('main')
<div class="flash-container">
	@if(Session::has('message'))
	<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
	</div>
	@endif
</div>
<main id="site-content" role="main" class="log-user">
	<div class="container">
		<div class="logo text-center mt-5">
			<a href="{{url('/store')}}">
				<img src="{{site_setting('store_logo','3')}}" width="120" height="">
			</a>
		</div>
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1>{{trans('messages.driver.reset_your_account')}}</h1>
			<form name="forgotpassword2" method="POST" action="{{route('store.set_password')}}">
				@csrf
				<div class="form-group">

					<label>{{trans('messages.store_dashboard.enter_digit_code_sent_to_your_email')}}</label>
					<input type="text" name="code_confirm" id="code_confirm" placeholder=""/>
					<span class="text-danger">{{ $errors->first('code_confirm') }}</span>

					<input type="hidden" name="user_details" value="{{ @$user_id }}">

				</div>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" id="code_confirm_submit" type="submit">{{trans('messages.store_dashboard.next')}} <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
@stop