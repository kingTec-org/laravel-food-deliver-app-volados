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
			<h4 class="text-center my-3"></h4>
			{!! Form::open(['url'=>route('store.password'),'method'=>'post','class'=>'mt-4' , 'id'=>'login_form'])!!}
			@csrf
			<div class="form-group" ng-init="textInputPassword=''">
				<label>{{trans('messages.profile.enter_your_password')}}</label>

				{!! Form::password('textInputPassword',['placeholder' => trans('messages.profile.password'),'ng-model' => 'textInputPassword'])!!}

				<span class="text-danger">{{ $errors->first('password') }}</span>

			</div>
			<a href="{{route('store.forget_password')}}">{{trans('messages.profile.forget_password')}}<span class="qust">{{ trans('messages.store.ques_mark') }}</span> </a> <small> <a href="{{route('help_page',current_page())}}">{{trans('messages.profile.get_help')}}</a></small>
			<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit">{{trans('messages.profile.next_button')}} <i class="icon icon-right-arrow"></i></button>
		</form>
	</div>
</div>
</main>
@stop