@extends('driver.template')

@section('main')
<main id="site-content" role="main" class="log-user driver trip-page">
	<div class="container">
		<div class="profile mb-5">
			<div class="d-md-flex">

				@include('driver.partner_navigation')

				<div class="profile-info col-12 col-md-9 col-lg-9">
					<div class="row d-block">
						<div class="col-12 d-md-flex align-items-center">
							<div class="col-md-4 col-lg-3">
								<span class="filter-trip d-none d-md-inline-block theme-color" data-toggle="collapse" data-target="#trip-accordion" aria-expanded="true" aria-controls="trip-accordion">
									<i class="icon icon-z-equalizer mr-2">
									</i>{{trans('messages.driver.filter_trips')}}
								</span>
							</div>
							<div class="col-md-5 profile-title py-md-4">
								<h1 class="text-center text-uppercase">{{trans('messages.profile.my_trips')}}</h1>
							</div>
						</div>

						<div class="my-4 text-center">
							<span class="filter-trip d-inline-block d-md-none theme-color" data-toggle="collapse" data-target="#trip-accordion" aria-expanded="true" aria-controls="trip-accordion">
								<i class="icon icon-z-equalizer mr-2">
								</i>{{trans('messages.driver.filter_trips')}}
							</span>
						</div>

						<div id="trip-accordion" class="trip-toggle collapse mb-5">
							<div class="card">
								<div class="card-body">

								<form action="{{route('driver.trips')}}" method="POST">
								@csrf
									<div class="d-md-flex align-items-center row time-frame">
										<div class="col-md-4 col-lg-3 mt-4 mb-2 my-md-0">
											<span>{{trans('messages.driver.timeframe')}} </span>
										</div>
										<div class="col-md-8 col-lg-9">
											<ul class="trip-months">
												<li>
													<label>
														<input type="radio" name="month" {{  ( date("m") == $month ) ? 'checked' : '' }} value="{{ date("m") }}" />
														<button type="button" class="btn btn-primary">
															{{trans('messages.driver.'.date("F")) }}
														</button>
													</label>
												</li>
												<li>
													<label>
														<input type="radio" name="month" {{  ( date("m",strtotime("-1 Months")) == $month ) ? 'checked' : '' }} value="{{ date("m",strtotime("-1 Months")) }}" />
														<button type="button" class="btn btn-primary">
															{{trans('messages.driver.'.date("F",strtotime("-1 Months"))) }}
														</button>
													</label>
												</li>
												<li>
													<label>
														<input type="radio" name="month" {{  ( date("m",strtotime("-2 Months")) == $month ) ? 'checked' : '' }} value="{{ date("m",strtotime("-2 Months")) }}"/>
														<button type="button" class="btn btn-primary">
															{{trans('messages.driver.'.date("F",strtotime("-2 Months"))) }}
														</button>
													</label>
												</li>
											</ul>
										</div>
									</div>

									<input type="submit"  value="{{trans('messages.driver.filter_trips')}}" class="w-100 btn btn-theme my-2 my-md-4">


</form>
								</div>
							</div>

						</div>

						<div class="profile-table my-3">
							<div class="table-responsive">
								<table>
									<thead>
										<tr>
											<th></th>
											<th>{{trans('messages.driver.pickup')}}</th>
											<th>{{trans('messages.driver.rider')}}</th>
											<th>{{trans('messages.driver.fare')}}</th>
											<th>{{trans('messages.driver.car')}}</th>
											<th>{{trans('messages.profile.location')}}</th>
											<th>{{trans('messages.profile_orders.payment_method')}}</th>
										</tr>
									</thead>
									<tbody>
										@if(count($history_details['today_delivery'])>0)
										@foreach($history_details['today_delivery'] as $key=>$value)
										<tr class="trip-origin" data-toggle="collapse" data-target="#trip-info-{{$key}}" aria-expanded="false" aria-controls="table-accordion">
											<td><i class="icon theme-color icon-angle-arrow-pointing-to-right-1"></i></td>
											<td>{{$value['date']}}</td>
											<td>{{$value['driver']}}</td>
											<td class="fare"><span>{!!$history_details['currency_symbol']!!} {{$value['total_fare']}}</span>
												<span>{{trans('messages.profile_orders.'.$value['status'])}}</span>
											</td>
											<td>{{$value['vehicle_name']}}</td>
											<td>{{$value['drop_address']}}</td>
											<td>{{trans('messages.profile_orders.'.$value['payment_method'])}}</td>
										</tr>
										<tr id="trip-info-{{$key}}" class="trip-info collapse">
											<td colspan="8">
												<div class="d-lg-flex justify-content-between">
													<div class="trip-img mb-4">
														<img src="{{$value['map_image']}}">
													</div>
													<div class="trip-tracking text-md-center">
														<h5>{!!$history_details['currency_symbol']!!} {{$value['total_fare']}}</h5>
														<p>{{trans('messages.profile_orders.'.$value['payment_method'])}}</p>
														<div class="trip-day mt-3 py-3">
															<span>{{trans('messages.driver.order_id')}}: {{$value['id']}}</span><br>
															<span>{{$value['date1']}}</span>
														</div>
														<div class="trip-time text-left">
															<div class="time-row">
																<i class="icon icon-z-dot-and-circle green"></i>
																<p>{{$value['pickup_time']}}</p>
																<span>{{$value['pickup_address']}}</span>
															</div>
															<div class="time-row">
																<i class="icon icon-z-dot-and-circle red"></i>
																<p>{{$value['drop_time']}}</p>
																<span>{{$value['drop_address']}}</span>
															</div>
														</div>
													</div>
													{{--
													<div class="trip-btn py-4 mt-4 text-md-center w-100">
														<a href="javascript:void(0)" class="btn btn-theme text-capitalize">
															<i class="icon icon-search-3"></i>
															{{trans('messages.driver.view_detail')}}
														</a>
													</div>
													--}}
												</td>
											</tr>
											@endforeach
											@else
											<tr class="no-result">
												<td></td>
												<td colspan="6">{{trans('messages.driver.no_details_found')}}</td>
											</tr>
											@endif
										</tbody>
									</table>
								</div>

								<div class="table-pagination text-right my-4">
									{{ $links }}
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	@stop