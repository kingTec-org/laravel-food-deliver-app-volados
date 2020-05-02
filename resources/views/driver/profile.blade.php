@extends('driver.template')

@section('main')
<div class="flash-container">
	@if(Session::has('message'))
	<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
	</div>
	@endif
</div>
<main id="site-content" role="main" class="log-user driver" ng-controller="driver_signup">
	<div class="container">
		<div class="profile mb-5">
			<div class="d-md-flex">
			@include('driver.partner_navigation')
				<div class="profile-info col-12 col-md-9 col-lg-9">
					<div class="row d-block">
						<div class="profile-title py-md-4">
							<h1 class="text-center text-uppercase">{{trans('messages.profile.profile')}}</h1>
						</div>
						<div class="pro-photo py-4 col-12 d-md-flex align-items-center justify-content-between text-center text-md-left">
							<div class="col-md-6">

								@if($driver_details)

								<h4>{{$driver_details->name}}</h4>

								@if($driver_details->status==1)

								<label class="active-label my-2">{{trans('messages.profile.active')}}</label>

								@elseif($driver_details->user->status==4)

								<label class="label my-2">{{trans('messages.profile.pending')}}</label>
								<label>{{trans('messages.profile.document_details')}}</label>
								<label>{{trans('messages.profile.vehicle_details')}}</label>
								@else
								<label>{{trans('messages.driver.'.$driver_details->user->status_text_show)}}</label>
								@endif
								@endif
							</div>
							<div class="col-md-6 mt-3 mt-md-0">
								<button type="button" class="btn btn-theme" ng-click="selectFile()">{{trans('messages.profile.add_photo')}}</button>

								<input type="file" ng-model="profile_image" style="display:none" accept="image/*" id="file" name='profile_image' accept=".jpg,.jpeg,.png" onchange="angular.element(this).scope().fileNameChanged(this)" />
							</div>
						</div>
						<div class="manage-doc text-center text-md-left py-4 col-12">
							<a class="m-1 m-md-0 d-inline-block" href="{{url('/driver/documents').'/'.$driver_details->id}}">
								<button type="button" class="btn btn-theme">{{trans('messages.profile.manage_documents')}}</button>
							</a>
							<a class="m-1 m-md-0 d-inline-block" href="{{route('driver.vehicle_details')}}">
								<button type="button" class="btn btn-theme">{{trans('messages.profile.vehicle_details')}}</button>
							</a>
						</div>

						{!! Form::open(['url'=>route('driver.profile'),'method'=>'post','class'=>'mt-4' , 'id'=>'profile_update_form'])!!}
						@csrf
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a">{{trans('messages.driver.first_name')}}</label>
							</div>
							<div class="col-md-7">
								<input type="text" name="first_name" placeholder="{{trans('messages.driver.first_name')}}" value="{{$driver_details->user->user_first_name}}">
								<span class="text-danger">{{ $errors->first('first_name') }}</span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a">{{trans('messages.driver.last_name')}}</label>
							</div>
							<div class="col-md-7">
								<input type="text" name="last_name" placeholder="{{trans('messages.driver.last_name')}}" value="{{$driver_details->user->user_last_name}}">
								<span class="text-danger">{{ $errors->first('last_name') }}</span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a">{{trans('messages.profile.email_address')}}</label>
							</div>
							<div class="col-md-7">
								<input type="text" name="email" placeholder="{{trans('messages.profile.email_address')}}" value="{{$driver_details->user->email}}">
								<span class="text-danger">{{ $errors->first('email') }}</span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a">{{trans('messages.driver.phone')}}</label>
							</div>
							<div class="col-md-7">
								<input type="text" name="mobile" placeholder="{{trans('messages.profile.phone_number')}}" value="{{$driver_details->user->mobile_number}}">
								<span class="text-danger">{{ $errors->first('mobile') }}</span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a">{{trans('messages.profile.address')}}</label>
							</div>
							<div class="col-md-7">
								<input type="text" name="address" placeholder="{{trans('messages.profile.address')}}" id="driver_address" value="{{($driver_details->user_address)?$driver_details->user_address->address:''}}">
								<span class="text-danger">{{ $errors->first('address') }}</span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label>{{trans('messages.driver.city')}}</label>
							</div>
							<div class="col-md-7">
								<input type="text" name="city" placeholder="{{trans('messages.driver.city')}}" id="city" value="{{($driver_details->user_address)?$driver_details->user_address->city:''}}">

								<input type="hidden" name="address_line_1" id="address_line_1" value="{{($driver_details->user_address)?$driver_details->user_address->street:''}}">
								<input type="hidden" name="state" id="state" value="{{($driver_details->user_address)?$driver_details->user_address->state:''}}">
								<input type="hidden" name="latitude" id="latitude" value="{{($driver_details->user_address)?$driver_details->user_address->latitude:''}}">
								<input type="hidden" name="longitude" id="longitude" value="{{($driver_details->user_address)?$driver_details->user_address->longitude:''}}">
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label>{{trans('messages.profile.country')}}</label>
							</div>
							<div class="col-md-7">
								<div class="select">
									<select name="country" id="country">

										@if($country_code)

										@foreach($country_code as $key=>$value)

										<option value="{{$value->code}}" {{($driver_details->user_address)?($value->code==$driver_details->user_address->country_code)?'selected':'':''}}>{{$value->name}}</option>

										@endforeach
										@endif

									</select>
								</div>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label>{{trans('messages.profile.postal_code')}}</label>
							</div>
							<div class="col-md-7">
								<input type="text" name="postal_code" placeholder="{{trans('messages.profile.postal_code')}}" value="{{($driver_details->user_address)?$driver_details->user_address->postal_code:''}}">
							</div>
						</div>
						<div class="profile-submit col-12 mt-4 pt-3">
							<button type="submit" class="btn btn-theme">{{trans('messages.profile.update')}}</button>
						</div>
						{{ Form::close() }}
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop