@extends('template2')

@section('main')
<main id="site-content" role="main" ng-init="order_data = {{json_encode($order_detail_data)}};other_store='no';schedule_time_value={{json_encode(time_data('schedule_time'))}};schedule_date_value='{{session('schedule_data')['date']?:date('Y-m-d')}}';schedule_time_set='{{session('schedule_data')['time']}}'" ng-controller="stores_detail" class="place_order_change">
	<div class="checkout-content py-4 pt-md-5" ng-init="date='{{date('d M',strtotime($schedule_data['date']))}}'">
		<div class="container" ng-init="time='{{date('h:i A',strtotime($schedule_data['time']))}}'">
			<div class="clearfix">
				<div class="checkout-detail col-12 col-md-7 col-lg-8 pl-0 pr-0 pr-md-4 float-left">
					<h1>{{ trans('admin_messages.checkout') }}</h1>
					<div class="panel-group">

						<input type="hidden" id='user_check' value="{{is_user()}}">
						@if(!is_user())
						<div class="card done user_check1">
							<div class="card-header">
								<h3>1. {{ trans('messages.profile.register') }} {{ trans('messages.store.or') }} {{ trans('messages.driver.log_in') }}</h3>
							</div>
							<div class="card-body">
								<p>{{ trans('messages.store.new_to_registration', ['site_name'=>site_setting('site_name')]) }} {{ trans('messages.store.if_you_already_use', ['site_name'=>site_setting('site_name')]) }}</p>
								<div class="card-btn mt-4">
									<a href="{{route('login')}}" class="btn btn-primary mr-2">{{ trans('admin_messages.login') }}</a>
									<a href="{{route('signup')}}" class="btn btn-theme">{{ trans('messages.profile.register') }}</a>
								</div>
							</div>
						</div>
						@else
						<div class="card active user_check2">
							<div class="card-header">
								<h3>2. {{ trans('admin_messages.delivery_time') }}</h3>
							</div>
							<div class="card-body schedule3" ng-init="status='{{$schedule_data['status']}}'">
								<p>
									<i class="icon icon-clock mr-1"></i>
									<span ng-if="status!='ASAP'">
										<span id="date1" ng-model="date">@{{date}} </span>
										<span id="time1" ng-model="time">@{{time}}</span>
									</span>
									<span id="schedule2"></span>
									<span ng-if="status=='ASAP'" id='possible'>
										{{ trans('admin_messages.as_soon_as_possible') }}
									</span>

									<time>{{$store_details->convert_mintime}}–{{$store_details->convert_maxtime}} {{trans('messages.store.min')}}</time>
									<a href="#" class="float-right schedule-btn theme-color" data-toggle="modal" data-target="#schedule-modal">{{ trans('messages.store.schedule') }}</a>
								</p>
							</div>
						</div>
						<div class="card active user_check2">
							<div class="card-header">
								<h3>3. {{ trans('messages.store.confirm_location') }}</h3>
							</div>
							<div class="card-body d-block d-md-flex text-center text-md-left">
								<div class="check-img d-inline-block">
									<img width="100%" height="200" src="https://maps.googleapis.com/maps/api/staticmap?size=200x175&amp;center={{ session('latitude') }},{{ session('longitude') }}&amp;zoom=15&amp;maptype=roadmap&amp;sensor=false&key={{ $map_key }}&amp;markers=icon:images/map-pin-set-3460214b477748232858bedae3955d81.png%7C{{ session('latitude') }},{{ session('longitude') }}">
								</div>
								<div class="check-location w-100 pl-0 pl-md-4 mt-4 mt-md-0">
									<div class="form-group">
										<div class="search-input">
											<svg width="16px" height="16px" viewBox="0 0 16 16" version="1.1"><g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Icons-/-Semantic-/-Location" stroke="#262626"><g id="Group" transform="translate(1.500000, 0.000000)"><path d="M6.5,15.285929 L10.7392259,10.9636033 C13.0869247,8.56988335 13.0869247,4.68495065 10.7392259,2.29123075 C8.39683517,-0.0970769149 4.60316483,-0.0970769149 2.26077415,2.29123075 C-0.0869247162,4.68495065 -0.0869247162,8.56988335 2.26077415,10.9636033 L6.5,15.285929 Z" id="Combined-Shape"></path><circle id="Oval-3" cx="6.5" cy="6.5" r="2"></circle></g></g></g></svg>
											<input type="text" class="w-100 text-truncate" placeholder="{{ trans('messages.store.enter_your_address') }}" id="confirm_address" value="{{session('locality')}}" />

											<input type="hidden" id="store_id" value="{{$store_details->id}}">

											<input type="hidden" name="order_city" id="order_city" value="{{session('city')}}">
											<input type="hidden" name="order_street" id="order_street" value="{{session('city')}}">
											<input type="hidden" name="order_state" id="order_state" value="{{session('state')}}">
											<input type="hidden" name="order_country" id="order_country" value="{{session('country')}}">
											<input type="hidden" name="order_postal_code" id="order_postal_code" value="{{session('postal_code')}}">
											<input type="hidden" name="order_latitude" id="order_latitude" value="{{session('latitude')}}">
											<input type="hidden" name="order_longitude" id="order_longitude" value="{{session('longitude')}}">
											<input type="hidden" name="order_type" id="order_type" value="{{$schedule_data['status']=='ASAP'?0:1}}">
											<input type="hidden" name="delivery_time" id="delivery_time" value="{{$schedule_data['status']!='ASAP'?$schedule_data['date'].' '.$schedule_data['time']:''}}">
										</div>
										<p id="error_place_order" class="mt-2 error_place_order" style="display: none;color:red">{{ trans('messages.store.location_is_required') }}</p>
									</div>
									<div class="form-group">
										<input type="text" placeholder="{{ trans('messages.store.apt_suite_floor') }}" name="" id="suite">
									</div>
									<div class="form-group">
										<input type="text" placeholder="{{ trans('messages.store.add_delivery_note') }}" name="" id="delivery_note">
									</div>
								</div>
							</div>
						</div>
						<div class="card active user_check2 promo_loading">
							<div class="card-header">
								<h3>4. {{ trans('messages.profile.payment') }}</h3>
							</div>
							<div class="card-body" ng-inoi>
								<select id="payment_method" ng-model="payment_method">
									<option value="0">{{ trans('messages.store.cash_paid_at_delivery') }}</option>
									<option ng-if="payment_details" value="1">{{ trans('messages.store.debit_or_credit_card') }}</option>
								</select>
								<div ng-show="payment_details" id="payment_detail"  ng-init="payment_details={{json_encode($payment_detail)}};payment_method=0">
									<div ng-if="payment_details!=null && payment_method==1" class="mt-3">
										<span class="d-block">
											<i class="icon icon-credit-card mr-2"></i>
											{{ trans('messages.profile.card_number') }} :
											<span class="d-inline-block">
												xxxxxxxxxxxx
												<span id="last_4">
													@{{payment_details.last4}}
												</span>
											</span>
										</span>
										<span class="d-block mt-2">
											<i class="icon icon-credit-card mr-2"></i>
											{{ trans('messages.store.card_type') }} :
											<span id="card_type" class="d-inline-block">
												@{{payment_details.brand}}</span>
											</span>
											<hr>
										</div>
									</div>

									<p id="error_card" style="display:none; color:red;">
										<span id="error_card_details"></span>
									</p>

									<div class="payment-method mt-3" data-toggle="modal" data-target="#payment-modal">
										<a href="javascript:void(0)">
											<i class="icon icon-add mr-2"></i>
											{{ trans('messages.store.add_payment_method') }}
										</a>
									</div>

									<div class="mt-3" ng-if="order_data.promo_amount < 1">
										<a href="javascript:void(0)" ng-click="show_promo()" class="theme-color promo_btn_show">{{ trans('messages.store.add_promo_code') }}</a>
										<div class="add-promo">
											<form id="apply_promo_code" >
												@csrf
												<input class="promo_code_val col-md-6 col-12 mr-0 mr-md-3" type="text" name="code" value="{{(@$user_promo_code)?$user_promo_code->promo_code->code:''}}">
												<button ng-click="apply_promo()" type="button" class="btn btn-theme mt-3 mt-md-0">{{ trans('messages.profile.apply') }}</button>
											<!-- <a href="javascript:void(0)" class="theme-color cancel-promo">
												<i class="icon icon-close-2"></i>
											</a> -->
												<span class="promo_code_error text-danger d-none">{{ trans('messages.store.please_enter_promo_code') }}</span>
											</form>
										</div>
									</div>
									<p class="promo_code_success text-success d-none mt-2"></p>
							</div>
						</div>
						@endif
					</div>
				</div>
				<div class="checkout mb-5 position-sticky col-12 col-md-5 col-lg-4 p-0 float-right" id="calculation_form" ng-cloak>
					<form>
						<label>{{ trans('messages.store.your_order_from') }}</label>
						<h2>{{$store_details->name}} - {{$store_details->user_address->city}}</h2>
						<input type="hidden" name="order_id" id="order_id">
						@if(is_user())
						<button ng-disabled="!order_data" class="btn btn-theme w-100 place-order" id="place_order" disabled="disabled">{{ trans('messages.store.place_order') }}</button>
						@endif
					</form>
					
					<div class="cart-scroll">
						<div class="checkout-item d-flex align-items-start" ng-repeat="order_row in order_data.items">
							<div class="checkout-select col-3">
								<div class="select">
									<select id='count_quantity' ng-model="order_row.item_count" data-price='@{{menu_item_price}}' ng-change="order_store_changes(order_row.order_item_id)">
										<option value="" disabled></option>
										@for($i=1;$i<=20;$i++)
										<option value="{{$i}}">{{$i}}</option>
										@endfor
									</select>
								</div>
							</div>
							<div class="checkout-name col-9 pl-md-0">
								<h4 class="d-md-flex justify-content-between">
									<span class="col-md-7 p-0">
										@{{ order_row.name }}
									</span>
									<span class="col-md-5 d-inline-block text-md-right p-0">
										<span>{!!$store_details->currency->code!!}</span>
										<span class="d-inline-block">
											@{{ order_row.item_total }}
										</span>
									</span>
								</h4>
								<small ng-if="order_row.item_notes">
									(@{{order_row.item_notes}})<br>
								</small>
								<a class="theme-color" data-remove="@{{$index}}" href="" id="remove_order" ng-click="remove_sesion_data($index)">{{ trans('admin_messages.remove') }}</a>
							</div>
						</div>
					</div>

					<div class="total" ng-show="order_data">
						<div class="checkout-total">
							<div class="col-12 mb-4">
								<input type="text" placeholder="{{ trans('messages.store.add_note_extra_sauce_no_onions') }}" name="" id="order_note"/>
							</div>
							<div id="subtotal">
								<div class="checkout-total d-flex align-items-center" ng-init="total_count_order = {{count(session('order_data'))-1}}">
									<div class="col-7">
										<h3>{{ trans('messages.profile_orders.subtotal') }} ( <span id="total_count_dat">@{{order_data.total_item_count }}</span> {{ trans('messages.store.item') }})</h3>
									</div>
									<div class="col-5 text-right">
										<h3 ><span>{!!$store_details->currency->code!!}</span> <span id="total_price_dat">@{{order_data.subtotal | number : 2 }}</span></h3>
									</div>
								</div>
							</div>
							<div class="d-flex align-items-center" >
								<div class="col-7">
									<p>{{ trans('messages.profile_orders.tax') }}</p>
								</div>
								<div class="col-5 text-right" >
									<p><span class="currency1">{!!$store_details->currency->code!!}</span> <span id="tax_amoun">@{{order_data.tax | number : 2 }}</span></p>
								</div>
							</div>
							
							<div class="d-flex align-items-center">
								<div class="col-7">
									<p>{{ trans('messages.profile_orders.booking_fee') }}</p>
								</div>
								<div class="col-5 text-right">
									<p><span class="currency1">{!!$store_details->currency->code!!}</span> <span id="booking_fee_amoun">@{{order_data.booking_fee | number : 2 }}</span></p>
								</div>
							</div>
							<div class="d-flex align-items-center">
								<div class="col-7">
									<p>{{ trans('messages.profile_orders.delivery_fee') }}</p>
								</div>
								<div class="col-5 text-right">
									<p><span class="currency1">{!!$store_details->currency->code!!}</span> <span id="delivery_fee_amoun">@{{order_data.delivery_fee | number : 2 }}</span></p>
								</div>
							</div>
							<div class="d-flex align-items-center" ng-show="order_data.penalty > 0">
								<div class="col-7">
									<p>{{ trans('admin_messages.penalty') }}</p>
								</div>
								<div class="col-5 text-right" >
									<p><span class="currency1">{!!$store_details->currency->code!!}</span> @{{order_data.penalty}}</p>
								</div>
							</div>
							<div ng-if="order_data.promo_amount > 0" class="d-flex align-items-center" >
								<div class="col-7">
									<p>{{ trans('messages.profile_orders.promo_amount') }}</p>
								</div>
								<div class="col-5 text-right" >
									<p>-<span class="currency1">{!!$store_details->currency->code!!}</span> <span id="tax_amoun">@{{order_data.promo_amount | number : 2 }}</span></p>
								</div>
							</div>
							<div class="d-flex align-items-center">
								<div class="col-7">
									<h3>{{ trans('messages.profile_orders.total') }}</h3>
								</div>
								<div class="col-5 text-right">
									<h3><span class="currency1">{!!$store_details->currency->code!!}</span> <span id="total_price_dat">@{{order_data.total_price | number:'2'}}</span></h3>
								</div>
							</div>
							@if(isset($user_details->id))
							<input type="hidden" id="delivery_fee" value="@{{order_data.delivery_fee}}">
							<input type="hidden" id="booking_fee" value="@{{order_data.booking_fee}}">
							<input type="hidden" id="tax" value="@{{order_data.tax}}">
							<input type="hidden" id="order_data_id" value="{{$order_id->id}}">
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade payment-popup" id="payment-modal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content payment-modal_load">
				<div class="modal-header">
					<h5 class="modal-title">{{ trans('messages.store.add_payment_method') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="payment-option">
						<div class="card-option active">
							<p>
								<i class="icon icon-credit-card mr-2"></i>
								{{ trans('messages.store.credit_or_debit_card') }}
								<i class="icon icon-angle-arrow-pointing-to-right-1 float-right"></i>
							</p>
						</div>
						<div class="cash-option">
							<p>
								<i class="icon icon-credit-card mr-2"></i>
								{{ trans('messages.store.cash_paid_at_delivery') }}
							</p>
						</div>
					</div>
					<div class="card-form">
						<form>
							<div class="form-group card-number">
								<label>{{ trans('messages.profile.card_number') }}</label>
								<input type="text" name="card_number" id="card_number" />
							</div>
							<div class="form-group d-block d-md-flex">
								<div class="col-12 col-md-9 p-0">
									<label>{{ trans('messages.store.expiration_date') }}</label>
									<div class="date-selection d-flex">
										<div class="select">
											{{ Form::selectRange('expire_month', 1, 12, '',['id'=>'expire_month']) }}
										</div>
										<div class="select">
											{{ Form::selectRange('expire_year', date('Y'), date('Y')+10, '',['id'=>'expire_year']) }}

										</div>
									</div>
								</div>
								<div class="col-12 col-md-3 p-0 mt-3 mt-md-0">
									<label>{{ trans('messages.store.cvv') }}</label>
									<input type="text" name="cvv_number" id="cvv_number" />
								</div>
							</div>
							<div class="form-group d-block d-md-flex">
								<div class="col-12 col-md-9 p-0">
									<label>{{ trans('messages.profile.country') }}</label>
									<div class="date-selection d-flex">
										<div class="select">
											{!! Form::select('country_card',$address_country,'',['id'=>'country_card']) !!}
										</div>
									</div>
								</div>
								<div class="col-12 col-md-3 p-0 mt-3 mt-md-0">
									<label>{{ trans('messages.store.zip_code') }}</label>
									<input type="text" name="card_code"  id="card_code"/>
								</div>
							</div>
							<span id="error_add_card" class="text-danger error_add_card_new"></span>
							<button type="submit" class="w-100 btn btn-theme" id="payment_card">{{ trans('messages.store.add_card') }}</button>
						</form>
						<div class="text-center mt-2">
							<a href="javascript:void(0)" class="back-btn theme-color">{{ trans('messages.store.go_back') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade schedule-popup" id="schedule-modal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">{{ trans('messages.store.choose_delivery_time') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<label>{{ trans('messages.store.date') }}</label>
							<div class="select">
								<select id="schedule_date" ng-model="schedule_date_value">
									@foreach(date_data() as $key=>$data)
									<option value="{{$key}}" {{ ($key == session('schedule_data')['date']) ? 'selected' : '' }}>{{trans('messages.driver.'.date("M", strtotime($data))).' '.date("d", strtotime($data)).','.date("Y", strtotime($data))}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label>{{ trans('messages.store.time') }}</label>
							<div class="select">
								<select id="schedule_time" >
									<option  ng-selected="schedule_time_set==key" ng-repeat="(key ,value) in schedule_time_value" value="@{{key}}" ng-if="(key | checkTimeInDay :schedule_date_value)">@{{value}}</option>
								</select>
							</div>
						</div>
						<button class="w-100 btn btn-theme" id="set_time1" type="submit">{{ trans('messages.store.set_time') }}</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade place-popup" id="place-modal" tabindex="-1" role="dialog" aria-labelledby="placeModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-center">{{ trans('messages.store.verify_mobile') }}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
					<div class="num-verify mt-4">
						<p>{{ trans('messages.store.please_enter_your_verification_code') }}</p>
						<p>{{ trans('messages.store.we_sent_the_code_to') }} +91 1111222233</p>
					</div>
				</div>
				<div class="modal-body">
					<form>
						<div class="form-group">
							<input type="text" name="" placeholder="1234">
						</div>
						<button class="w-100 btn btn-theme" type="submit">{{ trans('messages.store.verify') }}</button>
					</form>
				</div>
				<div class="modal-footer">
					<div class="d-block d-md-flex w-100 text-center text-md-left">
						<div class="col-12 col-md-6">
							<a href="javascript:void(0)" class="theme-color">{{ trans('messages.store.resend_the_text') }}</a>
						</div>
						<div class="col-12 col-md-6 text-md-right mt-2 mt-md-0">
							<a href="javascript:void(0)" class="theme-color">{{ trans('messages.store.change_mobile_number') }}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop