@extends('driver.template')

@section('main')
<div class="flash-container">
      @if(Session::has('message'))
          <div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
              <a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
          </div>
      @endif
  </div>
<main id="site-content" role="main" class="log-user driver">
	<div class="container">
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1>{{trans('messages.profile.sign_in')}}</h1>
			{!! Form::open(['url'=>route('driver.password'),'method'=>'post','class'=>'mt-4' , 'id'=>'login_form1'])!!}
			@csrf
				<div class="form-group">
					<label>{{trans('messages.profile.password')}}</label>
					<input type="password" name="password" value="" placeholder="{{trans('messages.profile.password')}}"/>
					<span class="text-danger">{{ $errors->first('password') }}</span>
				</div>
				<button class="btn btn-arrow btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit">{{trans('messages.profile.next_button')}}</button>
				<div class="mt-4">
					<p>
						<a href="{{route('driver.forgot_password')}}" class="theme-color">{{trans('messages.profile.forget_password')}}<span class="qust">{{ trans('messages.store.ques_mark') }}</span> </a>
						<a href="{{route('help_page',current_page())}}" class="theme-color">{{trans('messages.profile.get_help')}}</a>
					</p>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</main>
@stop