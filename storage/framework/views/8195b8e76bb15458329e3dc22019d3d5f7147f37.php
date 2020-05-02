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
			<form name="signup2" method="POST" action="<?php echo e(route('store_signup_data')); ?>" name="signup_confirm">
				<?php echo csrf_field(); ?>
				<div class="form-group">
					<label><?php echo e(trans('messages.driver.enter_the_digit_code_sent_to_you_at')); ?> <?php echo e($phone_number); ?></label>
					<!-- for live only start -->
					<input type="text" value="" name="code_confirm" id="code_confirm" placeholder=""/>
					<!-- for live only end -->
					<p id='code_check' style="display: none;color: red"><?php echo e(trans('messages.store_dashboard.code_is_incorrect')); ?></p>
					<input type="hidden" name="code_session" id="code_session" value="">


				</div>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" id="code_confirm_submit" type="submit"><?php echo e(trans('messages.profile.next_button')); ?> <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>