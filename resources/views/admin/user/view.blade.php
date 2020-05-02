@extends('admin/template')
@section('main')
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
              <br>
              <button style="margin-bottom: 5px;" type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
		<div class="card">

      <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">{{$form_name}}</h4>
                  </div>
                </div>
             

			<div class="col-md-12 text-right mt-3">
				<a class="btn btn-success" href="{{route('admin.add_user')}}" > @lang('admin_messages.add_user') </a>
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
@endsection

@push('scripts')
<link rel="stylesheet" href="{{asset('admin_assets/css/buttons.dataTables.css')}}">
<script src="{{asset('admin_assets/js/dataTables.buttons.js')}}">
</script>
<script src={{url('vendor/datatables/buttons.server-side.js')}}></script>

<script>
  var oTable = $('#statement_table').DataTable({
    dom:"lBfrtip",
    buttons:["csv","excel","print"],
    order:[0, 'desc'],
    processing: true,
    serverSide: true,

    ajax: {
      url: ajax_url_list['all_users'],
      data: function (d) {
        d.from_dates = $('#from_date').val();
        d.to_dates = $('#to_date').val();
      }
    },
    columns: [
    {data: 'id', name: 'id', title: 'ID'},
    {data: 'first_name',name: 'first_name',title: 'First Name'},
    {data: 'last_name',name: 'last_name',title: 'Last Name'},
    {data: 'email',name: 'email',title: 'email'},
    {data: 'status',name: 'status',title: 'Status'},
    {data: 'created_at',name: 'created_at',title: 'created_at'},
    {data: 'action',name: 'action',title: 'Action',orderable: false,searchable: false}
    ]
  });

 	$('#custom_statement').on('submit', function(e) {
      oTable.draw();
      e.preventDefault();
    });
</script>
@endpush