@extends('template2')
@section('main')
<main id="site-content" role="main" ng-controller="stores_detail" ng-init="order_data = {{json_encode($order_detail_data)}};store_id={{$store->id}};other_store='{{$other_store}}'">
	<div class="detail-banner" style="background-image: url('{{$store->store_image}}');">
		<div class="container">
			<div class="banner-content product">
				<div class="product-info">
					<input type="hidden" value="1" name="check_detail_page" id="check_detail_page">
					<input type="hidden" id="session_order_data" value="{{json_encode(session('order_data'))}}">
					<h2>
						<a href="#">
							@if($store)
							<span>{{$store->name}}</span>
							@if($store->user_address)
							@if($store->user_address->city !='' && $store->user_address->city !=null)
							- <span>{{$store->user_address->city}}</span>
							@endif
							@endif
							@endif
						</a>
					</h2>
					@if(isset($store_category))
					<div class="pro-category">
						<p class="text-truncate">
							@for($i=0;$i<$store->price_rating;$i++)
							{!! $store->currency->original_symbol !!}
							@endfor
						</p>
						@foreach($store_category as $row)
						<p class="text-truncate">
							<span>•</span>
							{{$row->category_name}}
						</p>
						@endforeach
					</div>
					@endif
					@if(isset($store))
					<div class="product-rating">

						@if($store->review->store_rating_count)
							<span>
								<i class="icon icon-star mr-1"></i>
								{{$store->review->store_rating}} <span>({{$store->review->store_rating_count}})</span>
							</span>
						@endif

					@if($store->status==0)
                    <span>{{ trans('messages.store.currently_unavailable') }} </span>
                    @elseif(isset($store->store_time->closed)!=0)
                    <span>{{ $store->convert_mintime }} – {{ $store->convert_maxtime }} <span>{{trans('messages.store.min')}}</span></span>
                    @else
					<span>{{ $store->store_next_opening }} </span>
					@endif
					</div>
					@endif

				</div>
			</div>
		</div>
	</div>
	<div class="detail-menu" ng-init="menu_category={{json_encode($menu_category)}}" ng-cloak>
		<div class="container">
			<div class="d-block d-md-flex align-items-center clearfix my-4 my-md-0">
				@if(count($store_menu)>1)
				<div class="category-select select mb-3 mb-md-0 py-3">
					<select id="menu_changes">
						@foreach($store_menu as $menu)
						<option value="{{$menu->id}}">{{$menu->name}}</option>
						@endforeach
					</select>
				</div>
				@endif

				<div class="menu-list">
					<ul class="text-truncate" >
						<li ng-repeat="list_of_menu in menu_category" ng-if="$index<7">
							<a href="#@{{list_of_menu.id}}">
								@{{list_of_menu.name}}
							</a>
						</li>
					</ul>
				</div>

				<div class="more-list ml-auto" ng-show="menu_category.length > 7">
					<a href="#" class="more-btn text-truncate text-right">{{ trans('messages.store.more') }}</a>
					<ul class="more-option">

						<li  ng-repeat="list_of_menu in menu_category" ng-if="$index>6">
							<a href="#@{{list_of_menu.id}}">
								@{{list_of_menu.name}}
							</a>
						</li>
					</ul>
				</div>


			</div>
		</div>
	</div>
	<div class="detail-content">
		<div class="container">
			<div class="clearfix">
				<div class="detail-products col-12 col-md-7 col-lg-8 pl-0 pr-0 pr-md-4 float-left">
					@if($store_menu)
					@foreach($store_menu as $menu_category1)
					@if($menu_category1->menu_category)
					@foreach($menu_category1->menu_category as $category_row)
					<div class="popular mb-4 mb-md-5" id="{{$category_row->id}}">
						<h1>{{$category_row->name}}</h1>
						<div class="pro-row clearfix">
							@if($category_row->menu_item)
							@foreach($category_row->menu_item as $menu_row)
							@if($menu_row->status==1)
							<div class="pro-item d-flex" data-id="{{$menu_row->id}}" data-name="{{$menu_row->name}}" data-price="{{ ($menu_row->offer_price!=0) ? $menu_row->offer_price : $menu_row->price }}">
								@if($store_time_data==0)
								<label class="sold-out">{{ trans('messages.store.closed') }}</label>
								@elseif($menu_row->is_visible==0)
								<label class="sold-out">{{ trans('messages.store.sold_out') }}</label>
								@elseif($menu_row->menu->menu_closed==0)
								<label class="sold-out">
									@if($menu_row->menu->menu_closed_status=='Available')
									{{ trans('admin_messages.available') }}
									@endif
									@if($menu_row->menu->menu_closed_status=='Un Available')
									{{ trans('messages.store.unavailable') }}
									@endif
									@if($menu_row->menu->menu_closed_status=='Closed')
									{{ trans('messages.store.closed') }}
									@endif
								</label>
								@endif
								<div class="pro-info">
									<h2 class="text-truncate">{{$menu_row->name}}</h2>
									<p><span>{!! $store->currency->code !!}</span>
										@if($menu_row->offer_price!=0)
										<strike>{{$menu_row->price}}</strike> {{$menu_row->offer_price}}
										@else
										{{$menu_row->price}}
										@endif
									</p>
								</div>
								<div class="pro-img" style="background-image: url('{{$menu_row->menu_item_image}}');">
								</div>
							</div>
							@endif
							@endforeach
							@endif
							{{--end of menu item--}}
						</div>
					</div>
					@endforeach
					@endif
					@endforeach
					@endif
					{{--end of menu item list--}}
				</div>

				<div class="checkout mb-5 position-sticky col-12 col-md-5 col-lg-4 p-0 float-right"  id="calculation_form" ng-class="!order_data ? 'disabled':''">
					<form name="order_checkout">
						<button ng-disabled="!order_data" class="btn btn-theme w-100" id="checkout" type="submit">{{ trans('admin_messages.checkout') }}
						</button>
					</form>
					<input type="hidden" id="order_id" value="@{{order_id}}">
					<div class="cart-scroll">
						<div class="checkout-item d-flex align-items-start" ng-repeat="order_row in order_data.items">
							<div class="checkout-select col-3">
								<div class="select">
									<select id='count_quantity1'  ng-model="order_row.item_count" data-price='@{{ (menu_item.offer_price!=0) ? menu_item.offer_price : menu_item_price}}' ng-change="order_store_changes(order_row.order_item_id)">
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
										<span>{!!$store->currency->code!!}</span>
										<span class="d-inline-block">
											@{{ order_row.item_total | number:'2' }}
										</span>
									</span>
								</h4>
								<small ng-if="order_row.item_notes">
									(@{{order_row.item_notes}})<br>
								</small>
								<a class="theme-color" data-remove="@{{$index}}" href="" id="remove_order" ng-click="remove_sesion_data($index)">
									{{ trans('admin_messages.remove') }}
								</a>
							</div>
						</div>
					</div>
					<div ng-show="order_data.total_item_count>0" id="subtotal5" >
						<div class="checkout-total d-flex align-items-center" ng-init="total_count_order = {{count(session('order_data'))-1}}">
							<div class="col-7">
								<h3>{{ trans('messages.profile_orders.subtotal') }}
									<span id="total_item_count" class="d-inline-block">
										(@{{ order_data.total_item_count }} {{ trans('messages.store.items') }})
									</span>
								</h3>
							</div>
							<div class="col-5 text-right">
								<h3><span>{!! $store->currency->code !!}</span>
									<span id="total_item_price">@{{ order_data.subtotal | number : 2}}</span>
								</h3>
							</div>
						</div>
					</div>
					<div class="checkout-info text-center" ng-if="!order_data.items.length">
						<p>{{ trans('messages.store.add_items_to_your_cart_and_they_appear') }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<a href="#" data-toggle="modal" data-target="#myModal" class="toogle_modal" style="display:none"></a>
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title">{{ trans('messages.store.start_new_cart') }}</h3>
				</div>
				<div class="modal-body detail_off">
					<p>{{ trans('messages.store.your_cart_already_contains') }} <span>{{isset($other_store_detail->name)?$other_store_detail->name:''}}</span> - <span>{{isset($other_store_detail->user_address->city)? $other_store_detail->user_address->city:''}}</span>. {{ trans('messages.store.would_you_like_to_clear_cart') }} {{$store->name}} - {{isset($store->user_address->city)? $store->user_address->city:''}} {{ trans('messages.store.instead') }}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-dismiss="modal" data-val="cancel">{{ trans('admin_messages.cancel') }}</button>
					<button type="button" class="btn btn-primary store_popup" data-dismiss="modal" data-val="ok">{{ trans('messages.store.new_cart') }}</button>
				</div>
			</div>
		</div>
	</div>
	<div class="detail-popup" ng-cloak>
		<div class="detail-pop-in mx-auto">
			<div class="pop-img" style="background-image: url('@{{menu_item.menu_item_image}}');">

				<i class="icon icon-close-2"></i>
			</div>
			<div class="pro-content">
				<h1>@{{menu_item.name}}</h1>
				<!-- 	<span class="pro-category theme-color d-inline-block text-nowrap">
						<i class="icon icon-approved-signal"></i>
						Halal
					</span> -->
					<input type="hidden" id="menu_item_id" value="@{{menu_item.id}}">
					<div class="special-inst mt-3">
						<h4>{{ trans('messages.store.special_instructions') }}</h4>
						<input class="p-2 w-100" type="text" ng-model="add_notes" placeholder="{{ trans('messages.store.add_note_extra_sauce_no_onions') }}"/>
					</div>
				</div>
				<div class="pro-cart d-block d-md-flex align-items-center">
					<div class="quantity d-flex align-items-center col-12 col-md-5 mb-3 mb-md-0 justify-content-center justify-content-md-start">
						<button class="value-changer" data-val="remove">
							<i class="icon icon-remove"></i>
						</button>
						<span class="mx-3 count_item" ng-model="item_count">@{{item_count}}</span>
						<button class="value-changer" data-val="add">
							<i class="icon icon-add"></i>
						</button>
					</div>

					<span style="display:none;">
						@{{ (menu_item.offer_price>0) ? menu_item.offer_price : menu_item.price}}
					</span>

					<div class="cart-btn col-12 col-md-7" ng-init="individual_price = individual_price">
						@if($store_time_data==0)
						<button class="btn btn-theme w-100 disabled" type="submit">{{ trans('messages.store.closed') }}</button>
						@elseif($store->status==0)
						<button class="btn btn-theme w-100 disabled" type="submit">{{ trans('messages.store.currently_unavailable') }}</button>
						@else
						<button ng-if="menu_item.is_visible == 0" disabled="disabled" class="btn btn-theme w-100">{{ trans('messages.store.item_is_sold_out') }}</button>
						<button class="btn btn-theme w-100" ng-if="menu_item.is_visible != 0 && menu_item.menu_item_status!=0" type="submit" id="cart_sumbit" ng-click="order_store_session()" data-val="@{{menu_item.is_visible}}">{{ trans('admin_messages.add') }} <span class ="count_item">@{{item_count}}</span> <span>{{ trans('messages.store.to_cart') }} </span><span class="span_close">(<span>{!!$store->currency->code!!}</span><span ng-hide="menu_item" class="ml-2" id="menu_item_price"></span> <span>@{{ menu_item.price }}</span> )</span></button>
						<button class="btn btn-theme w-100" disabled="disabled" ng-if=" menu_item.is_visible != 0 && menu_item.menu_item_status==0" >{{ trans('messages.store.item_is') }} <span ng-if="menu_item.menu_closed_status=='Available'">{{ trans('admin_messages.available') }}</span><span ng-if="menu_item.menu_closed_status=='Un Available'">{{ trans('messages.store.unavailable') }}</span><span ng-if="menu_item.menu_closed_status=='Closed'">{{ trans('messages.store.closed') }}</span></button>
						@endif
					</div>
				</div>
			</div>
		</div>


	</main>
	@stop
	@push('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			function category_menu() {
				var a = $('header').outerHeight();
				var b = $('.detail-menu').outerHeight();
				var menu_top = $('.detail-banner').position().top + $('.detail-banner').outerHeight();
				if ($(window).scrollTop() >= (menu_top - a)) {
					$('.detail-menu').css({"top":a + "px"});
					$('.detail-menu').addClass('active');
					$('.detail-content').css({"margin-top":b + "px"});
					$('.checkout').css({"top":a + b + 20 + "px"});
					$('header').addClass('no-shadow');
				} else {
					$('.detail-menu').css({"top":"inherit"});
					$('.detail-menu').removeClass('active');
					$('.detail-content').css({"margin-top":"0px"});
					$('header').removeClass('no-shadow');
				}
			}
			category_menu();
			$(window).scroll(function() {
				category_menu();
			});
		});

		$(document).on('click','.menu-list li a',function(e) {

e.preventDefault(); // prevent hard jump, the default behavior
var target = $(this).attr("href"); // Set the target as variable
var top = $(target).offset().top - ($('header').outerHeight() + $('.detail-menu').outerHeight() + 10);
// perform animated scrolling by getting top-position of target-element and set it as scroll target
$('html, body').stop().animate({
	scrollTop: top
}, 600, function() {
// location.hash = target; //attach the hash (#jumptarget) to the pageurl
});
});
</script>
@endpush