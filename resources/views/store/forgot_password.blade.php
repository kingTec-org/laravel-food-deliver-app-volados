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
			<h1>{{trans('messages.profile.forget_password')}}</h1>
			<form action="{{route('store.mail_confirm')}}" method="POST">
				@csrf
				<div class="form-group">
					<label>{{trans('messages.profile.enter_your_email_address')}}</label>
					<input type="text" name="email" placeholder=""/>
					<span class="text-danger">{{ $errors->first('email') }}</span>
				</div>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit">{{trans('messages.profile.next_button')}} <i class="icon icon-right-arrow"></i></button>
				<!-- <div class="mt-3">
					<p>Having trouble? <a class="theme-color" href="{{route('page','help')}}">{{static_pages('help')}}</a></p>
				</div> -->
			</form>
		</div>
	</div>
</main>
@stop