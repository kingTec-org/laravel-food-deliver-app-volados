@extends('admin/template')
@section('main')

<div class="content" ng-controller="statements" ng-cloak>
	<div class="container-fluid">
		    <form method="POST" id="custom_statement" class="from_search_admin form-inline d-block d-md-flex align-items-end justify-content-end" role="form">
            <div class="form-group">
              <label class="d-inline-block mr-3" for="name">Filter By</label><br>
              <select ng-change="filter_by_change()"  ng-init="filter_by ='overall'" class="form-control" name="filter_by" ng-model="filter_by"  id="filter_by">
                <option value="overall">Overall</option>
                <option value="daily">Today</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="yearly">Yearly</option>
                <option value="custom">Custom</option>
              </select>
            </div>
            <div class="form-group ml-md-5" ng-if="filter_by=='custom'">
              <label class="d-inline-block mr-3" for="email">From Date</label><br>
              <input type="text" class="datepickerfrom form-control date" name="from_date" id="from_date" placeholder="From Date">
            </div>
            <div class="form-group ml-md-5" ng-if="filter_by=='custom'">
              <label class="d-inline-block mr-3" for="email">To Date</label><br>
              <input type="text" class="datepickerto form-control date" name="to_date" id="to_date" placeholder="To Date">
            </div>
            <div class="form-group ml-md-5">
              <br>
              <button style="margin-bottom: 5px;" type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
      <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <div class="card card-stats">
                    <div class="card-header card-header-warning card-header-icon">
                        <div class="card-icon">
                        @if(get_current_login_user()=='admin')
                            <i class="material-icons">shopping_cart </i>
                        @else
                            <i class="material-icons">monetization_on </i>
                        @endif
                        </div>
                        <p class="card-category">
                        @if(get_current_login_user()=='admin')
                        @lang('admin_messages.pending_order')
                        @else
                        @lang('admin_messages.tax')
                        @endif
                        </p>
                        <h3 class="card-title counts-info">@{{pending_order}}
                        </h3>
                    </div>
                    <div class="card-footer ">
                       <!--  <div class="stats">
                            <i class="material-icons text-danger">warning
                            </i>
                            <a href="#pablo">Get More Space...
                            </a>
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <div class="card card-stats">
                    <div class="card-header card-header-rose card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">attach_money
                            </i>
                        </div>
                        <p class="card-category">
                        @if(get_current_login_user()=='admin')
                        @lang('admin_messages.payments')
                        @else
                        @lang('admin_messages.earings')
                        @endif

                        </p>
                        <h3 class="card-title counts-info">@{{total_earning}}
                        </h3>
                    </div>
                    <div class="card-footer">
                        <!-- <div class="stats">
                            <i class="material-icons">local_offer
                            </i> Tracked from Google Analytics
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <div class="card card-stats">
                    <div class="card-header card-header-rose card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">monetization_on
                            </i>
                        </div>
                        <p class="card-category">
                        @if(get_current_login_user()=='admin')
                        @lang('admin_messages.commission_fee')
                        @else
                        @lang('admin_messages.owe_amount')
                        @endif
                        </p>
                        <h3 class="card-title counts-info">@{{total_service_fee}}
                        </h3>
                    </div>
                    <div class="card-footer">
                        <!-- <div class="stats">
                            <i class="material-icons">local_offer
                            </i> Tracked from Google Analytics
                        </div> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <div class="card card-stats">
                    <div class="card-header card-header-rose card-header-icon">
                        <div class="card-icon">
                            <i class="material-icons">shopping_cart
                            </i>
                        </div>
                        <p class="card-category">@lang('admin_messages.orders')
                        </p>
                        <h3 class="card-title counts-info">@{{total_order}}
                        </h3>
                    </div>
                    <div class="card-footer">
                        <!-- <div class="stats">
                            <i class="material-icons">local_offer
                            </i> Tracked from Google Analytics
                        </div> -->
                    </div>
                </div>
            </div>
      </div>
      <!-- <p> @{{count_text}}</p> -->
		<div class="card">

<div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">{{$form_name}}</h4>
                  </div>
                </div>
                  <div class="card-body ">
			<div class="table-responsive">
				<table id="statement_table" class="table table-condensed w-100">
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

    var column = [
    {data: 'id', name: 'id', title: '{{trans("admin_messages.order_id")}}' },
    {data: 'payment_type',name: 'payment_type',title: '{{trans("admin_messages.payment_type")}}'},
    {data: 'user_name',name: 'user_name',title: '{{trans("admin_messages.user_name")}}'},
    {data: 'store_name',name: 'store_name',title: '{{trans("admin_messages.store_name")}}'},
    {data: 'tax',name: 'tax',title: '{{trans("admin_messages.tax")}}'},
    {data: 'total',name: 'total',title: '{{trans("admin_messages.total")}}'},
    {data: 'status_text',name: 'status_text',title: '{{trans("admin_messages.order_status")}}'},
    {data: 'action',name: 'action',title: '{{trans("admin_messages.action")}}',orderable: false,searchable: false}
    ];

  var oTable = $('#statement_table').DataTable({
    dom:"lBfrtip",
    buttons:["csv","excel","print"],
    order:[0, 'desc'],
    processing: true,
    serverSide: true,

    ajax: {
      url: ajax_url_list['all_orders'],
      data: function (d) {
        d.filter_type = $('#filter_by').val();
        d.from_dates = $('#from_date').val();
        d.to_dates = $('#to_date').val();
      }
    },
    columns: column
  });
  $(document).ready(function(){


  })


  app.controller('statements', ['$scope', '$http', function($scope, $http) {

    $('#custom_statement').on('submit', function(e) {
      oTable.draw();
      $scope.count_section();
      e.preventDefault();
    });
    $scope.count_text="Overall Statement";
    $scope.count_section=function()
    {
      $http.post(ajax_url_list['sort_order'], {
       from_dates: $('#from_date').val(),
       to_dates: $('#to_date').val(),
       filter_type: $('#filter_by').val()
   }).then(function( response ) {
        $scope.count_text=response.data.count_text;
        $scope.total_earning=response.data.total_earning;
        $scope.total_service_fee=response.data.total_service_fee;
        $scope.total_order=response.data.total_order;
        $scope.pending_order=response.data.pending_order;
      })
    }
    $scope.count_section();

    $scope.filter_by_change = function(){

        if($scope.filter_by=='custom'){
              setTimeout(function(){
                 md.initFormExtendedDatetimepickers();
             },100);
         }
    }

  }]);
</script>
@endpush