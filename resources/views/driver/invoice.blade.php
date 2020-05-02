@extends('driver.template')

@section('main')
<main id="site-content" role="main" class="log-user driver payment-page" ng-controller="invoice_detail">
	<div class="container">
		<div class="profile mb-5">
			<div class="d-md-flex">
			@include('driver.partner_navigation')
				<div class="profile-info col-12 col-md-9 col-lg-9">
					<div class="row d-block">
						<div class="profile-title py-md-4">
							<h1 class="text-center text-uppercase">Invoice</h1>
						</div>
						<div class="pro-photo py-4 col-12 d-md-flex align-items-center justify-content-between text-center text-md-left">
							<div class="col-md-6">
								<p>TOTAL EARNINGS</p>
								<h4>{!!$currency_symbol!!}  {{$total_earnings}}</h4>
							</div>
							{{--
							<div class="col-md-6 mt-3 mt-md-0 text-md-right">
								<p>PAY PERIOD</p>
								<div class="select">
									<select>
										<option>See all statements</option>
										<option>Current statements</option>
										<option>May 29 - Jun 5</option>
										<option>May 22 - May 29</option>
										<option>May 15 - May 22</option>
										<option>May 01 - May 8</option>
									</select>
								</div>
							</div>
							--}}
						</div>
						<div class="trip-percentage col-12 d-md-flex py-3 text-center text-md-left">
							<div class="col-md-4">
								<h6>{{$completed_trips}}</h6>
								<p class="text-uppercase">completed trips</p>
							</div>
							<div class="col-md-4">
								<h6>{{$acceptance_rate}}</h6>
								<p class="text-uppercase">acceptance trips</p>
							</div>
							<div class="col-md-4">
								<h6>{{$cancelled_trips}}</h6>
								<p class="text-uppercase">cancelled trips</p>
							</div>
						</div>
						<div class="manage-doc text-center text-md-left py-4 col-12">
							<h5 class="m-0">Daily Earnings</h5>
						</div>
						<div class="table-option d-md-flex my-4">
							<div class="col-md-6 d-md-flex">
								<input type="text" name="" placeholder="Start Date" id="begin_date">
								<input type="text" name="" placeholder="End Date" id="end_date">
							</div>
							<div class="col-md-6 d-md-flex align-items-center">
								<span class="mr-3">Status</span>
								<div class="select w-100">
									<select id="trip_select">
										<option value="all_trips">All Trips</option>
										<option value="completed_trips">Completed Trips</option>
										<option value="cancelled_trips">Cancelled Trips</option>
									</select>
								</div>
							</div>
						</div>
						<div class="profile-table my-3">
							<div class="table-responsive">
								<table>
									<thead>
										<tr>
											<th>Pickup Time</th>
											<th>Vehicle</th>
											{{--<th>Duration</th>
											<th>Distance (km)</th>--}}
											<th>Total Earnings</th>
										</tr>
									</thead>
									<tbody id="invoice_data">
										@if(count($history_details['today_delivery'])>0)
										<span ng-init=" trip_details = {{$history_details['today_delivery']}}"></span>
										<tr class="trip-origin" ng-repeat = "trip in trip_details">
											<td id="date">@{{trip['updated_at']}}</td>
											<td id="vehicle_name">@{{trip['vehicle_type_name']}}</td>
											<td id="total_fare">{!!$currency_symbol!!} @{{trip['total_fare']}}</td>
										</tr>
										@else
										<tr class="no-result">
											<td colspan="6">No Details Found</td>
										</tr>
										@endif
									</tbody>
								</table>
							</div>
							@if(count($history_details['today_delivery'])>0)
							<div class="table-pagination text-right my-4">
								<a href="javascript:void(0)">
									<i class="icon icon-angle-arrow-pointing-to-right-1 custom-rotate"></i>
								</a>
								<a href="javascript:void(0)">
									<i class="icon icon-angle-arrow-pointing-to-right-1"></i>
								</a>
							</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop