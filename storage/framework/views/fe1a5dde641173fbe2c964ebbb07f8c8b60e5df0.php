<?php $__env->startSection('title', trans('messages.errors.page_not_found')); ?>

<?php $__env->startSection('message', trans('messages.errors.sorry_the_page')); ?>
<?php echo $__env->make('errors::layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>