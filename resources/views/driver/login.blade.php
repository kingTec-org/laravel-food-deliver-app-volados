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
			{!! Form::open(['url'=>route('driver.login'),'method'=>'post','class'=>'mt-4' , 'id'=>'login_form'])!!}
			@csrf




					<div class="form-group col-12">
							<div class="row">
							<label>{{trans('messages.profile.enter_your_phone_number')}}<span> ({{trans('messages.profile.required')}})</span></label>
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


				<button class="btn btn-arrow btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit">{{trans('messages.profile.next_button')}}</button>
				<div class="mt-4">
					<p>{{trans('messages.driver.dont_have_account')}}?
						<a href="{{route('driver.signup')}}" class="theme-color">{{trans('messages.driver.sign_up')}}</a>
					</p>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</main>
@stop