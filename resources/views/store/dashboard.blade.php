@extends('template')

@section('main')
<main id="site-content" role="main" ng-controller="store_dashboard">
	<div class="partners">

		@if($user->status_text!='active' && $user->status_text!='inactive')
		<div class="verification-steps mt-3 mb-5 my-md-5">
			<div class="verify-head d-flex align-items-center">
				<i class="icon icon-thumbs-up mr-3"></i>
				<h2>{{trans('messages.store_dashboard.verification_step')}}</h2>
			</div>

			<div class="verify-steps">
				<ul>
					<li class="{{$document? 'completed':''}}">
						<a href="{{route('store.profile','#document')}}">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							{{trans('messages.store_dashboard.add_document')}}
						</a>
					</li>
					<li class="{{$open_time? 'completed':''}}">
						<a href="{{route('store.profile','#open_time')}}">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							{{trans('messages.store_dashboard.add_open_time')}}
						</a>
					</li>
					<li class="{{$profile_step? 'completed':''}}">
						<a href="{{route('store.profile')}}">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							{{trans('messages.store_dashboard.complete_profile')}}
						</a>
					</li>
					<li class="{{$payout_preference? 'completed':''}}">
						<a href="{{route('store.payout_preference')}}">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							{{trans('messages.store_dashboard.add_payout_preference')}}
						</a>
					</li>
					<li class="{{$menu? 'completed':''}}">
						<a href="{{route('store.menu')}}">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							{{trans('messages.store_dashboard.add_menu')}}
						</a>
					</li>
					<li class="{{($menu && $payout_preference && $profile_step && $open_time && $document)? '':'d-none'}}">
						<a href="javascript:void(0)">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							{{trans('messages.store_dashboard.waiting_for_approval')}}
						</a>
					</li>
				</ul>
			</div>
		</div>
		@endif
		@include ('store.navigation')
		{!! Charts::assets() !!}
		<div id="sales">
			<div class="d-md-flex align-items-center justify-content-between">
				<h1 class="title">{{trans('messages.store_dashboard.sales')}}</h1>
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="weekly-tab" data-toggle="tab" href="#weekly" role="tab" aria-controls="weekly" aria-selected="true">7 {{trans('messages.store_dashboard.days')}}</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="false">30 {{trans('messages.store_dashboard.days')}}</a>
					</li>
				</ul>
			</div>
			<div class="panel-content my-3 my-md-5">
				<div class="tab-pane fade active" id="weekly" role="tabpanel" aria-labelledby="weekly-tab">
					<div class="d-md-flex align-items-center justify-content-between">
						<div class="net-pay col-md-4">
							<h2>${{$last_seven_total_payouts}}</h2>
							<p>{{trans('messages.store_dashboard.net_payout')}}</p>
						</div>

						<div class="net-chart col-md-8 mt-5 mt-md-0">
							@if(isset($seven_chart))
								<center>
									{!! $seven_chart->render() !!}
								</center>
							@else
							<h3>{{trans('messages.store_dashboard.last_seven_days')}}</h3>
							@endif
						</div>
					</div>
					@if(count($top_sale_thirty_days)>0)
					<div class="menu-items mt-5 dashboard_menu">
						<h3>{{trans('messages.store_dashboard.selling_menu_items')}}</h3>
						<ul class="clearfix mt-3">
							@foreach($top_sale_saven_days as $top_saven)
							<li><span>{{$top_saven->total_times}}</span>{{$top_saven->menu_item->name}}</li>
							@endforeach
						</ul>
					</div>
					@endif
				</div>
				<div class="tab-pane fade active " id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
					<div class="d-md-flex align-items-center justify-content-between">
						<div class="net-pay col-md-4">
							<h2>${{$last_thirty_total_payouts}}</h2>
							<p>{{trans('messages.store_dashboard.net_payout')}}</p>
						</div>
						<div class="net-chart col-md-8 mt-5 mt-md-0">
							@if(isset($thirty_chart))
								<center>
									{!! $thirty_chart->render() !!}
								</center>
							@else
							<h3>{{trans('messages.store_dashboard.last_thirty_days')}}</h3>
							@endif

						</div>
					</div>
					@if(count($top_sale_thirty_days)>0)
					<div class="menu-items mt-5 dashboard_menu">
						<h3>{{trans('messages.store_dashboard.selling_menu_items')}}</h3>
						<ul class="clearfix mt-3">
							@foreach($top_sale_thirty_days as $top_saven)
							<li><span>{{$top_saven->total_times}}</span>{{$top_saven->menu_item->name}}</li>
							@endforeach
						</ul>
					</div>
					@endif
				</div>

					</div><!--
					<div class="my-5 select col-12 col-md-6 col-lg-4 p-0">
						<select>
							<option>Past month</option>
							<option>Past week</option>
							<option>Yesterday, 08/28</option>
						</select>
					</div> -->
				</div>
				<div id="service">
					<h1 class="title">{{trans('messages.store_dashboard.service_quality')}}</h1>
					<div class="mt-3">
						<p class="light-color">{{trans('messages.store_dashboard.speed_and_convenience',['site_name'=> site_setting('site_name')]) }}</p>
					</div>
					<div class="panel-content mt-3 my-md-5">
						<div class="text-right">
							<p class="light-color">{{trans('messages.store_dashboard.based_on_past_30_days')}}</p>
						</div>

						<div class="service-row">
							<h3>{{trans('messages.profile_orders.orders')}}</h3>
							<div class="mt-4 d-block row d-lg-flex align-items-center">
								<div class="col-12 col-lg-6">
									<div class="accepted-hr d-md-flex align-items-center row">
										<div class="col-12 col-md-4">
											<p>{{trans('messages.store_dashboard.accept_orders')}}</p>
										</div>
										<div class="col-12 col-md-7 offset-md-1 d-md-flex new_bar align-items-center">
											<div class="bar-info w-100 pr-md-3">
												<span class="bar"></span>
												<span style="width: {{$accepted_rating}}%" class="bar bar-percentage {{(($accepted_rating >= 80) ?'bar-green':(($accepted_rating >= 50)?'bar-yellow':'bar-red')) }} "></span>
											</div>
											<p class="text-nowrap">{{$accepted_rating}}%</p>
										</div>
									</div>
									<div class="expected-hr d-md-flex align-items-center row mt-4 mt-md-0">
										<div class="col-12 col-md-4">
											<p>{{trans('messages.store_dashboard.cancel_orders')}}</p>
										</div>
										<div class="col-12 col-md-7 offset-md-1 d-md-flex new_bar align-items-center">
											<div class="bar-info w-100 pr-3">
												<span class="bar"></span>
												<span style="width: {{$canceled_rating}}%" class="bar bar-percentage {{(($canceled_rating >= 80) ?'bar-green':(($canceled_rating >= 50)?'bar-yellow':'bar-red')) }} "></span>
											</div>
											<p class="text-nowrap">{{$canceled_rating}}%</p>
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-6 mt-4 mt-lg-0">
									@if($accepted_rating == 100)
									<div class="hrs-info pd-15">
										<h4>{{trans('messages.store_dashboard.thanks_for_being_reliable')}}</h4>
										<p>{{trans('messages.store_dashboard.you_fulfilling_all_orders')}}</p>
									</div>
									@endif
								</div>
							</div>
						</div>

					</div>
				</div>
				<div id="customer-satisfaction">
					<div class="d-md-flex justify-content-between align-items-center">
						<h1 class="title">{{trans('messages.store_dashboard.customer_satisfaction')}}</h1>
						<p class="light-color">{{trans('messages.store_dashboard.based_on_past_30_days')}}</p>
					</div>
					<div class="panel-content my-3 my-md-5">
						<div class="service-row">
							<div class="d-block row d-lg-flex align-items-center">
								<div class="col-12 col-lg-6">
									<h3>{{$retauarnt_rating}}%</h3>
									<p>{{trans('messages.store_dashboard.satisfaction_rating')}}</p>
									<div class="cust-hr d-md-flex align-items-center row">
										<div class="col-12 d-md-flex new_bar align-items-center">
											<!-- <p class="text-nowrap d-block d-md-none text-right mt-3">100%</p> -->
											<div class="w-100 pr-md-3">
												<div class="bar-info">
													<span class="bar"></span>
													<span style="width: {{$retauarnt_rating}}%" class="bar bar-percentage {{(($retauarnt_rating >= 80) ?'bar-green':(($retauarnt_rating >= 50)?'bar-yellow':'bar-red')) }} "></span>
												</div>
											</div>
											<p class="text-nowrap d-md-block">{{$retauarnt_rating}}%</p>
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-6 mt-4 mt-lg-0">
									<div class="hrs-info">
										<h5>{{trans('messages.store_dashboard.see_what_people_are_saying')}}</h5>
										<p class="light-color">{{trans('messages.store_dashboard.customers_like_your_item')}}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ratings-table mb-4">
					<h5>{{trans('messages.store_dashboard.ratings')}}</h5>
					<div class="table-responsive">
						<table>
							<thead>
								<tr>
									<th>{{trans('messages.store_dashboard.item')}}</th>
									<th>{{trans('messages.store_dashboard.satisfaction_rating')}}</th>
									<th>{{trans('messages.store_dashboard.negative_feedback')}}</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach($review_column as $review_id => $reviews)
								<tr>
									<td>{{$reviews['name']}}</td>
									<td>
										<div class="d-flex align-items-center">
											<div class="bar">
												<span style="width: {{$reviews['prasantage']}}%" class="bar-process @if($reviews['prasantage']==100)  green @elseif($reviews['prasantage']>50) yellow @elseif($reviews['prasantage']<50) red @endif"></span>
											</div>
											<span class="text-nowrap ml-3">{{$reviews['prasantage']}}% ({{$reviews['count_thumbs']}})</span>
										</div>
									</td>
									<td>
										<div class="feedbacks">
											@if(isset($reviews['issues_column']))
											@foreach($reviews['issues_column'] as $key => $issue)
											<label>
												<span>{{$key}}</span>
												<span>{{$issue}}</span>
											</label>
											@endforeach
											@endif
										</div>
									</td>
									<td class="text-right" >
										<input type="hidden" name="comments" value="{{$reviews['review_comments']}}" id="comments_{{$review_id}}">
										<a href="javascript:void(0)"><i ng-click="show_comments({{$review_id}})"  class="icon icon-comment-black-rectangular-speech-bubble-interface-symbol"></i></a>
									</td>
								</tr>
								@endforeach
								@if(count($review_column) < 1)
								<tr>
									<td colspan="4" class="text-center"> {{trans('messages.store_dashboard.no_item_rating_found')}}</td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</main>

		<!-- Add category model !-->
		<div class="modal fade" id="comments_modal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<i class="icon icon-close-2"></i>
						</button>
					</div>
					<div class="modal-body">
						<form class="form_valitate">
							<div class="form-group d-flex menu-name">
								<ul class="comment_list dotted">

									<li>sadfaszd</li>
								</ul>
							</div>
							<div class="mt-3 pt-4 modal-footer px-0 text-right">
								<button data-dismiss="modal" type="cancel" class="btn btn-primary theme-color">{{trans('messages.store_dashboard.close')}}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- End Add category model !-->

		@stop
		@push('scripts')
		<script type="text/javascript">

		</script>
		@endpush