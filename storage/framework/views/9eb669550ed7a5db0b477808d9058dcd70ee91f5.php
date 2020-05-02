<?php $__env->startSection('main'); ?>
<div class="flash-container">
      <?php if(Session::has('message')): ?>
          <div class="alert <?php echo e(Session::get('alert-class')); ?> text-center" role="alert">
              <a href="#" class="alert-close" data-dismiss="alert">&times;</a> <?php echo e(Session::get('message')); ?>

          </div>
      <?php endif; ?>
  </div>
<main id="site-content" role="main" class="log-user driver">
	<div class="container">
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1><?php echo e(trans('messages.profile.sign_in')); ?></h1>
			<?php echo Form::open(['url'=>route('driver.login'),'method'=>'post','class'=>'mt-4' , 'id'=>'login_form']); ?>

			<?php echo csrf_field(); ?>




					<div class="form-group col-12">
							<div class="row">
							<label><?php echo e(trans('messages.profile.enter_your_phone_number')); ?><span> (<?php echo e(trans('messages.profile.required')); ?>)</span></label>
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


				<button class="btn btn-arrow btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit"><?php echo e(trans('messages.profile.next_button')); ?></button>
				<div class="mt-4">
					<p><?php echo e(trans('messages.driver.dont_have_account')); ?>?
						<a href="<?php echo e(route('driver.signup')); ?>" class="theme-color"><?php echo e(trans('messages.driver.sign_up')); ?></a>
					</p>
				</div>
			<?php echo e(Form::close()); ?>

		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('driver.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>