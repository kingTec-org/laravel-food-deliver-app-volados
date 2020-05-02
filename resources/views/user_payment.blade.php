@extends('template2')

@section('main')

<main id="site-content" role="main">
	<div class="container">
		<div class="profile user-payment py-5">
			<h1 class="text-center">{{trans('messages.profile.payment')}}</h1>
			<div class="d-md-flex">
				<div class="profile-img text-center col-12 col-md-3 col-lg-3">
					<img src="{{$profile_image}}"/>
					<h4>{{$user_details->name}}</h4>
				</div>
				<div class="profile-info col-12 col-md-8 col-lg-8 ml-lg-5 mt-4 offset-md-1 mt-md-0">
					<div class="methods">
						<div class="d-flex w-100 justify-content-between">
							<h3>{{trans('messages.profile.payment_methods')}}</h3>
							<a href="javascript:void(0)" class="theme-color method-btn"><i class="icon icon-add"></i></a>
						</div>
						<div class="method-info">
							<p>{{trans('messages.profile.new_payment_profile')}}</p>
						</div>
					</div>
					<div class="added-methods">
						@if($payment_details)
						<div class="added-info">
							<span><i class="icon icon-credit-card mr-2"></i> {{trans('messages.profile.card_number')}} : xxxxxxxxxxxx{{$payment_details->last4}}</span><br>
							<span><i class="icon icon-credit-card mr-2"></i> {{trans('messages.profile.card_type')}} : {{$payment_details->brand}}</span><br>
						</div>
						@endif
						<div class="added-info">
							<p><i class="icon icon-credit-card mr-2"></i>{{trans('messages.profile.personal_cash')}} ••••</p>
						</div>
					</div>
					@if(count($promo))
					<div class="promo-table">
						<div class="promo-head mt-3">
							<h3>{{trans('messages.profile.promo_details')}}</h3>
						</div>
						<div class="table-responsive">
							<table>
								<thead>
									<tr>
										<th>{{trans('messages.profile.promo_code')}}</th>
										<th>{{trans('messages.profile.amount')}}</th>
										<th>{{trans('messages.profile.percentage')}}</th>
										<th>{{trans('messages.profile.expired_date')}}</th>
									</tr>
								</thead>
								<tbody>
									@foreach($promo as $promo_detail)
									<tr>
										<td>
											{{$promo_detail->promo_code->code}}
										</td>
										<td>
											{{$promo_detail->promo_code->promo_type==0 ? currency_symbol().$promo_detail->promo_code->price:'' }}
										</td>
										<td>
											{{$promo_detail->promo_code->promo_type==1 ? $promo_detail->promo_code->percentage:'' }}
										</td>
										<td style="white-space: nowrap;">
											{{date('d-m-Y',strtotime($promo_detail->promo_code->end_date))}}
										</td>										
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					@endif
					<div class="promotions">
						<form action="{{route('add_promo_code_data',['page'=>'web'])}}" method="POST">
							@csrf
							<div class="d-md-flex align-items-center pt-4 justify-content-between">
								<h3>{{trans('messages.profile.promotions')}}</h3>
								<div class="promo-input mt-3 mt-md-0 d-flex">
									<input type="text" name="code" placeholder= {{trans('messages.profile.promo_code')}} value="">
									<button type="submit" class="btn btn-theme">{{trans('messages.profile.apply')}}</button>
								</div>
							</div>
							<span class="text-danger">{{ $errors->first('code') }}</span>
						</form>
						{{--
						<div class="active-info mt-4">
							<div class="active-head">
								<p>{{trans('messages.profile.active')}}</p>
							</div>
							<div class="active-content">
								<p>
									Congrats! You've been upgraded to {{site_setting('site_name')}} Premier! Pay with your HDFC Credit Card to avail 25% off, upto Rs 100, on 20 {{site_setting('site_name')}} Premier rides | Valid till 31 Jul 2018 | T&C Apply.
								</p>
							</div>
						</div>
						--}}
					</div>
					{{--
					<div class="tips mt-4">
						<h3>Gratuity for TAXI</h3>
						<p>Your preferred gratuity will be applied to any future TAXI trips that you request through {{site_setting('site_name')}}. This will apply to TAXI fares only (not Black, SUV, or  {{site_setting('site_name')}}X) and be paid to your driver. Gratuity only applies in certain countries.</p>
						<div class="d-md-flex align-items-center">
							<label class="m-0">TAXI Gratuity</label>
							<div class="select my-3 my-md-0 ml-md-3">
								<select>
									<option>0%</option>
									<option>20%</option>
									<option>40%</option>
									<option>60%</option>
									<option>80%</option>
									<option>100%</option>
								</select>
							</div>
							<button type="submit" class="btn btn-theme mt-md-0 ml-md-2">Save</button>
						</div>
					</div>
					--}}
				</div>
			</div>
		</div>
	</div>
</main>
@stop