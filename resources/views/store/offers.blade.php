@extends('template')

@section('main')
<main id="site-content" role="main" ng-controller="offer">
	<div class="partners offers-page py-5 px-0" >
		@include ('store.navigation')
		<div class="container" ng-init="offers={{ $offer }};offer_index='';offer_id='';edit_offer=''">
			<div class="my-4 d-flex justify-content-between align-items-center">
				<h1 class="title">{{trans('messages.store_dashboard.offers')}}</h1>
				<a href="javascript:void(0)" class="btn btn-primary" ng-click="set_offers($index,'Add Offer')">{{trans('admin_messages.add_offers')}}</a>
			</div>

			<div class="offers-table my-4">
				<div class="table-responsive">
					<table>
						<thead>
							<tr>
								<th>{{trans('messages.store_dashboard.id')}}</th>
								<th>{{trans('messages.store_dashboard.Offer_title')}}</th>
								<th>{{trans('admin_messages.start_date')}}</th>
								<th>{{trans('admin_messages.end_date')}}</th>

								<th>{{trans('messages.profile.percentage')}}</th>
								<th>{{trans('messages.profile_orders.status')}}</th>
								<th>{{trans('messages.store_dashboard.actions')}}</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="offer_data in offers">
								<td>@{{offer_data.id}}</td>
								<td>@{{offer_data.offer_title}}</td>
								<td>@{{offer_data.start_date}}</td>
								<td>@{{offer_data.end_date}}</td>
								<td>@{{offer_data.percentage}}</td>
								<td>

									<input type="hidden" id="status_check_@{{$index}}" value="@{{offer_data.status}}">

									<label class="switch">
										<input type="checkbox" id="checkbox_offer_@{{$index}}" class="offer_check" data-val="@{{offer_data.id}}">

										<div class="toggle-slider round"><span class="on">{{trans('admin_messages.active')}}</span><span class="off">{{trans('admin_messages.inactive')}}</span></div>
									</label>

								</td>
								<td>
									<a href="javascript:void(0)" title="edit"  class="icon icon-pencil-edit-button site-color mx-2" ng-click="set_offers($index,'Edit offer')"></a>

									<a href="javascript:void(0)" title="delete" data-toggle="modal" data-target="#delete_modal" class="icon icon-rubbish-bin site-color"  ng-click="set($index,offer_data.id);"></a>
								</td>
							</tr>
							<tr ng-hide="offers.length>0"><td colspan="7">{{trans('messages.store_dashboard.no_offers')}}</td> </tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!--category delete !-->

	<div class="modal fade" id="delete_modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<i class="icon icon-close-2"></i>
					</button>
					<h3 class="modal-title">{{trans('messages.store_dashboard.delete_this_offer')}}</h3>
				</div>
				<div class="modal-body">
					<p>{{trans('messages.store_dashboard.this_will_delete_offer_action_cannot_be_undone')}}</p>
					<p class="text-danger delete_item_msg"> </p>
				</div>
				<div class="modal-footer text-right">
					<button type="reset" data-dismiss="modal" class="offer_cancel_button btn btn-primary theme-color">{{trans('messages.store.cancel')}}</button>
					<button type="submit" class="btn btn-theme ml-2" data-dismiss="modal" ng-click="delete_offer()">{{trans('messages.store.submit')}}</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="offer_modal" role="dialog" >

		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<i class="icon icon-close-2"></i>
					</button>
					<h5 class="my-3 modal-title text-capitalize text-center" ng-if="offer_title=='Edit offer'">{{trans('admin_messages.edit_store_offer')}}</h5>
					<h5 class="my-3 modal-title text-capitalize text-center" ng-if="offer_title=='Add Offer'">{{trans('admin_messages.add_offer')}}</h5>
				</div>
				<div class="modal-body">
					<form id="offer_form">
						<div class="form-group d-md-flex">
							<div class="col-md-4">
								<label>
									{{trans('messages.store_dashboard.Offer_title')}}
									<span class="required">*</span>
								</label>
							</div>
							<div class="col-md-8">
								<input type="hidden" ng-model="edit_offer.id" id='offer_id' name='offer_id'>
								<input type="text" autocomplete="off" ng-model="edit_offer.offer_title" id='offer_title' name='offer_title'>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-4">
								<label>
									{{trans('messages.store_dashboard.offer_description')}}
									<span class="required">*</span>
								</label>
							</div>
							<div class="col-md-8">
								<input type="text" autocomplete="off" ng-model="edit_offer.offer_description" id="offer_description" name='offer_description'>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-4">
								<label>
									{{trans('admin_messages.start_date')}}
									<span class="required">*</span>
								</label>
							</div>
							<div class="col-md-8">
								<input type="text" autocomplete="off" readonly="" ng-model="edit_offer.start_date"  id="from" name='from'>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-4">
								<label>
									{{trans('admin_messages.end_date')}}
									<span class="required">*</span>
								</label>
							</div>
							<div class="col-md-8">
								<input type="text" autocomplete="off" readonly="" ng-model="edit_offer.end_date"   id="to" name='to'>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-4">
								<label>
									{{trans('messages.profile.percentage')}}
									<span class="required">*</span>
								</label>
							</div>
							<div class="col-md-8">
								<input type="number" min="0" ng-model="edit_offer.percentage" id="percentage" name='percentage'>
							</div>
						</div>
						{{--
						<div class="form-group d-md-flex">
							<div class="col-md-4">
								<label>
									Offer min price
									<span class="required">*</span>
								</label>
							</div>
							<div class="col-md-8">
								<input type="text" autocomplete="off" ng-model="edit_offer.min_price" id="offer_min_price" name='offer_min_price'>
							</div>
						</div>

						<div class="form-group d-md-flex">
							<div class="col-md-4">
								<label>
									Offer max price
									<span class="required">*</span>
								</label>
							</div>
							<div class="col-md-8">
								<input type="text" autocomplete="off" ng-model="edit_offer.offer_max_price" id="offer_max_price" name='offer_max_price'>
							</div>
						</div>
						--}}
						<div class="mt-3 pt-4 modal-footer px-0 text-right">
							<button data-dismiss="modal" type="cancel" class="btn btn-primary theme-color">{{trans('messages.store.cancel')}}</button>
							<button type="submit" class="btn btn-theme ml-2" ng-click="add_offer()">{{trans('messages.store.submit')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</main>
@stop
