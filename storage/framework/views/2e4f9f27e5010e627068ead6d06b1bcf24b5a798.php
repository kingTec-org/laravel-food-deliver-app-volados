<?php echo $__env->make('common.head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->make('common.driver.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->yieldContent('main'); ?>

<?php echo $__env->make('common.driver.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->make('common.foot', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>