@extends('admin/template')
@section('main')
<div class="content" ng-controller="store">
  <div class="container-fluid">
    <div class="col-md-12 p-3 bg-white">
      <div class="card m-0">
        <div class="card-header card-header-rose card-header-text" ng-init="day_name ={{ json_encode(day_name()) }};preparation_timing={{ json_encode($preparation) }};max_time= {{ $max_time }} ? {{ $max_time }}  :  50">
          <h4 class="card-title my-3">{{$form_name}}</h4>
        </div>
      </div>
      <div class="card-body p-0">
        {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal m-0','id'=>'store_preparation_time']) !!}
        @csrf
        <div class="my-3 d-flex align-items-center add-times justify-content-between">
          <input type="text" name="overall_max_time" ng-model="max_time">
          <span class="d-inline-block ml-1">minutes</span>
          <a href="javascript:void(0)" ng-click="default_decrement()">
            <i class="icon icon-remove ml-3"></i>
          </a>
          <a href="javascript:void(0)" ng-click="default_increment()">
            <i class="icon icon-add ml-3"></i>
          </a>
        </div>

        <div class="my-3 d-md-flex align-items-start menu-view added-times-row" ng-repeat="preparation in preparation_timing">
          <div class="d-flex align-items-center add-times">
            <input type="text" name="max_time[]" ng-model="preparation_timing[$index].max_time" readonly>
            <span class="d-inline-block ml-1">minutes</span>
            <a href="javascript:void(0)" ng-click="decrement($index)">
              <i class="icon icon-remove ml-3"></i>
            </a>
            <a href="javascript:void(0)" ng-click="increment($index)">
              <i class="icon icon-add ml-3"></i>
            </a>
          </div>
          <div class="select-day">
            <div class="select ml-md-3">
              <select name="day[]" ng-model="preparation_timing[$index].day" id="select_day_@{{$index}}">
                <option value="">Select a day</option>
                <option value="@{{key}}" ng-selected="preparation.day==key" ng-repeat="(key,value) in day_name track by $index">@{{value}}</option>
              </select>
            </div>
          </div>
          <input type="hidden" name="time_id[]" value="@{{open_time.id}}">

          <div class="added-times d-md-flex ml-3 align-items-start">
            <div class="d-flex align-items-start justify-content-between select-time">
              {!! Form::select('from_time[]',time_data('time'),'', ['ng-model'=>'preparation.from_time', 'id'=>'from_time_@{{$index}}','class'=>'from_time', 'data-index'=>'@{{$index}}','placeholder'=>'Select','data-end_time'=>'@{{preparation.to_time}}']); !!}
              <span class="m-2">to</span>
              {!! Form::select('to_time[]',time_data('time'),'', ['ng-model'=>'preparation.to_time','id'=>'to_time_@{{$index}}','class'=>'to_time ' ,'data-index'=>'@{{$index}}','placeholder'=>'Select']); !!}
            </div>
            <div class="d-flex align-items-start mt-2 mt-md-0 select-status">
              <div class="flex-grow-1 ml-md-3">
                {!! Form::select('status[]',['0'=>'Inactive','1'=>'Active'],'', ['ng-model'=>'preparation.status','id'=>'status@{{$index}}','class'=>'status ' ,'data-index'=>'@{{$index}}','placeholder'=>'Select']); !!}
              </div>
              <i  class="icon icon-rubbish-bin d-inline-block m-2 mr-0 text-danger" ng-click="remove_preparation($index)">
              </i>
            </div>
          </div>
        </div>
        <a href="javascript:void(0)" class="theme-color d-inline-block mb-4" ng-click="add_preparation_time()" ng-show="preparation_timing.length < 7">
          <i class="icon icon-add mr-2"></i>
          ADD MORE
        </a>
        <div class="card-footer">
          <div class="ml-auto">
            <button class="btn btn-fill btn-rose btn-wd" type="submit"  value="timing_save">
              @lang('admin_messages.submit')
            </button>
          </div>
          <div class="clearfix"></div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
  $('#phone_code_country').change(function() {
    $('#apply_country_code').val('');
    if($(this).val())
      $('#apply_country_code').val('+'+$(this).val());
  });
</script>
@endpush