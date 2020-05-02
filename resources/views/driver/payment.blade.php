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
							<h1 class="text-center text-uppercase">{{trans('messages.driver.trip_payment')}}</h1>

						</div>
						<div class="profile-table my-3">
							<div class="table-responsive">

								<div class="pro-photo py-4 col-12 d-md-flex align-items-center justify-content-between text-center text-md-left">
							<div class="col-md-6">
								<p>{{trans('messages.driver.total_earnings')}}</p>
                               <h4>{!!$currency_symbol!!}  {{$total_earnings}}</h4>
							</div>

						</div>

							<div class="trip-percentage col-12 d-md-flex py-3 text-center text-md-left">
							<div class="col-md-4">
								<h6>{{$completed_trips}}</h6>
								<p class="text-uppercase">{{trans('messages.driver.completed_trips')}}</p>
							</div>
							<div class="col-md-4">
								<h6>{{$acceptance_rate}}</h6>
								<p class="text-uppercase">{{trans('messages.driver.acceptance_trips')}}</p>
							</div>
							<div class="col-md-4">
								<h6>{{$cancelled_trips}}</h6>
								<p class="text-uppercase">{{trans('messages.driver.cancelled_trips')}}</p>
							</div>
						</div>

								<table>
									<thead>
										<tr>
											<th>{{trans('messages.driver.trip_week')}}</th>
											<th>{{trans('messages.profile.earnings')}}</th>
										</tr>
									</thead>
									<tbody>


										@if($trip_week_details)

										@foreach($trip_week_details as $key=>$value)
										<tr>
											<td><a href="{{url('driver/daily_payment').'/'.$value['week_format']}}"><span class="theme-color text-nowrap">{{$value['week']}}</span></a></td>
											<td>{!!$value['currency_symbol']!!} {{$value['total_fare']}}</td>
										</tr>
										@endforeach
										@else
										<tr class="no-result">
											<td colspan="5">{{trans('messages.driver.no_details_found')}}</td>
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