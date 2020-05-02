@extends('template2')

@section('main')
<div class="flash-container">
	@if(Session::has('message'))
	<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
	</div>
	@endif
</div>
<main id="site-content" role="main" class="log-user" ng-controller="home_page">
	<div class="container">
		<div class="logo text-center mt-5">
			<a href="{{url('/')}}">
				<img src="{{site_setting('1','1')}}" width="120" height="">
			</a>
		</div>
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1>{{trans('messages.profile.create_an_account')}}</h1>
			<form name="signup2" id='eater_signup_form' class="form-horizontal">
				<div class="form-group">
					<label>{{trans('messages.profile.enter_first_name')}}<span>({{trans('messages.profile.required')}})</span></label>
					<input type="text" name="first_name" id="first_name" placeholder=""/>
				</div>
				<div class="form-group">
					<label>{{trans('messages.profile.enter_last_name')}} <span>({{trans('messages.profile.required')}})</span></label>
					<input type="text" name="last_name" id="last_name" placeholder=""/>
				</div>
				<div class="form-group">
					<label>{{trans('messages.profile.enter_your_phone_number')}} <span>({{trans('messages.profile.required')}})</span></label>
					<div class="d-flex w-100">
						<div class="select mob-select col-md-3">
							<span class="phone_code">+{{ @session::get('phone_code') }}</span>


							<select id="phone_code" name="country_code" class="form-control">
						                    @foreach ($country as $key => $country)
						                        <option value="{{ $country->phone_code }}" {{ $country->phone_code == @session::get('phone_code') ? 'selected' : '' }} >{{ $country->name }}</option>
						                    @endforeach
						                </select>

						</div>
						<input type="number" name="phone_number" id="phone_number" data-error-placement="container" data-error-container=".phone_error" placeholder=""/>
					</div>
				</div>
				<p class="phone_error text-danger">  </p>
				<div class="form-group">
					<label>{{trans('messages.profile.enter_your_email_address')}} <span>({{trans('messages.profile.required')}})</span></label>
					<input type="text" name="email_address" id="email_address" placeholder=""/>
					<p id="email_address_error" style="color: red;display: none">Invalid email address</p>
				</div>
				<div class="form-group">
					<label>{{trans('messages.profile.password')}} <span>({{trans('messages.profile.required')}})</span></label>
					<input type="password" name="password" id="password" placeholder=""/>
				</div>
				<p class="required_error" style="color: red; display: none">{{trans('messages.profile.invalid_email_address')}}</p>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" id="signup_form_submit" type="submit">{{trans('messages.profile.next_button')}} <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
@stop