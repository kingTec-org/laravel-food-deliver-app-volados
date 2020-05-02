@extends('driver.template')


@section('main')
<div class="flash-container">
	@if(Session::has('message'))
	<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
	</div>
	@endif
</div>
<main id="site-content" role="main" class="log-user" ng-controller="driver_signup">
	<div class="container">
		<div class="logo text-center mt-5">
			<a href="{{url('/driver')}}">
				<img src="{{site_setting('driver_logo','7')}}" width="120" height="">
			</a>
		</div>
		<div class="password-form py-5 mb-5 col-md-8 col-lg-6 mx-auto">
			<h1>{{ trans('messages.driver.change_your_password') }}</h1>
			<form name="reset_password" id="reset_password" method="POST" action="{{route('driver.password_change')}}">
				@csrf
				<div class="form-group">
					<label>{{ trans('messages.profile.password') }}</label>
					<input type="password" name="password"  placeholder="{{ trans('messages.driver.enter_password') }}"/>
					<span class="text-danger">{{ $errors->first('password') }}</span>
				</div>
				<div class="form-group">
					<label>{{ trans('messages.store.confirm_password') }}</label>
					<input type="password" name="password_confirmation" id="confirm_password1" placeholder="{{ trans('messages.driver.enter_password_again') }}"/>
					<span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
				</div>


				<input type="hidden" name="user_id" id='user_id1' value="{{$user_id}}">

				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" id="password_change" type="submit">{{ trans('messages.profile.next_button') }} <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
@stop