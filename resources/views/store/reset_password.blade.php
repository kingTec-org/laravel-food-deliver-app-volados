@extends('template')

@section('main')
<div class="flash-container">
	@if(Session::has('message'))
	<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
	</div>
	@endif
</div>
<main id="site-content" role="main" class="log-user" ng-controller="forgot_password">
	<div class="container">
		<div class="logo text-center mt-5">
			<a href="{{url('/store')}}">
				<img src="{{site_setting('store_logo','3')}}" width="120" height="">
			</a>
		</div>
		<div class="password-form py-5 mb-5 col-md-8 col-lg-6 mx-auto">
			<h1>{{ trans('messages.driver.change_your_password') }}</h1>
			<form method="" action="">
				@csrf
				<div class="form-group">
					<label>{{ trans('messages.profile.password') }}</label>
					<input type="password" name="password" id="password" placeholder="{{ trans('messages.driver.enter_password') }}"/>
				</div>
				<p id='password_count' style="display: none; color:red;">{{ trans('messages.store_dashboard.password_must_be_atleast') }}</p>
				<p id='password_required' style="display: none; color:red;">{{ trans('messages.store.this_field_is_required') }}</p><div class="form-group">
					<label>{{ trans('messages.store.confirm_password') }}</label>
					<input type="password" name="confirm_password" id="confirm_password" placeholder="{{ trans('messages.driver.enter_password_again') }}"/>
				</div>

				<p id='password_error' style="display: none; color:red;">{{ trans('messages.store_dashboard.password_doesnot_match') }}</p>

				<input type="hidden" name="user_id" id='user_id' value="{{$user_id}}">

				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" id="password_change" type="submit">{{ trans('messages.profile.next_button') }} <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
@stop