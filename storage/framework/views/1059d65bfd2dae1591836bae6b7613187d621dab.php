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
			<a href="<?php echo e(url('store')); ?>">
				<img src="<?php echo e(site_setting('store_logo','3')); ?>" width="120" height="">
			</a>
		</div>
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h1><?php echo e(trans('messages.profile.sign_in')); ?></h1>
			<?php echo Form::open(['url'=>route('store.login'),'method'=>'post','class'=>'mt-4' , 'id'=>'login_form']); ?>

			<?php echo csrf_field(); ?>
			<div class="form-group" ng-init="textInputValue=''">
				<label><?php echo e(trans('messages.store.Enter your email')); ?></label>

				<?php echo Form::text('textInputValue','',['placeholder' => trans('messages.store.email'),'ng-model' => 'textInputValue']); ?>

				<span class="text-danger"><?php echo e($errors->first('textInputValue')); ?></span>

			</div>
			<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit"><?php echo e(trans('messages.profile.next_button')); ?> <i class="icon icon-right-arrow"></i></button>
			<?php echo Form::close(); ?>

		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>