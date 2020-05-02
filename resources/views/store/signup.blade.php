@extends('template')

@section('main')
<div class="flash-container">
	@if(Session::has('message'))
	<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
	</div>
	@endif
</div>
<main id="site-content" role="main">
	<div class="signup-page" ng-controller="store_signup">
		<div class="banner-info">
			<div class="container">
				<div class="banner-txt">
					<div class="col-md-5 col-lg-6 p-0">
						<h1>{{trans('messages.store.fast_way')}}<span>{{trans('messages.store.grow_bussiness')}}</span>
						</h1>
					</div>
					<div class="banner-form">
						<h2>{{trans('messages.store.partner')}}</h2>
						{!! Form::open(['url'=>route('store.signup'),'method'=>'post','class'=>'mt-4' , 'id'=>'signup_form'])!!}
						@csrf
						<div class="form-group">
							{!! Form::text('name','',['id'=>'name','placeholder' => trans('messages.store.store_name')])!!}
						</div>

						<div class="form-group">
							{!! Form::text('address','',['id'=>'location_val','placeholder' => trans('messages.store.address')])!!}
							<p class="location_error text-danger"></p>
						</div>

						<div class="form-group">
							<div class="location">
								{!! Form::text('city','',['id'=>'city','placeholder' => trans('messages.store.city'),'data-error-placement'=>"container",'data-error-container'=>'.city-error'])!!}
							</div>
							<span class="city-error"></span>
						</div>

						<div class="form-group">
							<div class="row">
								<div class='col-md-6'>
									{!! Form::text('first_name','',['placeholder' => trans('messages.store.first_name')])!!}
								</div>
								<div class='col-md-6 mt-3 mt-md-0'>
									{!! Form::text('last_name','',['placeholder' => trans('messages.store.last_name')])!!}
								</div>
							</div>
						</div>

						<div class="form-group col-12">
							<div class="row">
								<div class="d-flex w-100">
									<div class="select mob-select col-md-3">
										<span class="phone_code">+{{ @session::get('phone_code') }}</span>
										<select id="phone_code" name="country" class="form-control">
											@foreach ($country as $key => $country)
											<option value="{{ $country->phone_code }}" {{ $country->phone_code == @session::get('phone_code') ? 'selected' : '' }} >{{ $country->name }}</option>
											@endforeach
										</select>
									</div>
									{!! Form::text('mobile_number','',['placeholder' => trans('messages.store.phone_number'),'class' =>'','data-error-placement'=>'container','data-error-container'=>'.mobile-number-error'])!!}
								</div>
								<span class="mobile-number-error"></span>
							</div>
						</div>

						<div class="form-group">
							{!! Form::text('email','',['placeholder' => trans('messages.store.email')])!!}
							<span class="text-danger">{{ $errors->first('email') }}</span>
						</div>

						<div class="form-group">
							<div class="select">
								{!!Form::select('category', $category, null, ['class' => 'form-control','placeholder' => trans('messages.store.type_of_category'),'data-error-placement'=>'container','data-error-container'=>'.category-error'])!!}
							</div>
							<span class="category-error"></span>
						</div>
						<div class="form-group">
							{!! Form::password('password',['placeholder' => trans('messages.store.password') , 'id'=> 'password'])!!}
						</div>
						<div class="form-group">
							{!! Form::password('conform_pasword',['placeholder' => trans('messages.store.confirm_password'),'id' => 'conform_password'])!!}
						</div>

						<div style="display:none;">
							{!! Form::text('country_code','',['id'=>'country_code'])!!}
							{!! Form::text('postal_code','',['id'=>'postal_code'])!!}
							{!! Form::text('state','',['id'=>'state'])!!}
							{!! Form::text('street','',['id'=>'address_line_1'])!!}
							{!! Form::text('latitude','',['id'=>'latitude'])!!}
							{!! Form::text('longitude','',['id'=>'longitude'])!!}
						</div>

						<button type="submit" class="btn btn-theme w-100 text-left text-uppercase">{{trans('messages.store.submit')}}
							<i class="icon icon-right-arrow float-right"></i>
						</button>
					<!-- 	<span class="mt-3 d-block">
							After you submit this form, a member of the {{site_setting('site_name')}} team will get in touch with you.
						</span> -->
						{!! Form::close() !!}
					</div>
				</div>
			</div>
		</div>

		<div class="signup-slider mb-5 owl-carousel">
			@foreach($slider as $image)
			<div class="slide-txt" style="background-image: url('{{$image->slider_image}}');">
				<div class="container">
					<div class="col-md-5 col-lg-6 p-0">
						<h1>{{$image->title}}</h1>
						<p>{{$image->description}}</p>
					</div>
				</div>
			</div>
			@endforeach
		</div>

		<div class="stores-info my-5">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="res-img">
							<img src="{{url('/')}}/images/business.png"/>
						</div>
						<h2>{{trans('messages.store.more_business')}}</h2>
						<p>{{trans('messages.store.impact_your_business', ['site_name'=>site_setting('site_name')] ) }}</p>
					</div>
					<div class="col-md-4">
						<div class="res-img">
							<img src="{{url('/')}}/images/route.png"/>
						</div>
						<h2>{{trans('messages.store.deliver_faster')}}</h2>
						<p>{{trans('messages.store.item_your_customers', ['site_name'=>site_setting('site_name')] ) }}</p>
					</div>
					<div class="col-md-4">
						<div class="res-img">
							<img src="{{url('/')}}/images/support.png"/>
						</div>
						<h2>{{trans('messages.store.partner_with_professionals')}}</h2>
						<p>{{trans('messages.store.promote_your_menu', ['site_name'=>site_setting('site_name')] ) }}</p>
					</div>
				</div>
			</div>
		</div>

		<div class="profile-slider owl-carousel">
			<div class="item d-md-flex align-items-center flex-md-row-reverse">
				<div class="slider-img col-md-5" style="background-image: url('{{url('/')}}/images/banner1.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4>{{trans('messages.store.general_manager_feedback', ['site_name'=>site_setting('site_name')])}}</h4>
						<p><strong>{{trans('messages.store.general_manager_name')}}</strong>
							<span>{{trans('messages.store.general_manager')}}</span>
						</p>
					</div>
				</div>
			</div>
			<div class="item d-md-flex align-items-center flex-md-row-reverse">
				<div class="slider-img col-md-5" style="background-image: url('{{url('/')}}/images/banner2.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4>{{trans('messages.store.owner_feedback', ['site_name'=>site_setting('site_name')])}}</h4>
						<p><strong>{{trans('messages.store.owner_name')}}</strong>
							<span>{{trans('messages.store.owner')}}</span>
						</p>
					</div>
				</div>
			</div>
			<div class="item d-md-flex align-items-center flex-md-row-reverse">
				<div class="slider-img col-md-5" style="background-image: url('{{url('/')}}/images/banner3.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4>{{trans('messages.store.chef_feedback', ['site_name'=>site_setting('site_name')])}}</h4>
						<p>
							<strong>{{trans('messages.store.chef_name')}}</strong>
							<span>{{trans('messages.store.chef')}}</span>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop
@push('scripts')
<script type="text/javascript">
	Lang.setLocale("{!! (Session::get('language')) ? Session::get('language') : $default_language[0]->value !!}");
</script>
@endpush