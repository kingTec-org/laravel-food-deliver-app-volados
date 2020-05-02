@extends('template2')

@section('main')

<main id="site-content" role="main" ng-controller="user_data">
	<div class="container">
		<div class="profile py-5">
			<h1 class="text-center">{{trans('messages.profile.profile')}}</h1>

			<div class="d-md-flex">
				<div class="profile-img text-center col-12 col-md-3 col-lg-3">
					<img src="{{$profile_image}}"/>
					<h4>{{$user_details->name}}</h4>
				</div>
				<div class="profile-info col-12 col-md-8 col-lg-8 ml-lg-5 mt-4 offset-md-1 mt-md-0 eater_profi">
					<h3>{{trans('messages.profile.general_information')}}</h3>

					<div class="row">
						<form class="mt-4 w-100" action="{{route('user_details_store')}}" method="POST" enctype="multipart/form-data">
						@csrf
							<div class="form-group d-md-flex">
								<div class="col-md-5">
									<label>{{trans('messages.profile.name')}}</label>
								</div>
								<div class="col-md-7">
									<input type="text" name="user_name" id="user_name" value="{{$user_details->name}}">
									 <span class="text-danger">{{ $errors->first('user_name') }}</span>
								</div>
							</div>
							<div class="form-group d-md-flex">
								<div class="col-md-5">
									<label>{{trans('messages.profile.location')}}</label>
								</div>
								<div class="col-md-7">
									<input type="text" name="user_address" id="user_address" value="{{($user_details->user_address)?$user_details->user_address->address:''}}" placeholder="{{trans('messages.profile.enter_a_location')}}">

									<input type="hidden" name="user_street" id="user_street" value="{{($user_details->user_address)?$user_details->user_address->street:''}}">
									<input type="hidden" name="user_city" id="user_city" value="{{($user_details->user_address)?$user_details->user_address->city:''}}">
									<input type="hidden" name="user_state" id="user_state" value="{{($user_details->user_address)?$user_details->user_address->state:''}}">
									<input type="hidden" name="user_country" id="user_country" value="{{($user_details->user_address)?$user_details->user_address->country:''}}">
									<input type="hidden" name="user_postal_code" id="user_postal_code" value="{{($user_details->user_address)?$user_details->user_address->postal_code:''}}">
									<input type="hidden" name="user_latitude" id="user_latitude" value="{{($user_details->user_address)?$user_details->user_address->latitude:''}}">
									<input type="hidden" name="user_longitude" id="user_longitude" value="{{($user_details->user_address)?$user_details->user_address->longitude:''}}">

									<span class="text-danger">{{ $errors->first('user_address') }}</span>
									<!-- <span class="text-danger">{{ $errors->first('user_city') }}</span>
									<span class="text-danger">{{ $errors->first('user_state') }}</span>
									<span class="text-danger">{{ $errors->first('user_country') }}</span> -->
								</div>
							</div>
							<div class="form-group d-md-flex">
								<div class="col-md-5">
									<label>{{trans('messages.profile.mobile')}}</label>
								</div>
								<div class="col-md-7">
									<span>+{{$user_details->country_code}}</span> {{$user_details->mobile_number}}
									{{--<span class="d-block mt-2">Not Verified (<a href="#">resend</a>)</span>--}}
								</div>
							</div>

							<div class="form-group d-md-flex">
								<div class="col-md-5">
									<label>{{trans('messages.profile.email_address')}}</label>
								</div>
								<div class="col-md-7">
									<p class="m-0">{{$user_details->email}}</p>
								</div>
							</div>

							<div class="form-group d-md-flex m-0">
								<div class="col-md-5">
									<label>{{trans('messages.profile.profile_photo')}}</label>
								</div>
								<div class="col-md-7">
									<input type="file" name="profile_photo" id="profile_photo" style="visibility:hidden;">
									<a class="choose_file_type" id="profile_choose_file"><span id="profile_name">{{trans('messages.profile.choose_file')}}</span></a>
									<span class="upload_text" id="file_text"></span>
									 <span class="text-danger">{{ $errors->first('profile_photo') }}</span>
								</div>
							</div>

							<div class="profile-submit text-center mt-3 pt-3">
								<button type="submit" id="user_detail_store" class="btn btn-theme">{{trans('messages.profile.save')}}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>    
@stop
@push('scripts')
<script type="text/javascript">

	$('#profile_choose_file').click(function(){
    	$('#profile_photo').trigger('click');
    	$('#profile_photo').change(function(evt) {
    			var fileName = $(this).val().split('\\')[$(this).val().split('\\').length - 1];
        		$('#profile_choose_file').css("background-color","#43A422");
        		$('#profile_choose_file').css("color","#fff");
        		$('#profile_name').text(Lang.get('js_messages.file.file_attached'));
        		$('#file_text').text(fileName);
        		$('span.upload_text').attr('title',fileName)
    		});
    });
</script>
@endpush