@extends('template2')

@section('main')
<main id="site-content" role="main" ng-controller="orders_detail">
	<div class="container">
		<div class="my-orders py-4 pt-md-5 col-lg-9 mx-auto">
			<h1>{{trans('messages.profile_orders.orders')}}</h1>
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="past-tab" data-toggle="tab" href="#past" role="tab" aria-controls="past" aria-selected="true">{{trans('messages.profile_orders.past')}}</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="false">{{trans('messages.profile_orders.upcoming')}}</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="past" role="tabpanel" aria-labelledby="past-tab">
					<div class="order-list">
						@if(count($order_details))
						@foreach($order_details as $order_row)
						<div class="order-item text-center text-md-left d-md-flex">
							<div class="order-img" style="background-image: url('{{$order_row->store->store_image}}');">
							</div>
							<div class="order-info d-flex flex-column justify-content-between">
								<div class="shop-name">
									<h1><span>{{$order_row->store->name}} </span>- <span> {{$order_row->store->user_address->city}}</span></h1>

									@foreach($order_row->order_item as $item_row)
									<span><span>{{$item_row->quantity}} {{$item_row->menu_item->name}}</span>
										@if($item_row->notes !='')
										<small>({{$item_row->notes}})</small> 
										@endif
									</span><br>
									@endforeach
								</div>
								<div class="order-method">
									<span>
										{{trans('messages.profile_orders.order_id')}} :  {!!$order_row->id!!}
									</span>
									@if($order_row->promo_amount>0)
									<span>
										{{trans('messages.profile_orders.promo_amount')}} :  - <span class="currency1">{!!$order_row->currency->code!!}</span> {{$order_row->promo_amount}}
									</span>
									@endif
									<span>
										{{trans('messages.profile_orders.payment_method')}} :  {{trans('messages.profile_orders.'.$order_row->payment_type_text)}}
									</span>
									<span>
										{{trans('messages.profile_orders.status')}}  : {{trans('messages.profile_orders.'.$order_row->status_text)}}
									</span>
									<span class="order-charge">
										@if($order_row->status==4 || $order_row->status==2)
										<p>{{trans('messages.profile_orders.not_charged')}}</p>
										@elseif($order_row->status!=4 && $order_row->status>=3)
										<span>{{trans('messages.profile_orders.total')}}</span> : <span> {!!$order_row->currency->code!!}</span> <span>  {{ $order_row->total_amount}}</span>
										@endif
									</span>
								</div>
							</div>
							<div class="order-status d-flex flex-column text-md-right">

								@if($order_row->status==4 || $order_row->status==2)
								<span class="mb-auto">{{trans('messages.profile_orders.'.$order_row->status_text)}}
									{{$order_row->status==4?$order_row->cancelled_at:$order_row->declined_at}} 
									<!-- <i class="icon icon-close-2 ml-2"></i> -->
								</span>
								@endif
								@if($order_row->status==6)
								<span class="mb-auto">{{trans('messages.profile_orders.delivered')}} {{$order_row->delivery_at}}</span>
								@endif

								@if($order_row->status==6)
								<a href="javascript:void(0)" class="btn btn-theme invoice-btn" data-toggle="modal" data-target="#invoice-modal" data-id="{{$order_row->id}}">{{trans('messages.profile_orders.view_invoice')}}</a>
								@endif
								<a class="btn btn-theme mt-2" href="details/{{$order_row->store->id}}">{{trans('messages.profile_orders.view_store')}}</a>
							</div>
						</div>
						@endforeach
						@else
						<div class="order-item text-center text-md-left d-md-flex">
							<div class="order-img"></div>
							<div class="order-info d-flex flex-column justify-content-between">
								<div class="shop-name">
									<h1>{{trans('messages.profile_orders.no_orders')}}</h1>
									<span>{{trans('messages.profile_orders.past_orders')}}</span>
								</div>
							</div>
							<div class="order-status d-flex flex-column justify-content-end text-md-right">
								<a href="stores" class="btn btn-theme mt-2 mt-md-0">{{trans('messages.profile_orders.view_store')}}</a>
							</div>
						</div>
						@endif
					</div>
				</div>
				<div class="tab-pane fade" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
					@if(count($upcoming_order_details))
					<div class="order-list">
						@foreach($upcoming_order_details as $upcoming_order_row)
						<div class="order-item text-center text-md-left d-md-flex">
							<div class="order-img" style="background-image: url('{{$upcoming_order_row->store->store_image}}');">
							</div>
							<div class="order-info d-flex flex-column justify-content-between">
								<a href="{{route('order_track',['order_id'=>$upcoming_order_row->id])}}">
									<div class="shop-name">
										<h1><span>{{$upcoming_order_row->store->name}} </span>-<span> {{$upcoming_order_row->store->user_address->city}}</span></h1>

										@foreach($upcoming_order_row->order_item as $item_row)
										<span><span>{{$item_row->quantity}} {{$item_row->menu_item->name}}</span>
											@if($item_row->notes !='')
											<small>({{$item_row->notes}})</small>
											@endif
										</span>
										@endforeach
									</div>
									<div class="order-method">
										<span>
											{{trans('messages.profile_orders.order_id')}} :  {!!$upcoming_order_row->id!!}
										</span>
										@if($upcoming_order_row->promo_amount>0)
										<span>
											{{trans('messages.profile_orders.promo_amount')}} :  -<span class="currency1"> {!!$upcoming_order_row->currency->code!!} </span> {{$upcoming_order_row->promo_amount}}
										</span>
										@endif
										<span>
											{{trans('messages.profile_orders.payment_method')}} 
											 :  {{trans('messages.profile_orders.'.$upcoming_order_row->payment_type_text)}}
										</span>
										<span>
											{{trans('messages.profile_orders.status')}}  : 
											{{trans('messages.profile_orders.'.$upcoming_order_row->status_text)}}
										</span>
										<span class="order-charge">
											<span>{{trans('messages.profile_orders.total')}} </span> : <span> {!!$upcoming_order_row->currency->code!!}</span> <span>{{$upcoming_order_row->total_amount}}</span>
										</span>
									</div>
								</a>
							</div>
							<div class="order-status d-flex flex-column text-md-right">

								<a class="btn btn-theme mt-auto" href="details/{{$upcoming_order_row->store->id}}">{{trans('messages.profile_orders.view_store')}}</a>

								@if($upcoming_order_row->status==1)

								<a ng-click="open_cancel_model('{{$upcoming_order_row->id}}')" class="btn btn-theme mt-2" href="javascript:void(0)"  data-toggle="modal" data-target="#cancel_modal">{{trans('messages.profile_orders.cancel_order')}}</a>

								@endif
							</div>
						</div>
						@endforeach
					</div>
					@else
					<div class="order-item text-center text-md-left d-md-flex">
						<div class="order-img"></div>
						<div class="order-info d-flex flex-column justify-content-between">
							<div class="shop-name">
								<h1>{{trans('messages.profile_orders.no_orders')}}</h1>
								<span>{{trans('messages.profile_orders.any_orders')}}</span>
							</div>
						</div>
						<div class="order-status d-flex flex-column justify-content-end text-md-right">
							<a href="stores" class="btn btn-theme mt-2 mt-md-0">{{trans('messages.profile_orders.view_store')}}</a>
						</div>

					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade invoice-popup" id="invoice-modal" tabindex="-1" role="dialog" aria-labelledby="invoiceModalLabel" aria-hidden="true" ng-cloak>
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title text-center">{{trans('messages.profile_orders.order_receipt')}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
				</div>
				<div class="modal-body" ng-loack>
					<div class="checkout-list">

						<div class="checkout-item d-flex px-0" ng-repeat="receipt_row in order_detail.order_item">
							<div class="col-3 col-md-2 pl-0">
								<input type="text" name="" value="@{{receipt_row.quantity}}" readonly/>
							</div>
							<div class="col-6 col-md-7 pl-0">
								<h4><span class="resp_name">@{{receipt_row.menu_item.name}}</span> <span ng-bind-html="currency_symbol"></span>@{{receipt_row.price}}</h4>
							</div>
							<div class="col-3 px-0 text-right">
								<h4><span ng-bind-html="currency_symbol"></span>@{{receipt_row.total_amount}}</h4>
							</div>
						</div>
					</div>
					<div class="total">
						<div class="checkout-total px-0">
							<div class="row d-block">
								<div class="d-flex align-items-center">
									<div class="col-7">
										<p>{{trans('messages.profile_orders.subtotal')}} (@{{order_detail.order_item.length}} {{ trans('messages.store.item') }})</p>
									</div>
									<div class="col-5 text-right">
										<p><span ng-bind-html="currency_symbol"></span>@{{order_detail.subtotal}}</p>
									</div>
								</div>
								<div class="d-flex align-items-center" ng-if="order_detail.delivery_fee>0">
									<div class="col-7">
										<p>{{trans('messages.profile_orders.delivery_fee')}}</p>
									</div>
									<div class="col-5 text-right">
										<p><span ng-bind-html="currency_symbol"></span>@{{order_detail.delivery_fee}}</p>
									</div>
								</div>
								<div class="d-flex align-items-center" ng-if="order_detail.tax>0">
									<div class="col-7">
										<p>{{trans('messages.profile_orders.tax')}}</p>
									</div>
									<div class="col-5 text-right">
										<p><span ng-bind-html="currency_symbol"></span>@{{order_detail.tax}}</p>
									</div>
								</div>
								<div class="d-flex align-items-center" ng-if="order_detail.booking_fee>0">
									<div class="col-7">
										<p>{{trans('messages.profile_orders.booking_fee')}}</p>
									</div>
									<div class="col-5 text-right">
										<p><span ng-bind-html="currency_symbol"></span>@{{order_detail.booking_fee}}</p>
									</div>
								</div>
								<div class="d-flex align-items-center" ng-if="order_detail.promo_amount>0">
									<div class="col-7">
										<p>{{trans('messages.profile_orders.promo_code_amount')}}</p>
									</div>
									<div class="col-5 text-right">
										<p>- <span ng-bind-html="currency_symbol"></span>@{{order_detail.promo_amount}}</p>
									</div>
								</div>
								<div class="col-12">
									<div class="invoice-total mt-3 pt-3 d-flex align-items-center">
										<div class="col-7 pl-0">
											<h3>{{trans('messages.profile_orders.total')}}</h3>
										</div>
										<div class="col-5 text-right pr-0">
											<h3><span ng-bind-html="currency_symbol"></span>@{{order_detail.total_amount}}</h3>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>

<div class="modal fade" id="open_cancel_model" role="dialog">
	<div class="modal-dialog">
		{!! Form::open(['url'=>route('cancel_order'),'method'=>'POST'])!!}
		<div class="modal-content">
			<div class="modal-header">
				<h3>{{trans('messages.profile_orders.cancel_reason')}}</h3>
				<button type="button" class="close" data-dismiss="modal">
					<i class="icon icon-close-2"></i>
				</button>
			</div>
			<div class="modal-body">
				<div class="flash-container" id="popup1_flash-container"> </div>
				<div id="select">
					<select id="cancel_reason" name="reason">
						@foreach($cancel_reason as $row=>$value)
						<option value="{{$value->id}}">{{$value->name}}</option>
						@endforeach
					</select>
				</div>
				<br>
				<input type="hidden" id="cancel_order_id" name="order_id" value="">
				<textarea id="cancel_message" name="message" placeholder="{{trans('messages.profile_orders.comments')}}" style="width: 100%"></textarea>
				<div class="panel-footer mt-4">
					<input type="submit" value="{{trans('messages.profile_orders.submit')}}" class="btn btn-theme">
				</div>
			</div>
		</div>
		{!! Form::close() !!}
	</div>
</div>
@stop