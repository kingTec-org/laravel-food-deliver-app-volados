@extends('template2')

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
			<a href="{{url('/')}}">
				<img src="{{site_setting('1','1')}}"" width="130" height="">
			</a>
		</div>
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1>{{trans('messages.profile.sign_in')}}</h1>
			<form method="POST" action="{{ route('authenticate') }}">
				@csrf
				<div class="form-group col-12">
					<div class="row">
						<label>{{trans('messages.profile.enter_your_phone_number')}} <span>({{trans('messages.profile.required')}})</span></label>
						<div class="d-flex w-100">
							<div class="select mob-select col-md-3">
								<span class="phone_code">+1</span>
								<select id="phone_code" name="country" class="form-control">
									@foreach ($country as $key => $country)
									<option value="{{ $country->phone_code }}" {{ $country->phone_code == 1 ? 'selected' : '' }} >{{ $country->name }}</option>
									@endforeach
								</select>
							</div>
							{!! Form::text('phone_number','',['placeholder' => trans('messages.profile.phone_number'),'class' =>'flex-grow-1','data-error-placement'=>'container','data-error-container'=>'.mobile-number-error'])!!}
						</div>
						<span class="mobile-number-error text-danger">{{$errors->first('phone_number')}}</span>
					</div>
				</div>

				<div class="form-group">
					<label>{{trans('messages.profile.enter_your_password')}}<span>({{trans('messages.profile.required')}})</span></label>
					<input type="password" value="" name="password" id="password" placeholder=""/>
					<span class="text-danger"> {{$errors->first('password')}} </span>
				</div>
				<div class="forget_link">
					<a href="{{route('forgot_password')}}">{{trans('messages.profile.forget_password')}}<span class="qust">{{ trans('messages.store.ques_mark') }}</span> </a>
					<a href="{{route('help_page',current_page())}}">{{trans('messages.profile.get_help')}}</a>
				</div>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit">{{trans('messages.profile.next_button')}} <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
@stop