<?php echo $__env->make('common.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php if(Route::current()->uri() != 'store/login' && Route::current()->uri() != 'store/password' && Route::current()->uri() != 'store/forget_password' && Route::current()->uri() != 'store/mail_confirm' && Route::current()->uri() != 'store/set_password'): ?>
	<?php echo $__env->make('common.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php endif; ?>


<?php echo $__env->yieldContent('main'); ?>

<?php echo $__env->make('common.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->make('common.foot', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>