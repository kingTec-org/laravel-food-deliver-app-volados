@extends('admin/template')
@section('main')
<div class="content" ng-controller="store">
  <div class="container-fluid">
    <div class="col-md-12 p-0 px-md-15">
      <div class="card">
        <div class="card-header card-header-rose card-header-text" ng-init="open_time_timing={{json_encode($open_time)}};day_name ={{ json_encode(day_name()) }}">
          <div class="card-text">
            <h4 class="card-title">{{$form_name}}</h4>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'open_time_form']) !!}
          @csrf
          <div class="mb-4 mb-md-3 d-md-flex align-items-start select-day menu-view" ng-repeat="open_time in open_time_timing">
            <div class="select">
              <select  name="day[]" ng-model="open_time.day" id="select_day_@{{$index}}">
                <option value="">Select a day</option>
                <option value="@{{key}}"  ng-selected="open_time.day==key"  ng-repeat="(key,value) in day_name track by $index" ng-if="( key | checkKeyValueUsedInStack : 'day': open_time_timing) || open_time.day==key " >@{{value}}</option>
              </select>
              <span class="text-danger">{{ $errors->first('name') }}</span>
            </div>
            <input type="hidden" name="time_id[]" value="@{{open_time.id}}">
            
            <div class="added-times d-md-flex mt-2 mt-md-0 ml-md-3 align-items-start">
              <div class="d-flex align-items-start justify-content-between select-time">
                <div class="select">
                  {!! Form::select('start_time[]',time_data('time'),'', ['placeholder'=>'select..','ng-model'=>'open_time.orginal_start_time', 'id'=>'start_time_@{{$index}}','class'=>'start_time', 'data-index'=>'@{{$index}}', 'data-end_time'=>'@{{open_time.orginal_end_time}}']); !!}
                  <span class="text-danger">{{ $errors->first('start_time') }}</span>
                </div>
                <div class="m-2">to</div>
                <div class="select">
                  {!! Form::select('end_time[]',time_data('time'),'', ['placeholder'=>'select..','ng-model'=>'open_time.orginal_end_time','id'=>'end_time_@{{$index}}','class'=>'end_time ' ,'data-index'=>'@{{$index}}']); !!}
                  <span class="text-danger">{{ $errors->first('end_time') }}</span>
                </div>
              </div>

              <div class="d-flex align-items-start mt-2 mt-md-0 select-status">
                <div class="select ml-md-3">
                  {!! Form::select('status[]',['1'=>'Active','0'=>'Inactive'],'', ['placeholder'=>'select..','ng-model'=>'open_time.status','id'=>'status_@{{$index}}','class'=>'status ' ,'data-index'=>'@{{$index}}']); !!}
                  <span class="text-danger">{{ $errors->first('status') }}</span>
                </div>
                <i ng-show="open_time_timing.length > 1" class="icon icon icon-rubbish-bin d-inline-block m-2 mr-0 text-danger" ng-click="delete_open_time($index)">
                </i>
              </div>    
            </div>   
          </div> 
          <div class="mt-4">
            <a href="javascript:void(0)" class="theme-color" ng-click="add_open_time()" ng-show="open_time_timing.length < 7">
              <i class="icon icon-add mr-2"></i>
              ADD MORE
            </a>
          </div>
          <div class="card-footer mb-0 p-0">
            <div class="ml-auto">
              <button class="btn btn-fill btn-rose btn-wd" type="submit" value="site_setting">
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