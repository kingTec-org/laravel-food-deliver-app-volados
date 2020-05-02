@extends('driver.template')

@section('main')
<main id="site-content" role="main" class="log-user driver invoice-page">
	<div class="container">
		<div class="profile mb-5">
			<div class="d-md-flex">
			@include('driver.partner_navigation')
				<div class="profile-info col-12 col-md-9 col-lg-9">
					<div class="row d-block">

						<div class="profile-title px-4 py-md-4">
							<h1 class="text-center text-uppercase">{{ trans('messages.driver.trip_payment') }}</h1>
							{{--<p>Download invoices for trips made by you and your drivers. Please note that fares are subject to adjustments by Gofer based on client feedback. Your invoices will reflect those adjustments.</p>--}}
						</div>
						@if($trip_day!='' && $trip_day!=null)
							<div class="profile-table my-3">
								<div class="table-responsive">
									<table>
										<thead>
											<tr>
												<th colspan="2">
													<center>
														{{$trip_day['format_date']}} <br>
														{!!$trip_day['currency_symbol']!!} {{$trip_day['total_fare']}}
													</center>

												</th>

											</tr>
											<tr>
												<td>
													{{ trans('messages.driver.base_fare') }}
												</td>
												<td>
													{!!$trip_day['currency_symbol']!!} {{$trip_day['base_fare']}}

												</td>
											</tr>
											<tr>
												<td>{{ trans('messages.driver.access_fee') }}</td>
												<td>{!!$trip_day['currency_symbol']!!} {{$trip_day['access_fee']}}</td>
											</tr>


											
											<tr>
												<td>
													{{ trans('messages.driver.completed_trip') }}
												</td>
												<td>
													{{$trip_day['completed_trips']}}
												</td>
											</tr>
											<tr>
												<td>
													<h6>{{ trans('messages.driver.total_fare') }}</h6>
												</td>

												<td>
												 <h6>{!!$trip_day['currency_symbol']!!} {{$trip_day['total_fare']}}</h6>

												</td>
											</tr>
											<tr>
												<td>
													{{ trans('messages.driver.bank_deposit') }}
												</td>
												<td>
													{!!$trip_day['currency_symbol']!!} {{$trip_day['bank_deposits']}}
												</td>
											</tr>
											@if($trip_day['cash_collected']>0)

											<tr>
												<td><h6>{{ trans('messages.driver.cash_collected') }}</h6></td>
												<td> <h6> {!!$trip_day['currency_symbol']!!} {{$trip_day['cash_collected']}}</h6>
												</td>
											</tr>
											@endif
										</thead>
										<tbody>

										</tbody>
									</table>
								</div>
							</div>
						@endif

						<div class="profile-table my-3">
							<div class="table-responsive">
								<table>
									<thead>
										<tr>

											<th>{{ trans('messages.driver.trip_daily') }}</th>
											<th>{{ trans('messages.driver.invoice') }}</th>


										</tr>
									</thead>
									<tbody>
										@if($trip_day_details)

											@foreach($trip_day_details as $key=>$value)
												<tr>

													<td><a href="{{url('driver/detail_payment').'/'.$value['date_format']}}"><span class="theme-color text-nowrap">{{$value['date']}}</span></a></td>
													<td>{!!$value['currency_symbol']!!} {{$value['total_fare']}}</td>

												</tr>

											@endforeach
										@else

											<tr class="no-result d-none">
												<td colspan="5">No Details Found</td>
											</tr>

										@endif
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop