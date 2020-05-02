<?php $__env->startSection('main'); ?>
<div class="content">
<?php echo Charts::assets(); ?>

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="<?php echo e(route('admin.order')); ?>">
                    <div class="card card-stats">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">add_shopping_cart
                                </i>
                            </div>
                            <p class="card-category"><?php echo app('translator')->getFromJson('admin_messages.total_orders'); ?>
                            </p>
                            <h3 class="card-title"><?php echo e($total_booking); ?>

                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="<?php echo e(route('admin.view_user')); ?>">
                    <div class="card card-stats">
                        <div class="card-header card-header-rose card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">group
                                </i>
                            </div>
                            <p class="card-category"><?php echo app('translator')->getFromJson('admin_messages.total_user'); ?>
                            </p>
                            <h3 class="card-title"><?php echo e($total_users); ?>

                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">

                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="<?php echo e(route('admin.view_store')); ?>">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">store
                                </i>
                            </div>
                            <p class="card-category"><?php echo app('translator')->getFromJson('admin_messages.total_stores'); ?>
                            </p>
                            <h3 class="card-title"><?php echo e($total_stores); ?>

                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">

                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <a href="<?php echo e(route('admin.view_driver')); ?>">
                    <div class="card card-stats">
                        <div class="card-header card-header-info card-header-icon">
                            <div class="card-icon">
                                <i class="material-icons">local_taxi
                                </i>
                            </div>
                            <p class="card-category"><?php echo app('translator')->getFromJson('admin_messages.total_driver'); ?>
                            </p>
                            <h3 class="card-title"><?php echo e($total_drivers); ?>

                            </h3>
                        </div>
                        <div class="card-footer">
                            <div class="stats">

                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <br>


        <div class="row">
            <div class="col-md-12">
            <center>
                 <?php echo $earning_chart->render(); ?>

                 </center>

            </div>






        </div>
    </div>
</div>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin/template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>