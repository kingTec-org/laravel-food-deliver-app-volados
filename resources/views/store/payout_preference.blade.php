@extends('template')

@section('main')
<main id="site-content" role="main" ng-controller="payout_preferences1" ng-cloak>
	<div class="partners">
		@include ('store.navigation')
		<div class="partner-payments mt-4 mb-5">
			<h1>{{trans('messages.store.payouts')}}</h1>
			<div class="my-4">
				
				<div class="week-activity mt-3 d-md-flex">
					<div class="col-md-4">
						<span class="d-block light-color mb-2">{{trans('messages.store_dashboard.net_earnings')}}</span>
						<h4>{!!$current_week_symbol!!} {{$current_week_profit}}</h4>
					</div>
					<div class="col-md-4">
						<span class="d-block light-color mb-2">{{trans('messages.profile.orders')}}</span>
						<h4>{{$current_week_orders}}</h4>
					</div>
					<div class="col-md-4">
						<span class="d-block light-color mb-2">{{trans('messages.store.next_payment')}}</span>
						<h4>{{date('d', strtotime('next monday')).' '.trans('messages.driver.'.date('M', strtotime('next monday')))}}</h4>
					</div>
				</div>
			</div>
			<div class="payment-history my-5">
				<h5>{{trans('messages.store.payout_history')}}</h5>
				<div class="table-responsive">
					<table>
						<thead>
							<tr>
								<th>{{trans('messages.store.week_of')}}</th>
								<th>{{trans('messages.profile.orders')}}</th>
								<th>{{trans('messages.store.sale')}}</th>
								<th>{{trans('messages.profile_orders.tax')}}</th>
								<th>{{trans('messages.profile_orders.total')}}</th>
								<th>{{ site_setting('site_name') }} {{trans('messages.store.fee')}}</th>
								<th>{{trans('messages.store_dashboard.net_payout')}}</th>
								<th>{{trans('messages.store.payout_status')}}</th>
								<th>{{trans('admin_messages.penalty')}}</th>
								<th>{{trans('messages.store.paid_penalty')}}</th>
								<th>{{trans('messages.profile_orders.status')}}</th>
								<th>{{trans('messages.store.weekly_statement')}}</th>
							</tr>
						</thead>
						<tbody>
							@foreach($weekly_payouts as $key=>$value)
							<tr class="main-list">
								<td><a href="{{url('store/payout_details').'/'.$value['table_week']}}"><span class="theme-color text-nowrap">{{$value['week']}}</span></a></td>
								<td>{{$value['count']}}</td>
								<td>{!!$value['currency_symbol']!!} {{$value['subtotal']}}</td>
								<td>{!!$value['currency_symbol']!!} {{$value['tax']}}</td>
								<td>{!!$value['currency_symbol']!!} {{$value['total_amount']}}</td>
								<td>{!!$value['currency_symbol']!!} {{$value['gofer_fee']}}</td>
								<td>{!!$value['currency_symbol']!!} {{$value['total_payout']}}</td>
								<td> {{$value['payout_status']}}</td>
								<td>{!!$value['currency_symbol']!!} {{$value['penalty']}}</td>
								<td>{!!$value['currency_symbol']!!} {{$value['paid_penalty']}}</td>
								<td class="status pending"><label>{{$value['status']}}</label></td>
								<td class="text-center">
									<a href="export_data/{{$value['table_week']}}" class="icon icon-download-button theme-color" id="payout_export" data-val="{{$value['table_week']}}"></a>
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					@if(count($weekly_payouts)==0)
					<div class="p-4 text-center">
						<h4 class="m-0">{{trans('messages.store.no_payouts_available')}} !</h4>
					</div>
					@endif
				</div>
				{{--
				<div class="d-flex align-items-center justify-content-end">
					<span>1 of 1</span>
					<nav aria-label="Page navigation example" class="my-3 ml-3">
						<ul class="pagination">
							<li class="page-item disabled">
								<a class="page-link" href="#" tabindex="-1">{{trans('messages.store.previous')}}</a>
							</li>
							<li class="page-item">
								<a class="page-link" href="#">{{trans('admin_messages.next')}}</a>
							</li>
						</ul>
					</nav>
				</div>
				--}}
			</div>
			<div class="bank-details my-5">
				<h5>
					{{trans('messages.store.bank_account_details')}}  
				</h5>
				@if(!$payout_preference)
				<div class="col-12 mt-3">
					<a href="javascript:voi(0)" data-toggle="modal" data-target="#account_modal" class="btn btn-theme modal_popup">{{trans('messages.store.add')}}</a>
				</div>
				@endif
				@if($payout_preference)
				<div class="table-responsive">
					<table>
						<thead>
							<tr>
								<th>{{trans('messages.store.method')}}</th>
								<th>{{trans('messages.store.details')}}</th>
								<th>{{trans('messages.profile_orders.status')}}</th>
								<th>{{trans('admin_messages.edit')}}</th>
							</tr>
						</thead>
						<tbody>
							<tr class="main-list">
								<td>{{$payout_preference->payout_method}}</td>
								<td>{{$payout_preference->paypal_email}}
									@if($payout_preference->currency_code)
									({{$payout_preference->currency_code}})
									@endif
								</td>
								<td>{{trans('messages.store.ready')}}</td>
								<td><a href="javascript:voi(0)" data-toggle="modal" data-target="#account_modal" class="btn btn-theme modal_popup" ng-click="edit_payout($user_id)">{{trans('admin_messages.edit')}}</a></td>
							</tr>
						</tbody>
					</table>
				</div>
				@endif
			</div>
		</div>
	</div>

	<input type="hidden" id="user_id_data" value="{{$user_id}}">
	<div class="add-payout-modal modal fade" id="account_modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					{{trans('messages.store.add_payout_method')}}
					<button type="button" class="close" data-dismiss="modal">
						<i class="icon icon-close-2"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="flash-container" id="popup1_flash-container"> </div>
					<form class="modal-add-payout-pref" method="post" action="{{'update_payout_preferences/'.$user_id }}" id="payout_preference_submit" accept-charset="UTF-8" enctype="multipart/form-data">
						@csrf

						{!! Form::token() !!}
						<div class="panel-body" ng-init="payout_country={{json_encode(old('payout_country') ?: '')}};payout_currency={{json_encode(old('currency') ?: '')}};country_currency={{json_encode($country_currency)}};mandatory={{ json_encode($mandatory)}};mandatory_field={{ json_encode($mandatory_field)}};old_currency='{{ old('currency') ? json_encode(old('currency')) : '' }}';payout_responce=''">
							<div class="select-cls">
								<label for="payout_info_payout_country">
									{{trans('messages.profile.country')}}
									<span class="required">*</span>
								</label>
								<div class="select">
									{!! Form::select('payout_country', $country_list, '', ['autocomplete' => 'billing country', 'id' => 'payout_info_payout_country','placeholder'=>'Select','ng-model'=>'payout_country','style'=>'min-width:140px;']) !!}
									<span class="text-danger">{{$errors->first('payout_country')}}</span>
								</div>
							</div>

							<div ng-if="mandatory_field[payout_country]['currency']" class="select-cls" id="currency_payout">
								<label for="payout_info_payout_currency">
									{{trans('messages.store.currency')}}
									<span class="required">*</span>
								</label>
								<div   class="select">
									{!! Form::select('currency', $currency,'', ['autocomplete' => 'billing currency', 'id' => 'payout_info_payout_currency','placeholder'=>'Select','style'=>'min-width:140px;','ng-model'=>'payout_currency']) !!}
									<span class="text-danger">{{$errors->first('currency')}}</span>
								</div>
							</div>
							<div ng-show="payout_country == 'JP'">
								<label class="" for="phone_number">
									{{trans('messages.store.phone_number')}}
									<span style="color:red">*</span></label>
									{!! Form::text('phone_number', '', ['id' => 'phone_number', 'class' => 'form-control']) !!}
								</div>

								<div ng-if="payout_country == 'JP'" class="select-cls row-space-3">
									<label for="user_gender">
										{{trans('messages.store.gender')}}
									</label>
									<div class="select">
										{!! Form::select('gender', ['male' => 'Male', 'female' => 'Female'], 'male', ['id' => 'user_gender', 'placeholder' => 'Gender', 'class' => 'focus','style'=>'min-width:140px;']) !!}
										<span class="text-danger">{{ $errors->first('gender') }}</span>
									</div>
								</div>

								<div ng-class="(payout_country == 'JP'? 'jp_form row':'')" class="clearfix row-space-2">
									<div class="country-info" ng-class="(payout_country == 'JP'? 'col-md-6 col-12':'')">
										<label ng-if="payout_country == 'JP'"><b>{{trans('messages.store.address_kana')}}:</b></label>
										<div>
											<label for="payout_info_payout_address2">{{trans('messages.profile.address')}} 1<span style="color:red">*</span></label>
											{!! Form::text('address1', '', ['id' => 'address1', 'class' => 'form-control']) !!}
										</div>

										<div>
											<label ng-if="payout_country == 'JP'" for="payout_info_payout_address2">{{trans('messages.store.town')}}<span style="color:red">*</span></label>
											<label ng-if="payout_country != 'JP'" for="payout_info_payout_address2">{{trans('messages.profile.address')}} 2</label>
											{!! Form::text('address2', '', ['id' => 'address2', 'class' => 'form-control']) !!}
										</div>

										<div>
											<label for="payout_info_payout_city">{{trans('messages.driver.city')}} <span style="color:red">*</span></label>
											{!! Form::text('city', '', ['id' => 'city', 'class' => 'form-control']) !!}
										</div>

										<div>
											<label for="payout_info_payout_state">{{trans('admin_messages.state')}} / {{trans('messages.store.province')}}<span style="color:red">*</span></label>
											{!! Form::text('state', '', ['id' => 'state', 'class' => 'form-control']) !!}
										</div>

										<div>
											<label for="payout_info_payout_zip">{{trans('messages.profile.postal_code')}} <span style="color:red">*</span></label>
											{!! Form::text('postal_code', '', ['id' => 'postal_code', 'class' => 'form-control']) !!}
											<span class="text-danger">{{$errors->first('postal_code')}}</span>
										</div>
									</div>
									<div ng-if="payout_country == 'JP'" class="country-info col-md-6 col-12 mt-3 mt-md-0">
										<label><b>{{trans('messages.store.address_kanji')}}:</b></label>
										<div>
											<label for="payout_info_payout_address2">{{trans('messages.profile.address')}} 1<span style="color:red">*</span></label>
											{!! Form::text('kanji_address1', '', ['id' => 'kanji_address1', 'class' => 'form-control']) !!}
										</div>

										<div>
											<label for="payout_info_payout_address2">{{trans('messages.store.town')}}<span style="color:red">*</span></label>
											{!! Form::text('kanji_address2', '', ['id' => 'kanji_address2', 'class' => 'form-control']) !!}
										</div>

										<div>
											<label for="payout_info_payout_city">{{trans('messages.driver.city')}} <span style="color:red">*</span></label>
											{!! Form::text('kanji_city', '', ['id' => 'kanji_city', 'class' => 'form-control']) !!}
										</div>

										<div>
											<label for="payout_info_payout_state">{{trans('admin_messages.state')}} / {{trans('messages.store.province')}}<span style="color:red">*</span></label>
											{!! Form::text('kanji_state', '', ['id' => 'kanji_state', 'class' => 'form-control']) !!}
										</div>

										<div>
											<label for="payout_info_payout_zip">{{trans('messages.profile.postal_code')}} <span style="color:red">*</span></label>
											{!! Form::text('kanji_postal_code', '', ['id' => 'kanji_postal_code', 'class' => 'form-control']) !!}
											<span class="text-danger">{{$errors->first('kanji_postal_code')}}</span>
										</div>
									</div>
								</div>

								<!-- Branch code -->
								<!-- Account Number -->

								<div ng-repeat="(field_name,validation) in mandatory_field[payout_country]">
									<div ng-if="field_name!='currency' && field_name!='iban'">
										<label ng-switch="mandatory_field[payout_country]['iban'] && field_name=='account_number'" class="" for="@{{field_name}}" >
											<span ng-switch-when='true'>  {{trans('messages.store.iban_number')}}</span>
											<span ng-switch-default>  @{{ field_name | translations }}</span>
											<span ng-if="validation" style="color:red">*</span>
										</label> 

										{!! Form::text('@{{field_name}}','',['id'=>'@{{field_name}}','class'=>'form-control','data-rule-required'=>'@{{validation}}',"ng-value"=>'payout_responce[field_name]']) !!}
									</div>

								</div>
								<input type="hidden" id="is_iban" name="is_iban" ng-value="mandatory_field[payout_country]['iban']?'Yes':'No'">
								<input type="hidden" id="is_branch_code" name="is_branch_code"  ng-value="mandatory_field[payout_country]['branch_code']? 'Yes':'No'">

								<div id="legal_document" class="legal_document">
									<label class="control-label required-label" >{{trans('admin_messages.document')}} ({{trans('messages.store.jpg_or_png_format')}})<span style="color:red" id="document_smbl">*</span></label>
									<div class="col-12 p-0">
										{!! Form::file('document', ['id' => 'document', 'class' => '' ,"accept"=>".jpg,.jpeg,.png", 'style'=>'display: none']) !!}
										<a id="choose_files" class="choose_file_type">{{trans('messages.profile.choose_file')}}</a>
										<span class="text-danger">{{$errors->first('document')}}</span>
									</div>
									@if($payout_preference && isset($payout_preference->document_image))
									<img class="mt-2" src="{{$payout_preference->document}}" width="100" height="100">
									@endif
								</div>
							</div>
							<input type="hidden" name="holder_type" value="individual" id="holder_type">
							<input type="hidden" name="stripe_token" id="stripe_token" >
							<p class="text-danger my-4" id="stripe_errors"></p>
							<div class="panel-footer mt-4">
								<input type="submit" value="{{ trans('messages.driver.submit') }}" class="btn btn-theme">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- Popup for get Stripe datas -->
		<!-- end Popup -->

		<div class="modal hide" id="payout_popup1" aria-hidden="false" style="" tabindex="-1">
			<div class="modal-table">
				<div class="modal-cell">
					<div id="modal-add-payout-set-address" class="modal-content">
						<div class="panel-header add_payout_mtd">
							<a data-behavior="modal-close" class="panel-close" href="javascript:void(0);"></a>
							{{ trans('messages.store.add_payout_method') }}
						</div>
						<div class="panel-header hide edit_payout_mtd">
							<a data-behavior="modal-close" class="panel-close" href="javascript:void(0);"></a>
							{{ trans('messages.store.edit_payout_preference') }}
						</div>
						<div class="flash-container" id="popup1_flash-container"> </div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal hide" id="payout_popup3" aria-hidden="false" style="" tabindex="-1">
			<div class="modal-table">
				<div class="modal-cell">
					<div id="modal-add-payout-set-address" class="modal-content">
						<div class="panel-header">
							<a data-behavior="modal-close" class="panel-close" href="javascript:void(0);"></a>
							{{ trans('messages.store.add_payout_method') }}
						</div>
						<div class="flash-container hide" id="popup3_flash-container">
							<div class="alert alert-error alert-error alert-header">
								<a class="close alert-close" href="javascript:void(0);">
								</a>
								<i class="icon alert-icon icon-alert-alt"></i>{{trans('messages.store.valid_email')}}</div>
							</div>

							<form method="post" id="payout_paypal" action="{{ url('users/payout_preferences/'.$user_id) }}" accept-charset="UTF-8">
								{!! Form::token() !!}
								<input type="hidden" id="payout_info_payout3_address1" value="" name="address1">
								<input type="hidden" id="payout_info_payout3_address2" value="" name="address2">
								<input type="hidden" id="payout_info_payout3_city" value="" name="city">
								<input type="hidden" id="payout_info_payout3_country" value="" name="country">
								<input type="hidden" id="payout_info_payout3_state" value="" name="state">
								<input type="hidden" id="payout_info_payout3_zip" value="" name="postal_code">
								<input type="hidden" id="payout3_method" value="" name="payout_method">
								<div class="panel-body">
									PayPal {{ trans('messages.store.email_id') }}
									<input type="text" name="paypal_email" id="paypal_email" >
								</div>
								<div class="panel-footer">
									<input type="submit" value="{{ trans('messages.account.submit') }}" id="modal-paypal-submit" class="btn btn-primary">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="blank_address" value="{{trans('messages.account.blank_address')}}">
			<input type="hidden" id="blank_city" value="{{trans('messages.account.blank_city')}}">
			<input type="hidden" id="blank_post" value="{{trans('messages.account.blank_post')}}">
			<input type="hidden" id="blank_country" value="{{trans('messages.account.blank_country')}}">
			<input type="hidden" id="choose_method" value="{{trans('messages.account.choose_method')}}">
		</main>
		@stop

		@push('scripts')
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

		<script type="text/javascript">
			if($('#payout_info_payout_country').val()!='OT') {
				Stripe.setPublishableKey('{{@$stripe_data}}');
			}

			function stripeResponseHandler(status, response) {
				$('#payout_preference_submit').removeClass('loading');
				if (response.error) {
					$("#stripe_errors").html("");
					if(response.error.message == "Must have at least one letter") {
						$("#stripe_errors").html('Please fill all required fields');
					}

					else {
						$("#stripe_errors").html(response.error.message);
					}
					return false;
				} 

				else {
					$("#stripe_errors").html("");
					var token = response['id'];
					$("#stripe_token").val(token);
					$('#payout_preference_submit').removeClass('loading');
					$("#payout_preference_submit").submit();
					return true;
				}
			}

		// Code for the Validator
		var $payout_valitation = $('#payout_preference_submit').validate({
			rules: {
				iban: { custom_required: true },                
				bsb: { custom_required: true },                
				transit_number: { custom_required: true },                
				institution_number: { custom_required: true },                
				account_holder_name: { custom_required: true },                
				currency: { custom_required: true },                
				account_number: { custom_required: true },                
				routing_number: { custom_required: true },                
				sort_code: { custom_required: true },                
				account_owner_name: { custom_required: true },                
				bank_name: { custom_required: true },                
				branch_name: { custom_required: true },                
				branch_code: { custom_required: true },                
				clearing_code: { custom_required: true },                
				ssn_last_4: { custom_required: true },
				payout_country : { required : true},
				currency : { required : true},
				phone_number : { required : true},
				gender : { required : true},
				address1 : { required : true},
				city : { required : true},
				state : { required : true},
				postal_code : { required : true},                
			},
			messages: {
				payout_country : { required : Lang.get('js_messages.store.field_required')},
				currency : { required : Lang.get('js_messages.store.field_required')},
				phone_number : { required : Lang.get('js_messages.store.field_required')},
				gender : { required : Lang.get('js_messages.store.field_required')},
				address1 : { required : Lang.get('js_messages.store.field_required')},
				city : { required : Lang.get('js_messages.store.field_required')},
				state : { required : Lang.get('js_messages.store.field_required')},
				postal_code : { required : Lang.get('js_messages.store.field_required')},
			},
			errorElement: "span",
			errorClass: "text-danger",
			errorPlacement: function( label, element ) {
				if(element.attr( "data-error-placement" ) === "container" ) {
					container = element.attr('data-error-container');
					$(container).append(label);
				} 

				else {
					label.insertAfter( element ); 
				}
			},
		});

		$(document).ready(function () {
			$("#payout_preference_submit").submit(function (event) {
				if($('#payout_info_payout_country').val()!='OT'){
					stripe_token = $("#stripe_token").val();
					if(stripe_token != ''){
						return true;
					}
				}

				var $valid = $('#payout_preference_submit').valid();
				if(!$valid) {
					$payout_valitation.focusInvalid();
					return false;
				}

				if($('#account_number').val() == '')
				{
					$("#stripe_errors").html('Please fill all required fields');
					return false;
				}
				if($('#holder_name').val() == '')
				{
					$("#stripe_errors").html('Please fill all required fields');
					return false;
				}
				else if($('#address1').val() == '')
				{
					$("#stripe_errors").html('Please fill all required fields');
					return false;
				}
				else if($('#city').val() == '')
				{
					$("#stripe_errors").html('Please fill all required fields');
					return false;
				}
				else if($('#state').val() == '')
				{
					$("#stripe_errors").html('Please fill all required fields');
					return false;
				}
				else if($('#postal_code').val() == '')
				{
					$("#stripe_errors").html('Please fill all required fields');
					return false;
				}
				if($('#payout_info_payout_country').val() == 'JP')
				{
					if($('#bank_name').val() == '')
					{
						$("#stripe_errors").html('Please fill all required fields');
						return false;
					}

					if($('#branch_name').val() == '')
					{
						$("#stripe_errors").html('Please fill all required fields');
						return false;
					}
				}

				console.log($('[name="currency"]').val());
				is_iban = $('#is_iban').val();
				is_branch_code = $('#is_branch_code').val();
				var bankAccountParams = {
					country: $('#payout_info_payout_country').val(),
					currency: $('[name="currency"]').val(),
              // routing_number: $('#routing_number').val(),
              account_number: $('#account_number').val(),
              account_holder_name: $('#account_holder_name').val(),
              account_holder_type: $('#holder_type').val()
          }

          if(is_iban == 'No')
          {
          	if(is_branch_code == 'Yes')
          	{
          		if($('#payout_info_payout_country').val() != 'GB' && $('[name="currency"]').val() != 'EUR')
          		{
          			if($('#routing_number').val() == '')
          			{
          				$("#stripe_errors").html('Please fill all required fields');
          				return false;
          			}
          			if($('#branch_code').val() == '')
          			{
          				$("#stripe_errors").html('Please fill all required fields');
          				return false;
          			}

          			bankAccountParams.routing_number = $('#routing_number').val()+'-'+$('#branch_code').val();
          		}
          	}

          	else
          	{
          		if($('#payout_info_payout_country').val() != 'GB' && $('[name="currency"]').val() != 'EUR' && $('#payout_info_payout_country').val()!='OT')
          		{
          			if($('#routing_number').val() == '')
          			{
          				$("#stripe_errors").html('Please fill all required fields');
          				return false;
          			}
          			bankAccountParams.routing_number = $('#routing_number').val();
          		}
          	}
          }
          $('#payout_preference_submit').addClass('loading');
          if($('#payout_info_payout_country').val()!='OT'){
          	Stripe.bankAccount.createToken(bankAccountParams, stripeResponseHandler);
          	return false;
          }
          $('.icon-close-2').trigger('click');
      });
		});
	</script>

	<script type="text/javascript">
		@if (count($errors) > 0)
		$('.modal_popup').trigger('click');
		@endif
	</script>

	@endpush