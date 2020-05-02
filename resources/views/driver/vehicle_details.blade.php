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
		<div class="profile mb-5">
			<div class="d-md-flex">
			@include('driver.partner_navigation')
				<div class="profile-info col-12 col-md-9 col-lg-9">

					<div class="row d-block">
						<div class="profile-title py-md-4">
							<h1 class="text-center text-uppercase">{{trans('messages.profile.vehicle_details')}}</h1>
						</div>


						{!! Form::open(['url'=>route('driver.vehicle_details'),'method'=>'post','class'=>'mt-4' , 'id'=>'vehicle_update_form'])!!}
						@csrf
							<div class="form-group d-md-flex">
								<div class="col-md-5">
									<label class="required-a">{{trans('messages.driver.vehicle_name')}}</label>
								</div>
								<div class="col-md-7">
									<input type="text" name="vehicle_name" placeholder="{{trans('messages.driver.vehicle_name')}}" value="{{($driver_details->driver)?$driver_details->driver->vehicle_name:''}}">
									<span class="text-danger">{{ $errors->first('vehicle_name') }}</span>
								</div>
							</div>
							<div class="form-group d-md-flex">
								<div class="col-md-5">
									<label class="required-a">{{trans('messages.driver.vehicle_number')}}</label>
								</div>
								<div class="col-md-7">
									<input type="text" name="vehicle_number" placeholder="{{trans('messages.driver.vehicle_number')}}" value="{{($driver_details->driver)?$driver_details->driver->vehicle_number:''}}">
									<span class="text-danger">{{ $errors->first('vehicle_number') }}</span>
								</div>
							</div>

							<div class="form-group d-md-flex">
								<div class="col-md-5">
									<label class="required-a">{{trans('messages.driver.vehicle_type')}}</label>
								</div>
								<div class="col-md-7">
									<div class="select">
										<select name="vehicle_type" id="vehicle_type">
											<option value="">{{trans('messages.store_dashboard.select')}}</option>
											@if($vehicle)

												@foreach($vehicle as $key=>$value)

													<option value="{{$value->id}}" {{($driver_details->driver)?($value->id==$driver_details->driver->vehicle_type)?'selected':'':''}}>{{$value->name}}</option>

												@endforeach
											@endif

										</select>
									</div>
									<span class="text-danger">{{ $errors->first('vehicle_type') }}</span>
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