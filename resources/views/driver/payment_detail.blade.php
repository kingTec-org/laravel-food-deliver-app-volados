@extends('driver.template')

@section('main')
<main id="site-content" role="main" class="log-user driver invoice-page" ng-controller="payment_detail">
<div class="container">
<div class="profile mb-5">
<div class="d-md-flex">
@include('driver.partner_navigation')
<div class="profile-info col-12 col-md-9 col-lg-9">
<div class="row d-block">

<div class="profile-title px-4 py-md-4">
<h1 class="text-center text-uppercase">{{trans('messages.driver.trip_payment')}}</h1>
{{--<p>Download invoices for trips made by you and your drivers. Please note that fares are subject to adjustments by Gofer based on client feedback. Your invoices will reflect those adjustments.</p>--}}
</div>
@if($time_detail!='' && $time_detail!=null)
<div class="profile-table my-3">
<div class="table-responsive">
<table>
<thead>
<tr>
<th colspan="2">
<center>
{{$time_detail['day']}} {{$time_detail['format_date']}} <br>
{!!$time_detail['currency_symbol']!!} {{$time_detail['total_fare']}}
</center>

</th>

</tr>
<tr>
<td>
{{trans('messages.driver.base_fare')}}</td>
<td>
{!!$time_detail['currency_symbol']!!} {{$time_detail['base_fare']}}

</td>
</tr>
<tr>
<td>{{trans('messages.driver.access_fee')}}</td>
<td>{!!$time_detail['currency_symbol']!!} {{$time_detail['access_fee']}}</td>
</tr>


<tr>
<td>
{{trans('messages.driver.bank_deposit')}}
</td>
<td>
{!!$time_detail['currency_symbol']!!} {{$time_detail['bank_deposits']}}
</td>
</tr>
<tr>
<td>
{{trans('messages.driver.completed_trip')}}
</td>
<td>
{{$time_detail['completed_trips']}}
</td>
</tr>

<tr>
<td>
<h6>{{trans('messages.driver.total_fare')}}</h6>
</td>

<td>
<h6>{!!$time_detail['currency_symbol']!!} {{$time_detail['total_fare']}}</h6>

</td>
</tr>
@if($time_detail['cash_collected']>0)
<tr>
<td><h6>{{trans('messages.driver.cash_collected')}}</h6></td>
<td><h6> {!!$time_detail['currency_symbol']!!} {{$time_detail['cash_collected']}}</h6>
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

<th>{{trans('messages.driver.trip_daily')}}</th>
<th>{{trans('messages.driver.invoice')}}</th>


</tr>
</thead>
<tbody>
@if($time_detail)

@foreach($time_detail['daily_statement'] as $key=>$value)
<tr data-target="#trip-info-{{$key}}" data-val="{{$key}}" data-count="{{count($time_detail['daily_statement'])}}" data-toggle="collapse" class="payment_detail collapsed" data-order_id="{{$value['id']}}">

<td><span class="theme-color text-nowrap">{{ date('h:i', strtotime($value['time'])).' '.trans('messages.driver.'.date('a', strtotime($value['time']))) }}</span></td>
<td>{!!$value['currency_symbol']!!} {{$value['total_fare']}}</td>

</tr>

<tr id="trip-info-{{$key}}" class="trip-info collapse" >
<td colspan="8">

<div class="d-md-flex justify-content-between">
<div class="trip-img mb-4">
<div>
<img src="@{{trip_details['map_image']}}" width="450px" height="150px">
</div>

<div class="trip-time text-left">
<div class="time-row">
<p> <i class="icon icon-z-dot-and-circle green"></i>
{{trans('messages.driver.pickup_location')}}</p>
<span>@{{trip_details['pickup_location']}}</span>
</div>
<div class="time-row">
<p> <i class="icon icon-z-dot-and-circle red"></i>
{{trans('messages.driver.drop_location')}}</p>
<span>@{{trip_details['drop_location']}}</span>
</div>
</div>
</div>
<div class="trip-tracking w-100 text-md-center">

<div class="profile-table my-3">
<div class="table-responsive">
<table>
<thead>
<tr>
<th colspan="2">
@{{trip_details['trip_date']}}
</th>
</tr>
<tr>
<td>
{{trans('messages.driver.order_id')}}
</td>
<td>
@{{trip_details['order_id']}}
</td>
</tr>
	<tr ng-if="trip_details['distance_fare']>0">
		<td>{{trans('messages.driver.distance_fare')}}</td>
		<td>
			<span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['distance_fare']}}
		</td>
	</tr>
	<tr ng-if="trip_details['pickup_fare']>0">
		<td>{{trans('messages.driver.pickup_fare')}}</td>
		<td>
			<span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['pickup_fare']}}
		</td>
	</tr>
	<tr ng-if="trip_details['drop_fare']>0">
		<td>{{trans('messages.driver.drop_fare')}}</td>
		<td>
			<span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['drop_fare']}}
		</td>
	</tr>
	<tr>
		<td>
			<h6>{{trans('messages.driver.total_trip_fare')}}</h6>
		</td>
		<td>
			<h6><span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['total_fare']}}</h6>
		</td>
	</tr>


	<tr>
		<td>{{trans('messages.driver.access_fee')}}</td>
		<td>
		-<span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['admin_payout']}}
		</td>
	</tr>


	<tr>
		<td>{{trans('messages.driver.driver_payout')}}</td>
		<td>
			<span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['driver_payout']}}
		</td>
	</tr>

	<tr ng-if="trip_details['owe_amount']!=0">
		<td>{{trans('messages.driver.owe_amount')}}</td>

		<td>
			<span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['owe_amount']}}

		</td>
	</tr>



	<tr ng-if="trip_details['cash_collected']!=0">
		<td >
			<h6>{{trans('messages.driver.cash_collected')}}</h6>
		</td>
		<td>
			<h6><span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['cash_collected']}}</h6>
		</td>
	</tr>
	<tr ng-if="trip_details['applied_owe']!=0">
		<td >
			<h6>{{trans('messages.driver.detected_owe_amount')}}</h6>
		</td>
		<td>
			<h6>-<span ng-bind-html="trip_details['currency_symbol']"> @{{trip_details['currency_symbol']}} </span>@{{trip_details['applied_owe']}}</h6>
		</td>
	</tr>
</thead>
<tbody>

</tbody>
</table>
</div>
</div>
</div>
</div>
</td>
</tr>

@endforeach
@else

<tr class="no-result d-none">
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