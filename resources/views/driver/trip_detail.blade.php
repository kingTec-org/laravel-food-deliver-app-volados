@extends('driver.template')

@section('main')
<main id="site-content" role="main" class="log-user driver trip-detail-page">
	<div class="container">
		<div class="profile mb-5">
			<div class="d-md-flex">
				<div class="profile-img text-center col-12 col-md-3 col-lg-3 d-none d-md-block">
					<img src="{{url('/')}}/images/user.png"/>
					<h4>John</h4>
					<div class="pro-nav">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item active">
								<a class="nav-link" href="#">Profile</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Payment</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Invoice</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">My Trip</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Log Out</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="profile-info col-12 col-md-9 col-lg-9">
					<div class="row d-block">
						<div class="profile-title py-md-4 text-center">
							<h1 class="text-uppercase">YOUR TRIP</h1>
							<span>05:46 AM ON JUNE 01, 2018</span>
						</div>
						<div class="manage-doc text-center py-4 col-12">
							<button type="button" class="btn btn-theme text-capitalize">
								<i class="icon icon-download-button mr-2"></i>
								download invoice
							</button>
						</div>
						<div id="trip-info" class="trip-info mt-4 d-md-flex">
							<div class="trip-left col-md-6">
								<div class="trip-img">
									<img src="../images/map.png">
								</div>
								<div class="trip-detail-info">
									<div class="trip-tracking w-100 text-md-center">
										<div class="trip-time text-left">
											<div class="time-row">
												<i class="icon icon-z-dot-and-circle green"></i>
												<p>05:46 AM</p>
												<span>12/9, Ranan Nagar, Madurai, Tamil Nadu 625020, India</span>
											</div>
											<div class="time-row">
												<i class="icon icon-z-dot-and-circle red"></i>
												<p>05:46 AM</p>
												<span>12/9, Ranan Nagar, Madurai, Tamil Nadu 625020, India</span>
											</div>
										</div>
									</div>
									<div class="trip-km pt-4 mt-4 d-lg-flex text-center">
										<div class="col-lg-4">
											<span>CAR</span>
											<p>{{site_setting('site_name')}}XL</p>
										</div>
										<div class="col-lg-4">
											<span>KILOMETERS</span>
											<p>0.00</p>
										</div>
										<div class="col-lg-4">
											<span>TRIP TIME</span>
											<p>00:00:12</p>
										</div>
									</div>
								</div>
							</div>
							<div class="fare-table col-md-6 mt-5 mt-md-0">
								<h5 class="text-uppercase text-center">fare breakdown</h5>
								<div class="fare-row d-flex justify-content-between">
									<span class="col-6">Payment Mode</span>
									<span class="col-6 text-right">PayPal</span>
								</div>
								<div class="fare-row d-flex justify-content-between">
									<span class="col-6">Base Fare</span>
									<span class="col-6 text-right">$ 80.00</span>
								</div>
								<div class="fare-row d-flex justify-content-between">
									<span class="col-6">Distance Fare</span>
									<span class="col-6 text-right">$ 0.00</span>
								</div>
								<div class="fare-row d-flex justify-content-between">
									<span class="col-6">Time Fare</span>
									<span class="col-6 text-right">$ 0.00</span>
								</div>
								<div class="fare-row d-flex justify-content-between">
									<strong class="col-6">Total Trip Fare</strong>
									<strong class="col-6 text-right">$ 80.00</strong>
								</div>
								<div class="fare-row d-flex justify-content-between">
									<span class="col-6">Owe Amount</span>
									<span class="col-6 text-right">- $ 39.33</span>
								</div>
								<div class="fare-row d-flex justify-content-between">
									<strong class="col-6">Total Payout</strong>
									<strong class="col-6 text-right">$ 40.67</strong>
								</div>
							</div>
						</div>
						<div class="ride-user col-12 align-items-center text-center text-md-left py-4 mt-4 d-md-flex">
							<img class="mr-md-4 mb-3 mb-md-0" src="../images/user.png"/>
							<span class="d-block d-md-inline-block">You rode with John</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop