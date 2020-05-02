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
			<a href="{{url('store')}}">
				<img src="{{site_setting('store_logo','3')}}" width="120" height="">
			</a>
		</div>
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1>{{trans('messages.profile.sign_in')}}</h1>
			{!! Form::open(['url'=>route('store.login'),'method'=>'post','class'=>'mt-4' , 'id'=>'login_form'])!!}
			@csrf
			<div class="form-group" ng-init="textInputValue=''">
				<label>{{trans('messages.store.Enter your email')}}</label>

				{!! Form::text('textInputValue','',['placeholder' => trans('messages.store.email'),'ng-model' => 'textInputValue'])!!}
				<span class="text-danger">{{ $errors->first('textInputValue') }}</span>

			</div>
			<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit">{{trans('messages.profile.next_button')}} <i class="icon icon-right-arrow"></i></button>
			{!! Form::close() !!}
		</div>
	</div>
</main>
@stop