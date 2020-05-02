@if ((Route::current()->uri() == 'driver/signup' || Route::current()->uri() == 'driver') & Route::current()->uri() !== 'login' & Route::current()->uri() !== 'driver/password')
<header class="driver driver-header">
	<div class="container">
		<div class="d-flex align-items-center justify-content-between">
			<div class="logo">
				<a href="{{route('driver.home')}}">
					<img class="d-none d-md-inline-block" src="{{site_setting('1','7')}}">
					<img class="d-inline-block d-md-none" src="{{site_setting('1','8')}}">
				</a>
			</div>

			<!-- <a href="javascript:void(0)" class="link-arrow text-uppercase theme-color">eat with gofer
			</a> -->

			<a class="nav-link btn btn-primary" href="{{route('driver.login')}}">{{trans('messages.profile.sign_in')}}</a>
		</div>
	</div>
</header>
@endif

@if (Route::current()->uri() == 'driver/login' || Route::current()->uri() == 'driver/login_session' || Route::current()->uri() == 'driver/password')
<header class="driver driver-header login">
	<div class="site-pattern d-none d-md-block">
		<svg xmlns="http://www.w3.org/2000/svg">
			<defs>
				<pattern id="a___-1531234641" width="60" height="60" patternUnits="userSpaceOnUse">
					<path class="pattern-stroke" d="M11.5 39.8L0 51.2 8.8 60h12.4l8.8-8.8-11.5-11.4c-1.9-2-5.1-2-7 0z"></path>
					<path class="pattern-stroke" d="M30 38.8L18.5 50.3c-2 2-5.1 2-7.1 0L0 38.8 8.8 30h12.4l8.8 8.8zm11.5 1L30 51.2l8.8 8.8h12.4l8.8-8.8-11.5-11.4c-1.9-2-5.1-2-7 0z"></path>
					<path class="pattern-stroke" d="M60 38.8L48.5 50.3c-2 2-5.1 2-7.1 0L30 38.8l8.8-8.8h12.4l8.8 8.8zm-48.5-29L0 21.2 8.8 30h12.4l8.8-8.8L18.5 9.8c-1.9-2-5.1-2-7 0z"></path>
					<path class="pattern-stroke" d="M30 8.8L18.5 20.3c-2 2-5.1 2-7.1 0L0 8.8 8.8 0h12.4L30 8.8zm11.5 1L30 21.2l8.8 8.8h12.4l8.8-8.8L48.5 9.8c-1.9-2-5.1-2-7 0z"></path>
					<path class="pattern-stroke" d="M60 8.8L48.5 20.3c-2 2-5.1 2-7.1 0L30 8.8 38.8 0h12.4L60 8.8z"></path>
				</pattern>
			</defs>
			<rect fill="url(#a___-1531234641)" height="100%" width="100%"></rect>
		</svg>
	</div>
	<div class="container">
		<div class="logo mt-md-4 text-center">
			<a href="./" class="d-inline-block">
				<img class="d-none d-md-inline-block" src="{{site_setting('1','7')}}">
				<img class="d-inline-block d-md-none" src="{{site_setting('1','8')}}">
			</a>
		</div>
	</div>
</header>
@endif

@if (Route::current()->uri() == 'driver/profile' || Route::current()->uri() == 'driver/payment' || Route::current()->uri() == 'driver/invoice' || Route::current()->uri() == 'driver/trips' || Route::current()->uri() == 'driver/trip_detail' || Route::current()->uri() == 'driver/documents' || Route::current()->uri() == 'driver/daily_payment/{date}' || Route::current()->uri() == 'driver/detail_payment/{date}' || Route::current()->uri() == 'driver/vehicle_details' || Route::current()->uri() == 'driver/documents/{id}' )
<header class="driver driver-header driver-info">
	<div class="container">
		<div class="driver-info-head clearfix">
			<div class="logo d-flex justify-content-center align-items-center">
				<a href="{{route('driver.profile')}}">
					<img src="{{site_setting('1','8')}}">
				</a>
			</div>
			<nav class="navbar navbar-expand-md px-0 float-md-right">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="icon-bar"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<button class="navbar-toggler d-block d-md-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
					</button>
					<ul class="navbar-nav mr-auto">
						<li class="nav-item dropdown">
							<a class="nav-link d-inline-block align-middle user-name p-0 dropdown-toggle" data-toggle="dropdown" href="#">
								<i class="d-inline-block user-icon mr-1">
									@if(@Auth::guard('driver') == '')
									<img src="{{url('/')}}/images/user.png" class="profile_picture"/>
									@else
									<img src="{{@Auth::guard('driver')->user()->driver->driver_profile_picture}}" class="profile_picture"/>
									@endif
								</i>
								@if(@Auth::guard('driver') != '')
								{{@Auth::guard('driver')->user()->user_first_name}} {{@Auth::guard('driver')->user()->user_last_name}}
								@endif
								<i class="icon icon-sort-down ml-1"></i>
							</a>
							<div class="dropdown-menu">
								<ul class="navbar-nav mr-auto">
									<li class="nav-item">
										<a class="nav-link" href="{{route('driver.profile')}}">
											<i class="icon icon-user"></i>
											{{trans('messages.profile.profile')}}
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="{{route('driver.payment')}}">
											<i class="icon icon-credit-card"></i>
											{{trans('messages.profile.earnings')}}
										</a>
									</li>
									<!-- <li class="nav-item">
										<a class="nav-link" href="{{route('driver.invoice')}}">
											<i class="icon icon-document"></i>
											Invoice
										</a>
									</li> -->
									<li class="nav-item">
										<a class="nav-link" href="{{route('driver.trips')}}">
											<i class="icon icon-trip"></i>
											{{trans('messages.profile.my_trips')}}
										</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="{{route('driver.logout')}}">
											<i class="icon icon-logout"></i>
											{{trans('messages.profile.log_out')}}
										</a>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</nav>
		</div>
	</div>
</header>
@endif