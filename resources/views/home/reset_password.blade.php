@extends('template2')

@section('main')
<div class="flash-container">
	@if(Session::has('message'))
	<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
	</div>
	@endif
</div>
<main id="site-content" role="main" class="log-user" ng-controller="forget_password">
	<div class="container">
		<div class="logo text-center mt-5">
			<a href="{{url('/')}}">
				<img src="{{site_setting('1','1')}}" width="120" height="">
			</a>
		</div>
		<div class="password-form py-5 mb-5 col-md-8 col-lg-6 mx-auto">
			<h1>{{ trans('messages.driver.change_your_password') }}</h1>
			<form method="post" action="{{route('reset_password')}}">
				@csrf
				<div class="form-group">
					<label>{{ trans('messages.profile.password') }}</label>
					<input type="password" name="password" id="password" placeholder="{{ trans('messages.driver.enter_password') }}"/>
					<span class="text-danger">{{$errors->first('password')}} </span>
				</div>
				<div class="form-group">
					<label>{{ trans('messages.store.confirm_password') }}</label>
					<input type="password" name="confirmed" id="confirmed" placeholder="{{ trans('messages.driver.enter_password_again') }}"/>
					<span class="text-danger">{{$errors->first('confirm_password')}} </span>
				</div>
				<input type="hidden" name="user_id" id='user_id' value="{{$user_id}}">
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center"  type="submit">{{ trans('messages.profile.next_button') }} <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
@stop