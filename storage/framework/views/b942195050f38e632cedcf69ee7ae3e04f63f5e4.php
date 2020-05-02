<?php $__env->startSection('main'); ?>
<div class="flash-container">
	<?php if(Session::has('message')): ?>
	<div class="alert <?php echo e(Session::get('alert-class')); ?> text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> <?php echo e(Session::get('message')); ?>

	</div>
	<?php endif; ?>
</div>
<main id="site-content" role="main" class="log-user">
	<div class="container">
		<div class="logo text-center mt-5">
			<a href="<?php echo e(url('/')); ?>">
				<img src="<?php echo e(site_setting('1','1')); ?>"" width="130" height="">
			</a>
		</div>
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1><?php echo e(trans('messages.profile.sign_in')); ?></h1>
			<form method="POST" action="<?php echo e(route('authenticate')); ?>">
				<?php echo csrf_field(); ?>
				<div class="form-group col-12">
					<div class="row">
						<label><?php echo e(trans('messages.profile.enter_your_phone_number')); ?> <span>(<?php echo e(trans('messages.profile.required')); ?>)</span></label>
						<div class="d-flex w-100">
							<div class="select mob-select col-md-3">
								<span class="phone_code">+1</span>
								<select id="phone_code" name="country" class="form-control">
									<?php $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($country->phone_code); ?>" <?php echo e($country->phone_code == 1 ? 'selected' : ''); ?> ><?php echo e($country->name); ?></option>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								</select>
							</div>
							<?php echo Form::text('phone_number','',['placeholder' => trans('messages.profile.phone_number'),'class' =>'flex-grow-1','data-error-placement'=>'container','data-error-container'=>'.mobile-number-error']); ?>

						</div>
						<span class="mobile-number-error text-danger"><?php echo e($errors->first('phone_number')); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label><?php echo e(trans('messages.profile.enter_your_password')); ?><span>(<?php echo e(trans('messages.profile.required')); ?>)</span></label>
					<input type="password" value="" name="password" id="password" placeholder=""/>
					<span class="text-danger"> <?php echo e($errors->first('password')); ?> </span>
				</div>
				<div class="forget_link">
					<a href="<?php echo e(route('forgot_password')); ?>"><?php echo e(trans('messages.profile.forget_password')); ?><span class="qust"><?php echo e(trans('messages.store.ques_mark')); ?></span> </a>
					<a href="<?php echo e(route('help_page',current_page())); ?>"><?php echo e(trans('messages.profile.get_help')); ?></a>
				</div>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit"><?php echo e(trans('messages.profile.next_button')); ?> <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>