@extends('driver.template')

@section('main')
<main id="site-content" role="main" class="log-user driver">
	<div class="container">
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h4 class="mb-4">2-Step Verification - SMS</h4>
			<p>Enter the 4-digit code sent to you</p>
			<form>
				<div class="form-group">	
					<label>Verification Code</label>
					<input type="text" name="" placeholder="Email or mobile number"/>
				</div>
				<button class="btn btn-arrow btn-theme w-100 mt-3 d-flex justify-content-between align-items-center text-uppercase" type="submit">Verify</button>
				<div class="mt-4">
					<p>Resend code by:
						<a href="javascript:void(0)" class="theme-color">SMS Voice</a>
					</p>
					<p>Having trouble? 
						<a href="{{route('help_page',current_page())}}" class="theme-color">Get help</a>
					</p>
				</div>
			</form>
		</div>
	</div>
</main>
@stop