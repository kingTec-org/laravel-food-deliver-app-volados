<?php $__env->startSection('main'); ?>
<div class="flash-container">
	<?php if(Session::has('message')): ?>
	<div class="alert <?php echo e(Session::get('alert-class')); ?> text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> <?php echo e(Session::get('message')); ?>

	</div>
	<?php endif; ?>
</div>
<main id="site-content" role="main" class="log-user" ng-controller="home_page">
	<div class="container">
		<div class="logo text-center mt-5">
			<a href="<?php echo e(url('/')); ?>">
				<img src="<?php echo e(site_setting('1','1')); ?>" width="120" height="">
			</a>
		</div>
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1><?php echo e(trans('messages.profile.create_an_account')); ?></h1>
			<form name="signup2" id='eater_signup_form' class="form-horizontal">
				<div class="form-group">
					<label><?php echo e(trans('messages.profile.enter_first_name')); ?><span>(<?php echo e(trans('messages.profile.required')); ?>)</span></label>
					<input type="text" name="first_name" id="first_name" placeholder=""/>
				</div>
				<div class="form-group">
					<label><?php echo e(trans('messages.profile.enter_last_name')); ?> <span>(<?php echo e(trans('messages.profile.required')); ?>)</span></label>
					<input type="text" name="last_name" id="last_name" placeholder=""/>
				</div>
				<div class="form-group">
					<label><?php echo e(trans('messages.profile.enter_your_phone_number')); ?> <span>(<?php echo e(trans('messages.profile.required')); ?>)</span></label>
					<div class="d-flex w-100">
						<div class="select mob-select col-md-3">
							<span class="phone_code">+<?php echo e(@session::get('phone_code')); ?></span>


							<select id="phone_code" name="country_code" class="form-control">
						                    <?php $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						                        <option value="<?php echo e($country->phone_code); ?>" <?php echo e($country->phone_code == @session::get('phone_code') ? 'selected' : ''); ?> ><?php echo e($country->name); ?></option>
						                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						                </select>

						</div>
						<input type="number" name="phone_number" id="phone_number" data-error-placement="container" data-error-container=".phone_error" placeholder=""/>
					</div>
				</div>
				<p class="phone_error text-danger">  </p>
				<div class="form-group">
					<label><?php echo e(trans('messages.profile.enter_your_email_address')); ?> <span>(<?php echo e(trans('messages.profile.required')); ?>)</span></label>
					<input type="text" name="email_address" id="email_address" placeholder=""/>
					<p id="email_address_error" style="color: red;display: none">Invalid email address</p>
				</div>
				<div class="form-group">
					<label><?php echo e(trans('messages.profile.password')); ?> <span>(<?php echo e(trans('messages.profile.required')); ?>)</span></label>
					<input type="password" name="password" id="password" placeholder=""/>
				</div>
				<p class="required_error" style="color: red; display: none"><?php echo e(trans('messages.profile.invalid_email_address')); ?></p>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" id="signup_form_submit" type="submit"><?php echo e(trans('messages.profile.next_button')); ?> <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>