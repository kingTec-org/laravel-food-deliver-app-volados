<?php $__env->startSection('main'); ?>
<div class="content">
	<div class="container-fluid">
		<form method="POST" id="custom_statement" class="from_search_admin form-inline d-block d-md-flex align-items-end justify-content-end" role="form">
            <div class="form-group">
              <label class="mr-3" for="email">From Date</label><br>
              <input type="text" class="datepickerfrom form-control date" name="from_date" id="from_date" placeholder="From Date">
            </div>
            <div class="form-group ml-md-3">
              <label class="mr-3" for="email">To Date</label><br>
              <input type="text" class="datepickerto form-control date" name="to_date" id="to_date" placeholder="To Date">
            </div>
            <div class="form-group ml-md-3">

              <button style="margin-bottom: 5px;" type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
		<div class="card">
			  <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title"><?php echo e($form_name); ?></h4>
                  </div>
                </div>
     <div style="float: right;text-align: right;" class="col-md-12">
				<a class="btn btn-success" href="<?php echo e(route('admin.add_store')); ?>" > <?php echo app('translator')->getFromJson('admin_messages.add_store'); ?> </a>
			</div>
      <div class="card-body ">
			<div class="table-responsive">
				<table id="statement_table" class="table table-condensed">
                </table>
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

<script>
  var oTable = $('#statement_table').DataTable({
    dom:"lBfrtip",
    buttons:["csv","excel","print"],
    order:[0, 'desc'],
    processing: true,
    serverSide: true,

    ajax: {
      url: ajax_url_list['all_stores'],
      data: function (d) {
        d.from_dates = $('#from_date').val();
        d.to_dates = $('#to_date').val();
      }
    },
    columns: [
    {data: 'id', name: 'id', title: 'ID'},
    {data: 'name',name: 'name',title: 'Name'},
    {data: 'store_name',name: 'store_name',title: 'Store Name'},
    {data: 'email',name: 'email',title: 'Email'},
    {data: 'user_status',name: 'user_status',title: 'User Status'},
    {data: 'store_status',name: 'store_status',title: 'Store Status'},
    {data: 'recommend',name: 'recommend',title: 'Recommend'},
    {data: 'action',name: 'action',title: 'Action',orderable: false,searchable: false}
    ]
  });

 	$('#custom_statement').on('submit', function(e) {
      oTable.draw();
      e.preventDefault();
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin/template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>