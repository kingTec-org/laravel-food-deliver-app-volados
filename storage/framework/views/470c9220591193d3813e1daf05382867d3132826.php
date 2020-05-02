<?php $__env->startSection('main'); ?>
<main id="site-content" role="main" ng-controller="payout_preferences1" ng-cloak>
	<div class="partners">
		<?php echo $__env->make('store.navigation', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div class="partner-payments mt-4 mb-5">
			<h1><?php echo e(trans('messages.store.payouts')); ?></h1>
			<div class="my-4">
				
				<div class="week-activity mt-3 d-md-flex">
					<div class="col-md-4">
						<span class="d-block light-color mb-2"><?php echo e(trans('messages.store_dashboard.net_earnings')); ?></span>
						<h4><?php echo $current_week_symbol; ?> <?php echo e($current_week_profit); ?></h4>
					</div>
					<div class="col-md-4">
						<span class="d-block light-color mb-2"><?php echo e(trans('messages.profile.orders')); ?></span>
						<h4><?php echo e($current_week_orders); ?></h4>
					</div>
					<div class="col-md-4">
						<span class="d-block light-color mb-2"><?php echo e(trans('messages.store.next_payment')); ?></span>
						<h4><?php echo e(date('d', strtotime('next monday')).' '.trans('messages.driver.'.date('M', strtotime('next monday')))); ?></h4>
					</div>
				</div>
			</div>
			<div class="payment-history my-5">
				<h5><?php echo e(trans('messages.store.payout_history')); ?></h5>
				<div class="table-responsive">
					<table>
						<thead>
							<tr>
								<th><?php echo e(trans('messages.store.week_of')); ?></th>
								<th><?php echo e(trans('messages.profile.orders')); ?></th>
								<th><?php echo e(trans('messages.store.sale')); ?></th>
								<th><?php echo e(trans('messages.profile_orders.tax')); ?></th>
								<th><?php echo e(trans('messages.profile_orders.total')); ?></th>
								<th><?php echo e(site_setting('site_name')); ?> <?php echo e(trans('messages.store.fee')); ?></th>
								<th><?php echo e(trans('messages.store_dashboard.net_payout')); ?></th>
								<th><?php echo e(trans('messages.store.payout_status')); ?></th>
								<th><?php echo e(trans('admin_messages.penalty')); ?></th>
								<th><?php echo e(trans('messages.store.paid_penalty')); ?></th>
								<th><?php echo e(trans('messages.profile_orders.status')); ?></th>
								<th><?php echo e(trans('messages.store.weekly_statement')); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php $__currentLoopData = $weekly_payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<tr class="main-list">
								<td><a href="<?php echo e(url('store/payout_details').'/'.$value['table_week']); ?>"><span class="theme-color text-nowrap"><?php echo e($value['week']); ?></span></a></td>
								<td><?php echo e($value['count']); ?></td>
								<td><?php echo $value['currency_symbol']; ?> <?php echo e($value['subtotal']); ?></td>
								<td><?php echo $value['currency_symbol']; ?> <?php echo e($value['tax']); ?></td>
								<td><?php echo $value['currency_symbol']; ?> <?php echo e($value['total_amount']); ?></td>
								<td><?php echo $value['currency_symbol']; ?> <?php echo e($value['gofer_fee']); ?></td>
								<td><?php echo $value['currency_symbol']; ?> <?php echo e($value['total_payout']); ?></td>
								<td> <?php echo e($value['payout_status']); ?></td>
								<td><?php echo $value['currency_symbol']; ?> <?php echo e($value['penalty']); ?></td>
								<td><?php echo $value['currency_symbol']; ?> <?php echo e($value['paid_penalty']); ?></td>
								<td class="status pending"><label><?php echo e($value['status']); ?></label></td>
								<td class="text-center">
									<a href="export_data/<?php echo e($value['table_week']); ?>" class="icon icon-download-button theme-color" id="payout_export" data-val="<?php echo e($value['table_week']); ?>"></a>
								</td>
							</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</tbody>
					</table>
					<?php if(count($weekly_payouts)==0): ?>
					<div class="p-4 text-center">
						<h4 class="m-0"><?php echo e(trans('messages.store.no_payouts_available')); ?> !</h4>
					</div>
					<?php endif; ?>
				</div>
				
			</div>
			<div class="bank-details my-5">
				<h5>
					<?php echo e(trans('messages.store.bank_account_details')); ?>  
				</h5>
				<?php if(!$payout_preference): ?>
				<div class="col-12 mt-3">
					<a href="javascript:voi(0)" data-toggle="modal" data-target="#account_modal" class="btn btn-theme modal_popup"><?php echo e(trans('messages.store.add')); ?></a>
				</div>
				<?php endif; ?>
				<?php if($payout_preference): ?>
				<div class="table-responsive">
					<table>
						<thead>
							<tr>
								<th><?php echo e(trans('messages.store.method')); ?></th>
								<th><?php echo e(trans('messages.store.details')); ?></th>
								<th><?php echo e(trans('messages.profile_orders.status')); ?></th>
								<th><?php echo e(trans('admin_messages.edit')); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr class="main-list">
								<td><?php echo e($payout_preference->payout_method); ?></td>
								<td><?php echo e($payout_preference->paypal_email); ?>

									<?php if($payout_preference->currency_code): ?>
									(<?php echo e($payout_preference->currency_code); ?>)
									<?php endif; ?>
								</td>
								<td><?php echo e(trans('messages.store.ready')); ?></td>
								<td><a href="javascript:voi(0)" data-toggle="modal" data-target="#account_modal" class="btn btn-theme modal_popup" ng-click="edit_payout($user_id)"><?php echo e(trans('admin_messages.edit')); ?></a></td>
							</tr>
						</tbody>
					</table>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<input type="hidden" id="user_id_data" value="<?php echo e($user_id); ?>">
	<div class="add-payout-modal modal fade" id="account_modal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<?php echo e(trans('messages.store.add_payout_method')); ?>

					<button type="button" class="close" data-dismiss="modal">
						<i class="icon icon-close-2"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="flash-container" id="popup1_flash-container"> </div>
					<form class="modal-add-payout-pref" method="post" action="<?php echo e('update_payout_preferences/'.$user_id); ?>" id="payout_preference_submit" accept-charset="UTF-8" enctype="multipart/form-data">
						<?php echo csrf_field(); ?>

						<?php echo Form::token(); ?>

						<div class="panel-body" ng-init="payout_country=<?php echo e(json_encode(old('payout_country') ?: '')); ?>;payout_currency=<?php echo e(json_encode(old('currency') ?: '')); ?>;country_currency=<?php echo e(json_encode($country_currency)); ?>;mandatory=<?php echo e(json_encode($mandatory)); ?>;mandatory_field=<?php echo e(json_encode($mandatory_field)); ?>;old_currency='<?php echo e(old('currency') ? json_encode(old('currency')) : ''); ?>';payout_responce=''">
							<div class="select-cls">
								<label for="payout_info_payout_country">
									<?php echo e(trans('messages.profile.country')); ?>

									<span class="required">*</span>
								</label>
								<div class="select">
									<?php echo Form::select('payout_country', $country_list, '', ['autocomplete' => 'billing country', 'id' => 'payout_info_payout_country','placeholder'=>'Select','ng-model'=>'payout_country','style'=>'min-width:140px;']); ?>

									<span class="text-danger"><?php echo e($errors->first('payout_country')); ?></span>
								</div>
							</div>

							<div ng-if="mandatory_field[payout_country]['currency']" class="select-cls" id="currency_payout">
								<label for="payout_info_payout_currency">
									<?php echo e(trans('messages.store.currency')); ?>

									<span class="required">*</span>
								</label>
								<div   class="select">
									<?php echo Form::select('currency', $currency,'', ['autocomplete' => 'billing currency', 'id' => 'payout_info_payout_currency','placeholder'=>'Select','style'=>'min-width:140px;','ng-model'=>'payout_currency']); ?>

									<span class="text-danger"><?php echo e($errors->first('currency')); ?></span>
								</div>
							</div>
							<div ng-show="payout_country == 'JP'">
								<label class="" for="phone_number">
									<?php echo e(trans('messages.store.phone_number')); ?>

									<span style="color:red">*</span></label>
									<?php echo Form::text('phone_number', '', ['id' => 'phone_number', 'class' => 'form-control']); ?>

								</div>

								<div ng-if="payout_country == 'JP'" class="select-cls row-space-3">
									<label for="user_gender">
										<?php echo e(trans('messages.store.gender')); ?>

									</label>
									<div class="select">
										<?php echo Form::select('gender', ['male' => 'Male', 'female' => 'Female'], 'male', ['id' => 'user_gender', 'placeholder' => 'Gender', 'class' => 'focus','style'=>'min-width:140px;']); ?>

										<span class="text-danger"><?php echo e($errors->first('gender')); ?></span>
									</div>
								</div>

								<div ng-class="(payout_country == 'JP'? 'jp_form row':'')" class="clearfix row-space-2">
									<div class="country-info" ng-class="(payout_country == 'JP'? 'col-md-6 col-12':'')">
										<label ng-if="payout_country == 'JP'"><b><?php echo e(trans('messages.store.address_kana')); ?>:</b></label>
										<div>
											<label for="payout_info_payout_address2"><?php echo e(trans('messages.profile.address')); ?> 1<span style="color:red">*</span></label>
											<?php echo Form::text('address1', '', ['id' => 'address1', 'class' => 'form-control']); ?>

										</div>

										<div>
											<label ng-if="payout_country == 'JP'" for="payout_info_payout_address2"><?php echo e(trans('messages.store.town')); ?><span style="color:red">*</span></label>
											<label ng-if="payout_country != 'JP'" for="payout_info_payout_address2"><?php echo e(trans('messages.profile.address')); ?> 2</label>
											<?php echo Form::text('address2', '', ['id' => 'address2', 'class' => 'form-control']); ?>

										</div>

										<div>
											<label for="payout_info_payout_city"><?php echo e(trans('messages.driver.city')); ?> <span style="color:red">*</span></label>
											<?php echo Form::text('city', '', ['id' => 'city', 'class' => 'form-control']); ?>

										</div>

										<div>
											<label for="payout_info_payout_state"><?php echo e(trans('admin_messages.state')); ?> / <?php echo e(trans('messages.store.province')); ?><span style="color:red">*</span></label>
											<?php echo Form::text('state', '', ['id' => 'state', 'class' => 'form-control']); ?>

										</div>

										<div>
											<label for="payout_info_payout_zip"><?php echo e(trans('messages.profile.postal_code')); ?> <span style="color:red">*</span></label>
											<?php echo Form::text('postal_code', '', ['id' => 'postal_code', 'class' => 'form-control']); ?>

											<span class="text-danger"><?php echo e($errors->first('postal_code')); ?></span>
										</div>
									</div>
									<div ng-if="payout_country == 'JP'" class="country-info col-md-6 col-12 mt-3 mt-md-0">
										<label><b><?php echo e(trans('messages.store.address_kanji')); ?>:</b></label>
										<div>
											<label for="payout_info_payout_address2"><?php echo e(trans('messages.profile.address')); ?> 1<span style="color:red">*</span></label>
											<?php echo Form::text('kanji_address1', '', ['id' => 'kanji_address1', 'class' => 'form-control']); ?>

										</div>

										<div>
											<label for="payout_info_payout_address2"><?php echo e(trans('messages.store.town')); ?><span style="color:red">*</span></label>
											<?php echo Form::text('kanji_address2', '', ['id' => 'kanji_address2', 'class' => 'form-control']); ?>

										</div>

										<div>
											<label for="payout_info_payout_city"><?php echo e(trans('messages.driver.city')); ?> <span style="color:red">*</span></label>
											<?php echo Form::text('kanji_city', '', ['id' => 'kanji_city', 'class' => 'form-control']); ?>

										</div>

										<div>
											<label for="payout_info_payout_state"><?php echo e(trans('admin_messages.state')); ?> / <?php echo e(trans('messages.store.province')); ?><span style="color:red">*</span></label>
											<?php echo Form::text('kanji_state', '', ['id' => 'kanji_state', 'class' => 'form-control']); ?>

										</div>

										<div>
											<label for="payout_info_payout_zip"><?php echo e(trans('messages.profile.postal_code')); ?> <span style="color:red">*</span></label>
											<?php echo Form::text('kanji_postal_code', '', ['id' => 'kanji_postal_code', 'class' => 'form-control']); ?>

											<span class="text-danger"><?php echo e($errors->first('kanji_postal_code')); ?></span>
										</div>
									</div>
								</div>

								<!-- Branch code -->
								<!-- Account Number -->

								<div ng-repeat="(field_name,validation) in mandatory_field[payout_country]">
									<div ng-if="field_name!='currency' && field_name!='iban'">
										<label ng-switch="mandatory_field[payout_country]['iban'] && field_name=='account_number'" class="" for="{{field_name}}" >
											<span ng-switch-when='true'>  <?php echo e(trans('messages.store.iban_number')); ?></span>
											<span ng-switch-default>  {{ field_name | translations }}</span>
											<span ng-if="validation" style="color:red">*</span>
										</label> 

										<?php echo Form::text('{{field_name}}','',['id'=>'{{field_name}}','class'=>'form-control','data-rule-required'=>'{{validation}}',"ng-value"=>'payout_responce[field_name]']); ?>

									</div>

								</div>
								<input type="hidden" id="is_iban" name="is_iban" ng-value="mandatory_field[payout_country]['iban']?'Yes':'No'">
								<input type="hidden" id="is_branch_code" name="is_branch_code"  ng-value="mandatory_field[payout_country]['branch_code']? 'Yes':'No'">

								<div id="legal_document" class="legal_document">
									<label class="control-label required-label" ><?php echo e(trans('admin_messages.document')); ?> (<?php echo e(trans('messages.store.jpg_or_png_format')); ?>)<span style="color:red" id="document_smbl">*</span></label>
									<div class="col-12 p-0">
										<?php echo Form::file('document', ['id' => 'document', 'class' => '' ,"accept"=>".jpg,.jpeg,.png", 'style'=>'display: none']); ?>

										<a id="choose_files" class="choose_file_type"><?php echo e(trans('messages.profile.choose_file')); ?></a>
										<span class="text-danger"><?php echo e($errors->first('document')); ?></span>
									</div>
									<?php if($payout_preference && isset($payout_preference->document_image)): ?>
									<img class="mt-2" src="<?php echo e($payout_preference->document); ?>" width="100" height="100">
									<?php endif; ?>
								</div>
							</div>
							<input type="hidden" name="holder_type" value="individual" id="holder_type">
							<input type="hidden" name="stripe_token" id="stripe_token" >
							<p class="text-danger my-4" id="stripe_errors"></p>
							<div class="panel-footer mt-4">
								<input type="submit" value="<?php echo e(trans('messages.driver.submit')); ?>" class="btn btn-theme">
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
							<?php echo e(trans('messages.store.add_payout_method')); ?>

						</div>
						<div class="panel-header hide edit_payout_mtd">
							<a data-behavior="modal-close" class="panel-close" href="javascript:void(0);"></a>
							<?php echo e(trans('messages.store.edit_payout_preference')); ?>

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
							<?php echo e(trans('messages.store.add_payout_method')); ?>

						</div>
						<div class="flash-container hide" id="popup3_flash-container">
							<div class="alert alert-error alert-error alert-header">
								<a class="close alert-close" href="javascript:void(0);">
								</a>
								<i class="icon alert-icon icon-alert-alt"></i><?php echo e(trans('messages.store.valid_email')); ?></div>
							</div>

							<form method="post" id="payout_paypal" action="<?php echo e(url('users/payout_preferences/'.$user_id)); ?>" accept-charset="UTF-8">
								<?php echo Form::token(); ?>

								<input type="hidden" id="payout_info_payout3_address1" value="" name="address1">
								<input type="hidden" id="payout_info_payout3_address2" value="" name="address2">
								<input type="hidden" id="payout_info_payout3_city" value="" name="city">
								<input type="hidden" id="payout_info_payout3_country" value="" name="country">
								<input type="hidden" id="payout_info_payout3_state" value="" name="state">
								<input type="hidden" id="payout_info_payout3_zip" value="" name="postal_code">
								<input type="hidden" id="payout3_method" value="" name="payout_method">
								<div class="panel-body">
									PayPal <?php echo e(trans('messages.store.email_id')); ?>

									<input type="text" name="paypal_email" id="paypal_email" >
								</div>
								<div class="panel-footer">
									<input type="submit" value="<?php echo e(trans('messages.account.submit')); ?>" id="modal-paypal-submit" class="btn btn-primary">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" id="blank_address" value="<?php echo e(trans('messages.account.blank_address')); ?>">
			<input type="hidden" id="blank_city" value="<?php echo e(trans('messages.account.blank_city')); ?>">
			<input type="hidden" id="blank_post" value="<?php echo e(trans('messages.account.blank_post')); ?>">
			<input type="hidden" id="blank_country" value="<?php echo e(trans('messages.account.blank_country')); ?>">
			<input type="hidden" id="choose_method" value="<?php echo e(trans('messages.account.choose_method')); ?>">
		</main>
		<?php $__env->stopSection(); ?>

		<?php $__env->startPush('scripts'); ?>
		<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

		<script type="text/javascript">
			if($('#payout_info_payout_country').val()!='OT') {
				Stripe.setPublishableKey('<?php echo e(@$stripe_data); ?>');
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
		<?php if(count($errors) > 0): ?>
		$('.modal_popup').trigger('click');
		<?php endif; ?>
	</script>

	<?php $__env->stopPush(); ?>
<?php echo $__env->make('template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>