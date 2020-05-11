<?php echo $__env->make('common.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php if(Route::current()->uri() != 'login' && Route::current()->uri() != 'forgot_password' && Route::current()->uri() != 'signup' && Route::current()->uri() != 'signup_confirm' && Route::current()->uri() != 'otp_confirm' && Route::current()->uri() != 'reset_password'): ?>
	<?php echo $__env->make('common.header2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>

<?php echo $__env->yieldContent('main'); ?>

<?php echo $__env->make('common.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->make('common.foot', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>