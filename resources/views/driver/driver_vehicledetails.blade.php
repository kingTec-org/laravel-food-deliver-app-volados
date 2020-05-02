@extends('driver.template')

@section('main')
<div class="flash-container">
  @if(Session::has('message'))
  <div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
    <a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
  </div>
  @endif
</div>
<main id="site-content" role="main" class="vechile_details" ng-controller="driver_signup">
  <div class="signup-page">
    <div class="container">
      <div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
        <h1>Enter your vehicle information</h1>
        {!! Form::open(['url'=>route('driver.signup'),'method'=>'post','class'=>'mt-4' , 'id'=>'signup_form2'])!!}
        @csrf
        <input type="hidden" name="user_type" value="Driver">
        <div class="form-group">
          <label class="" for="input-email">Vehicle Name / eg: Toyoto Camry,Honda Accord...</label>
          {!! Form::text('vehicle_name', '', ['class' => '','placeholder' => 'Vehicle Name / eg: Toyoto Camry,Honda Accord...','id' => 'input-email','style' => 'margin:0px !important' ]) !!}
          <span class="text-danger">{{ $errors->first('vehicle_name') }}</span>
        </div>

        <div class="form-group">
          <label class="" for="input-email">Vehicle Number / eg: WNF 382</label>
          {!! Form::text('vehicle_number', '', ['class' => '','placeholder' => 'Vehicle Number / eg: WNF 382','id' => 'input-email','style' => 'margin:0px !important' ]) !!}
          <span class="text-danger">{{ $errors->first('vehicle_number') }}</span>
        </div>

        <div class="form-group">
          <div class="select-cls">
            <label class="" for="input-email">Choose your vehicle type</label>
            <div class="select">
              <select name="vehicle_type" id="vehicle_type" class="">
                <option value="" selected="" disabled="">Choose your vehicle type</option>
                @if($vehicle_type)
                @foreach($vehicle_type as $key => $value)
                <option value="{{$value->id}}" >{{ $value->name}}
                </option>
                @endforeach
                @endif
              </select>
            </div>
            <span class="text-danger">{{ $errors->first('vehicle_type') }}</span>
          </div>
        </div>

        <button id="submit-btn" type="submit" name="step" value="vehicle_details" class="btn btn-theme btn-arrow w-100 text-left text-uppercase">
          <span class="text-center">Continue</span>
          <i class="fa fa-long-arrow-right icon icon_right-arrow-thin pull-right"></i>
        </button>
        {{ Form::close() }}
      </div>
    </div>
  </div>
</main>
@stop
