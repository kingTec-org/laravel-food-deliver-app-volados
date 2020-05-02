@extends('template')

@section('main')
<main id="site-content" role="main" class="log-user">
	<div class="container">
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1>{{trans('messages.profile.create_an_account')}}</h1>
			<form>
				<div class="form-group">	
					<label>{{trans('messages.profile.enter_your_phone_number')}} ({{trans('messages.profile.required')}})</label>
					<div class="d-flex">
					<div class="select mob-select">
					<select>
						<option>+91</option>
						<option>+90</option>
						<option>+86</option>
						<option>+84</option>
					</select>
					</div>
					<input type="text" name="" placeholder=""/>
					</div>
				</div>
				<div class="form-group">	
					<label>{{trans('messages.profile.enter_your_email_address')}}</label>
					<input type="text" name="" placeholder=""/>
				</div>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit">{{trans('messages.profile.next_button')}} <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
@stop