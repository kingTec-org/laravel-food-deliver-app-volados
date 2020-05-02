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
			<?php echo Form::open(['url'=>route('driver.password'),'method'=>'post','class'=>'mt-4' , 'id'=>'login_form1']); ?>

			<?php echo csrf_field(); ?>
				<div class="form-group">
					<label><?php echo e(trans('messages.profile.password')); ?></label>
					<input type="password" name="password" value="" placeholder="<?php echo e(trans('messages.profile.password')); ?>"/>
					<span class="text-danger"><?php echo e($errors->first('password')); ?></span>
				</div>
				<button class="btn btn-arrow btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit"><?php echo e(trans('messages.profile.next_button')); ?></button>
				<div class="mt-4">
					<p>
						<a href="<?php echo e(route('driver.forgot_password')); ?>" class="theme-color"><?php echo e(trans('messages.profile.forget_password')); ?><span class="qust"><?php echo e(trans('messages.store.ques_mark')); ?></span> </a>
						<a href="<?php echo e(route('help_page',current_page())); ?>" class="theme-color"><?php echo e(trans('messages.profile.get_help')); ?></a>
					</p>
				</div>
			<?php echo e(Form::close()); ?>

		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('driver.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>