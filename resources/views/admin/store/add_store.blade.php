@extends('admin/template')
@section('main')
<div class="content" ng-controller="store">
  <div class="container-fluid">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-rose card-header-text">
          <div class="card-text">
            <h4 class="card-title">{{$form_name}}</h4>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'add_user_form','files'=>'true']) !!}
          @csrf
          <div class="row">
            <label class="col-sm-2 col-form-label">@lang('admin_messages.full_name')<span class="required text-danger">*</span></label>
            <div class="col-sm-10">
              <div class="form-group">
               {!! Form::text('name',@$store->name, ['class' => 'form-control', 'id' => 'input_user_name',]) !!}
               <span class="text-danger">{{ $errors->first('name') }}</span>
             </div>
           </div>
         </div>
         <div class="row">
          <label class="col-sm-2 col-form-label">@lang('admin_messages.email')<span class="required text-danger">*</span></label>
          <div class="col-sm-10">
            <div class="form-group">
             {!! Form::text('email',@$store->email, ['class' => 'form-control', 'id' => 'input_email',]) !!}
             <span class="text-danger">{{ $errors->first('email') }}</span>
           </div>
         </div>
       </div>
       <div class="row">
        <label class="col-sm-2 col-form-label">@lang('admin_messages.password')
          @if(@$store->email=='')
          <span class="required text-danger">*</span>
          @endif
        </label>
        <div class="col-sm-10">
          <div class="form-group">
            {!! Form::text('password','', ['class' => 'form-control', 'id' => 'input_password',]) !!}
            <span class="text-danger">{{ $errors->first('password') }}</span>
          </div>
        </div>
      </div>

      <div class="row">
        <label class="col-sm-2 col-form-label">@lang('admin_messages.phone_no')<span class="required text-danger">*</span></label>
        <div class="col-sm-2">
          <div class="form-group">
            @php
              $country_code=(request()->old('phone_country_code'))?request()->old('phone_country_code'):@$store->country_code;
            @endphp
              <select id="phone_code_country" name="phone_country_code" class="form-control">
                                @foreach ($country as $key => $country)
                                    <option value="{{ $country->phone_code }}" {{ $country->phone_code == $country_code ? 'selected' : '' }} >{{ $country->name }}</option>
                                @endforeach
                            </select>
           <span class="text-danger">{{ $errors->first('phone_country_code') }}</span>
         </div>
       </div>
       <div class="col-sm-2">
        <div class="form-group">
         {!! Form::text('text',@$store->country_code?'+'.$store->country_code:'', ['readonly'=>'readonly','class'=>'form-control','id'=>'apply_country_code']); !!}
       </div>
     </div>
     <div class="col-sm-6">
      <div class="form-group">

       {!! Form::text('mobile_number',@$store->mobile_number, ['class' => 'form-control', 'id' =>'input_mobile_number','placeholder'=>trans('admin_messages.phone_no')]) !!}
       <span class="text-danger">{{ $errors->first('mobile_number') }}</span>
     </div>
   </div>
 </div>
 <div class="row">
  <label class="col-sm-2 col-form-label">@lang('admin_messages.date_of_birth')<span class="required text-danger">*</span></label>
  <div class="col-sm-4">
    <div class="form-group">
      {!! Form::text('date_of_birth',set_date_on_picker(@$store->date_of_birth), ['class' => 'form-control datepickerdob', 'id' => 'input_password',]) !!}
      <span class="text-danger">{{ $errors->first('convert_dob') }}</span>
    </div>
  </div>
</div>

