<?php $__env->startSection('main'); ?>
<div class="flash-container">
	<?php if(Session::has('message')): ?>
	<div class="alert <?php echo e(Session::get('alert-class')); ?> text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> <?php echo e(Session::get('message')); ?>

	</div>
	<?php endif; ?>
</div>
<main id="site-content" role="main" class="log-user driver" ng-controller="driver_signup">
	<div class="container">
		<div class="profile mb-5">
			<div class="d-md-flex">
			<?php echo $__env->make('driver.partner_navigation', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<div class="profile-info col-12 col-md-9 col-lg-9">
					<div class="row d-block">
						<div class="profile-title py-md-4">
							<h1 class="text-center text-uppercase"><?php echo e(trans('messages.profile.profile')); ?></h1>
						</div>
						<div class="pro-photo py-4 col-12 d-md-flex align-items-center justify-content-between text-center text-md-left">
							<div class="col-md-6">

								<?php if($driver_details): ?>

								<h4><?php echo e($driver_details->name); ?></h4>

								<?php if($driver_details->status==1): ?>

								<label class="active-label my-2"><?php echo e(trans('messages.profile.active')); ?></label>

								<?php elseif($driver_details->user->status==4): ?>

								<label class="label my-2"><?php echo e(trans('messages.profile.pending')); ?></label>
								<label><?php echo e(trans('messages.profile.document_details')); ?></label>
								<label><?php echo e(trans('messages.profile.vehicle_details')); ?></label>
								<?php else: ?>
								<label><?php echo e(trans('messages.driver.'.$driver_details->user->status_text_show)); ?></label>
								<?php endif; ?>
								<?php endif; ?>
							</div>
							<div class="col-md-6 mt-3 mt-md-0">
								<button type="button" class="btn btn-theme" ng-click="selectFile()"><?php echo e(trans('messages.profile.add_photo')); ?></button>

								<input type="file" ng-model="profile_image" style="display:none" accept="image/*" id="file" name='profile_image' accept=".jpg,.jpeg,.png" onchange="angular.element(this).scope().fileNameChanged(this)" />
							</div>
						</div>
						<div class="manage-doc text-center text-md-left py-4 col-12">
							<a class="m-1 m-md-0 d-inline-block" href="<?php echo e(url('/driver/documents').'/'.$driver_details->id); ?>">
								<button type="button" class="btn btn-theme"><?php echo e(trans('messages.profile.manage_documents')); ?></button>
							</a>
							<a class="m-1 m-md-0 d-inline-block" href="<?php echo e(route('driver.vehicle_details')); ?>">
								<button type="button" class="btn btn-theme"><?php echo e(trans('messages.profile.vehicle_details')); ?></button>
							</a>
						</div>

						<?php echo Form::open(['url'=>route('driver.profile'),'method'=>'post','class'=>'mt-4' , 'id'=>'profile_update_form']); ?>

						<?php echo csrf_field(); ?>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a"><?php echo e(trans('messages.driver.first_name')); ?></label>
							</div>
							<div class="col-md-7">
								<input type="text" name="first_name" placeholder="<?php echo e(trans('messages.driver.first_name')); ?>" value="<?php echo e($driver_details->user->user_first_name); ?>">
								<span class="text-danger"><?php echo e($errors->first('first_name')); ?></span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a"><?php echo e(trans('messages.driver.last_name')); ?></label>
							</div>
							<div class="col-md-7">
								<input type="text" name="last_name" placeholder="<?php echo e(trans('messages.driver.last_name')); ?>" value="<?php echo e($driver_details->user->user_last_name); ?>">
								<span class="text-danger"><?php echo e($errors->first('last_name')); ?></span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a"><?php echo e(trans('messages.profile.email_address')); ?></label>
							</div>
							<div class="col-md-7">
								<input type="text" name="email" placeholder="<?php echo e(trans('messages.profile.email_address')); ?>" value="<?php echo e($driver_details->user->email); ?>">
								<span class="text-danger"><?php echo e($errors->first('email')); ?></span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a"><?php echo e(trans('messages.driver.phone')); ?></label>
							</div>
							<div class="col-md-7">
								<input type="text" name="mobile" placeholder="<?php echo e(trans('messages.profile.phone_number')); ?>" value="<?php echo e($driver_details->user->mobile_number); ?>">
								<span class="text-danger"><?php echo e($errors->first('mobile')); ?></span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label class="required-a"><?php echo e(trans('messages.profile.address')); ?></label>
							</div>
							<div class="col-md-7">
								<input type="text" name="address" placeholder="<?php echo e(trans('messages.profile.address')); ?>" id="driver_address" value="<?php echo e(($driver_details->user_address)?$driver_details->user_address->address:''); ?>">
								<span class="text-danger"><?php echo e($errors->first('address')); ?></span>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label><?php echo e(trans('messages.driver.city')); ?></label>
							</div>
							<div class="col-md-7">
								<input type="text" name="city" placeholder="<?php echo e(trans('messages.driver.city')); ?>" id="city" value="<?php echo e(($driver_details->user_address)?$driver_details->user_address->city:''); ?>">

								<input type="hidden" name="address_line_1" id="address_line_1" value="<?php echo e(($driver_details->user_address)?$driver_details->user_address->street:''); ?>">
								<input type="hidden" name="state" id="state" value="<?php echo e(($driver_details->user_address)?$driver_details->user_address->state:''); ?>">
								<input type="hidden" name="latitude" id="latitude" value="<?php echo e(($driver_details->user_address)?$driver_details->user_address->latitude:''); ?>">
								<input type="hidden" name="longitude" id="longitude" value="<?php echo e(($driver_details->user_address)?$driver_details->user_address->longitude:''); ?>">
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label><?php echo e(trans('messages.profile.country')); ?></label>
							</div>
							<div class="col-md-7">
								<div class="select">
									<select name="country" id="country">

										<?php if($country_code): ?>

										<?php $__currentLoopData = $country_code; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

										<option value="<?php echo e($value->code); ?>" <?php echo e(($driver_details->user_address)?($value->code==$driver_details->user_address->country_code)?'selected':'':''); ?>><?php echo e($value->name); ?></option>

										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php endif; ?>

									</select>
								</div>
							</div>
						</div>
						<div class="form-group d-md-flex">
							<div class="col-md-5">
								<label><?php echo e(trans('messages.profile.postal_code')); ?></label>
							</div>
							<div class="col-md-7">
								<input type="text" name="postal_code" placeholder="<?php echo e(trans('messages.profile.postal_code')); ?>" value="<?php echo e(($driver_details->user_address)?$driver_details->user_address->postal_code:''); ?>">
							</div>
						</div>
						<div class="profile-submit col-12 mt-4 pt-3">
							<button type="submit" class="btn btn-theme"><?php echo e(trans('messages.profile.update')); ?></button>
						</div>
						<?php echo e(Form::close()); ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('driver.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>