@extends('admin/template')
@section('main')
<div class="content">
  <div class="container-fluid">
    <div class="card">
      <div style="float: left" class="col-md-3">
      </div>
      <div class="table-responsive">
        {!! $dataTable->table() !!}
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