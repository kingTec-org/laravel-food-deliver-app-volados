<?php $__env->startSection('main'); ?>
<div class="content">
	<div class="container-fluid">
		<div class="card">
				 <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title"><?php echo e($form_name); ?></h4>
                  </div>
                </div>
     <div style="float: right;text-align: right;" class="col-md-12">
				<a class="btn btn-success" href="<?php echo e(route('admin.add_category')); ?>" > <?php echo app('translator')->getFromJson('admin_messages.add_category'); ?> </a>
			</div>
			<div class="card-body ">
			<div class="table-responsive">
				<?php echo $dataTable->table(); ?>

			</div>
		</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin_assets/css/buttons.dataTables.css')); ?>">
<script src="<?php echo e(asset('admin_assets/js/dataTables.buttons.js')); ?>">
</script>
<script src=<?php echo e(url('vendor/datatables/buttons.server-side.js')); ?>></script>
<?php echo $dataTable->scripts(); ?>


<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin/template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>