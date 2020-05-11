<?php $__env->startSection('title', trans('installer_messages.settings.title')); ?>
<?php $__env->startSection('container'); ?>
<?php echo Form::open(['url'=>route('LaravelInstaller::database'),'method'=>'post']); ?>

<ul class="list">
    <li class="list__item list__item--settings">
        Site Name<em class="error">*</em>
        <?php echo Form::text('site_name'); ?>

    </li>
    <li class="list__item list__item--settings">
        Admin Username<em class="error">*</em>
        <?php echo Form::text('username'); ?>

    </li>
    <li class="list__item list__item--settings">
        Admin Email<em class="error">*</em>
        <?php echo Form::text('email'); ?>

    </li>
    <li class="list__item list__item--settings">
        Admin Password<em class="error">*</em>
        <?php echo Form::text('password'); ?>

    </li>
</ul>
<div class="buttons">
    <button class="button button-classic">
        <?php echo e(trans('installer_messages.settings.install')); ?>

        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
    </button>
</div>
<?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('vendor.installer.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>