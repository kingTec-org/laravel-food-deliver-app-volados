@extends('admin/template')
@section('main')
<div class="content">
	<div class="container-fluid">
		<div class="card">
			 <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">{{$form_name}}</h4>
                  </div>
                </div>
			<div style="float: right;text-align: right;" class="col-md-12">
				<a class="btn btn-success" href="{{route('admin.add_promo')}}" > @lang('admin_messages.add_promo') </a>
			</div>
			<div class="card-body ">
			<div class="table-responsive">
				{!! $dataTable->table() !!}
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
{!! $dataTable->scripts() !!}

@endpush