<div class="row">
  <label class="col-sm-2 col-form-label">User status<span class="required text-danger">*</span></label>
  <div class="col-sm-4">
    <div class="form-group">
      {!! Form::select('user_status',['1'=>trans('admin_messages.active'),'0'=>trans('admin_messages.inactive'),'4'=>trans('admin_messages.pending'),'5'=>trans('admin_messages.waiting_for_approval')],@$store->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
      <span class="text-danger">{{ $errors->first('user_status') }}</span>
    </div>
  </div>
</div>
<div class="row">
  <label class="col-sm-2 col-form-label">@lang('admin_messages.store_name')<span class="required text-danger">*</span></label>
  <div class="col-sm-10">
    <div class="form-group">
     {!! Form::text('store_name',@$store->store->name, ['class' => 'form-control', 'id' => 'input_store_name',]) !!}
     <span class="text-danger">{{ $errors->first('store_name') }}</span>
   </div>
 </div>
</div>
<div class="row">
  <label class="col-sm-2 col-form-label">@lang('admin_messages.store_description')<span class="required text-danger">*</span></label>
  <div class="col-sm-10">
    <div class="form-group">
     {!! Form::text('store_description',@$store->store->description, ['class' => 'form-control', 'id' => 'input_store_description',]) !!}
     <span class="text-danger">{{ $errors->first('store_description') }}</span>
   </div>
 </div>
</div>
{{-- <div class="row">
<label class="col-sm-2 col-form-label">@lang('admin_messages.min_time')<span class="required text-danger">*</span></label>
<div class="col-sm-10">
  <div class="form-group">
   {!! Form::select('min_time',time_data('time'),@$store->store->min_time, ['id'=>'phone_code_country','placeholder' => trans('admin_messages.select'),'class'=>'form-control']) !!}
   <span class="text-danger">{{ $errors->first('min_time') }}</span>
 </div>
</div>
</div>
<div class="row">
  <label class="col-sm-2 col-form-label">@lang('admin_messages.max_time')<span class="required text-danger">*</span></label>
  <div class="col-sm-10">
    <div class="form-group">
     {!! Form::select('max_time',time_data('time'),@$store->store->max_time, ['id'=>'phone_code_country','placeholder' => trans('admin_messages.select'),'class'=>'form-control']) !!}
     <span class="text-danger">{{ $errors->first('max_time') }}</span>
   </div>
 </div>
</div>--}}


<div class="row" ng-init="
  country='{{ @$store->user_address->country }}';
  postal_code='{{(request()->old('postal_code'))?request()->old('postal_code'):@$store->user_address->postal_code}}';
  city='{{ (request()->old('city'))?request()->old('city'):@$store->user_address->city}}';
  state='{{ (request()->old('state'))?request()->old('state'):@$store->user_address->state}}';
  address_line_1='{{ (request()->old('street'))?request()->old('street'):@$store->user_address->street}}';
  latitude='{{(request()->old('latitude'))?request()->old('latitude'):@$store->user_address->latitude}}';
  longitude='{{(request()->old('longitude'))?request()->old('longitude'):@$store->user_address->longitude}}';
  country_code='{{(request()->old('country_code'))?request()->old('country_code'):@$store->user_address->country_code}}';">
  <label class="col-sm-2 col-form-label">@lang('admin_messages.address')<span class="required text-danger">*</span></label>

  <div class="col-sm-10">
    <div class="form-group">
     {!! Form::text('address',@$store->user_address->address,['id'=>'location_val','class'=>'form-control'])!!}
     <span class="text-danger">{{ $errors->first('address') }}</span>

   </div>
 </div>
</div>
<div class="d-none">
  {!! Form::text('country_code','',['value'=>'','id'=>'addresss_country_code','ng-model'=>'country_code'])!!}
  {!! Form::text('postal_code','',['value'=>'','id'=>'addresss_postal_code','ng-model'=>'postal_code'])!!}
  {!! Form::text('city','',['value'=>'','id'=>'addresss_city','ng-model'=>'city'])!!}
  {!! Form::text('state','',['value'=>'','id'=>'addresss_state','ng-model'=>'state'])!!}
  {!! Form::text('street','',['value'=>'','id'=>'addresss_address_line_1','ng-model'=>'address_line_1'])!!}
  {!! Form::text('latitude','',['value'=>'','id'=>'addresss_latitude','ng-model'=>'latitude'])!!}
  {!! Form::text('longitude','',['value'=>'','id'=>'addresss_longitude','ng-model'=>'longitude'])!!}
</div>
<div class="row">
  <label class="col-md-2 col-form-label">
   @lang('admin_messages.banner_image')
   <span class="required text-danger">*</span>
 </label>
 <div class="col-md-5 pt-md-4">
  <div class="fileinput fileinput-new" data-provides="fileinput">
    <div class="fileinput-new thumbnail">
      <img src="@if(isset($store->store->store_image)){{$store->store->store_image}}@else{{getEmptyStoreImage()}}@endif" alt="...">
    </div>
    <div class="fileinput-preview fileinput-exists thumbnail"></div>
    <div>
      <span class="btn btn-rose btn-round btn-file">
        <span class="fileinput-new">@lang('admin_messages.select_image')</span>
        <span class="fileinput-exists">@lang('admin_messages.change')</span>
        {!! Form::file('banner_image',['class' => 'form-control', 'id' => 'input_banner_image']) !!}
      </span>
      <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> @lang('admin_messages.remove')</a>
    </div>
    <span class="text-danger">{{ $errors->first('banner_image') }}</span>
  </div>
</div>
</div>

<div class="row">
  <label class="col-sm-2 col-form-label">@lang('admin_messages.price_rating')<span class="required text-danger">*</span></label>
  <div class="col-sm-4">
    <div class="form-group">
      {!! Form::select('price_rating',priceRatingList(),@$store->store->price_rating, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
      <span class="text-danger">{{ $errors->first('price_rating') }}</span>
    </div>
  </div>
</div>
<div class="row">
  <label class="col-md-2 col-form-label">@lang('admin_messages.category')<span class="required text-danger">*</span></label>
  <div class="col-md-9">
    <div class="form-group form-check-group category row">
      @foreach($category as $category_key => $category_value)
      <div class="form-check col-md-6">
        <label class="form-check-label">
         {!! Form::checkbox('category[]',$category_key,in_array($category_key,$store_category), ['class'=>'form-check-input','data-error-placement'=>"container" ,'data-error-container'=>".category_error"]); !!}
         <span class="form-check-sign">
          <span class="check"></span>
        </span>
        {{$category_value}}
      </label>
    </div>

    @endforeach

    <span class="text-danger">{{ $errors->first('category') }}</span>
  </div>
  <span class="category_error"> </span>
</div>
</div>
<div class="row">
  <label class="col-sm-2 col-form-label">@lang('admin_messages.store_status')<span class="required text-danger">*</span></label>
  <div class="col-sm-4">
    <div class="form-group">
      {!! Form::select('store_status',['1'=>trans('admin_messages.available'),'0'=>trans('admin_messages.unavailable')],@$store->store->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
      <span class="text-danger">{{ $errors->first('store_status') }}</span>
    </div>
  </div>
</div>
<div ng-init="default_img='{{$default_img}}';all_document={{old('document')?json_encode(old('document')):json_encode(@$store_document?:array(0))}};errors = {{json_encode($errors->getMessages())}}">
  <h4 class="my-3 px-md-3 my-md-4 text-left">@lang('admin_messages.documents')</h4>
</div>

<div ng-repeat="document in all_document" ng-cloak>
  <p ng-show="all_document.length > 1" style="float: right">
    <a href="javascript:void(0)" ng-click="delete_document($index)">
      <i class="material-icons btn-red">delete</i>
    </a>
  </p>
  <div class="row">
    <label class="col-md-3 col-form-label">@lang('admin_messages.document_name')<span class="required">*</span></label>
    <div class="col-md-4">
      <div class="form-group">
        <input type="hidden" name="document[@{{$index}}][id]" ng-value="document.id" class="form-control" id="document_id">
        <input type="text" name="document[@{{$index}}][name]" ng-model="document.name" class="form-control" id="name">
        <span class="text-danger">@{{ errors['document.'+$index+'.name'][0] }}</span>
      </div>
    </div>
  </div>
  <div class="row">
    <label class="col-md-3 col-form-label">@lang('admin_messages.document_image')<span class="required">*</span></label>
    <div class="col-md-9 pt-md-4">
      <div class="fileinput fileinput-new" data-provides="fileinput">
        <div class="fileinput-new thumbnail">
         <img ng-if="document.file.file_extension!='pdf'" src="@{{document.document_file?document.document_file:(document.document_old_file?document.document_old_file:default_img)}}" alt="...">
         <a ng-if="document.file.file_extension=='pdf'"  href="@{{document.document_file?document.document_file:(document.document_old_file?document.document_old_file:default_img)}}" alt="...">
         @{{document.file.name}}
         </a>

       </div>
       <div class="fileinput-preview fileinput-exists thumbnail"></div>
       <div>
        <span class="btn btn-rose btn-round btn-file">
          <span class="fileinput-new">@lang('admin_messages.select_file')</span>
          <span class="fileinput-exists">@lang('admin_messages.change')</span>
          <input type="file" name="document[@{{$index}}][document_file]" ng-model="document.document_file" class="form-control" id="document_file">
        </span>
        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> @lang('admin_messages.remove')</a>
      </div>
      <p class="logo_error"></p>
      <span class="text-danger">@{{ errors['document.'+$index+'.document_file'][0] }}</span>
    </div>
  </div>
</div>

</div>

<div class="col-12 my-4 text-right">
  <a href="javascript:void(0)" ng-click="add_document()" class="theme-color h6 p-0">
    + Add
  </a>
</div>


<div class="card-footer">
  <div class="ml-auto">

    <button class="btn btn-fill btn-rose btn-wd" type="submit"  value="site_setting">
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