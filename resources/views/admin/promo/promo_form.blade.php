@extends('admin/template')
@section('main')
<div class="content">
  <div class="container-fluid">
    <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">{{$form_name}}</h4>
                  </div>
                </div>
                <div class="card-body ">
                {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'promo_form']) !!}
                  @csrf
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.code')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group">
                         {!! Form::text('code',@$promo->code, ['class' => 'form-control', 'id' => 'input_email',]) !!}
                           <span class="text-danger">{{ $errors->first('code') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.promo_type')<span class="required text-danger">*</span></label>
                      <div class="col-sm-4">
                        <div class="form-group">
                         {!! Form::select('promo_type',['1'=>trans('admin_messages.percentage'),'0'=>trans('admin_messages.price')],@$promo->promo_type, ['id' => 'promo_type','placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
                           <span class="text-danger">{{ $errors->first('promo_type') }}</span>
                        </div>
                      </div>
                    </div>

                    <div class="promo_price">
                      <div class="row">
                        <label class="col-sm-2 col-form-label">@lang('admin_messages.price')<span class="required text-danger">*</span></label>
                        <div class="col-sm-10">
                          <div class="form-group">
                          {!! Form::text('price',@$promo->price, ['class' => 'form-control', 'id' => 'input_password',]) !!}
                             <span class="text-danger">{{ $errors->first('price') }}</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="promo_percentage">
                      <div class="row">
                        <label class="col-sm-2 col-form-label">@lang('admin_messages.percentage')<span class="required text-danger">*</span></label>
                        <div class="col-sm-10">
                          <div class="form-group">
                          {!! Form::text('percentage',@$promo->percentage, ['class' => 'form-control', 'id' => 'input_password',]) !!}
                             <span class="text-danger">{{ $errors->first('percentage') }}</span>
                          </div>
                        </div>
                      </div>
                      {{--<!-- <div class="row">
                        <label class="col-sm-2 col-form-label">@lang('admin_messages.min_price')<span class="required text-danger">*</span></label>
                        <div class="col-sm-10">
                          <div class="form-group">
                          {!! Form::text('min_price',@$promo->min_price, ['class' => 'form-control', 'id' => 'min_price',]) !!}
                             <span class="text-danger">{{ $errors->first('min_price') }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <label class="col-sm-2 col-form-label">@lang('admin_messages.promo_max_price')<span class="required text-danger">*</span></label>
                        <div class="col-sm-10">
                          <div class="form-group">
                          {!! Form::text('promo_max_price',@$promo->promo_max_price, ['class' => 'form-control', 'id' => 'promo_max_price',]) !!}
                             <span class="text-danger">{{ $errors->first('promo_max_price') }}</span>
                          </div>
                        </div>
                      </div> -->--}}
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">@lang('admin_messages.currency_code')<span class="required text-danger">*</span></label>
                        <div class="col-sm-10">
                          <div class="form-group">
                           {!! Form::text('currency_code',site_setting('default_currency'), ['disabled' => 'disabled','class' => 'form-control', 'id' => 'currency_code']) !!}
                           {!! Form::hidden('currency_code',site_setting('default_currency'), ['class' => 'form-control', 'id' => 'currency_code']) !!}
                            <span class="text-danger">{{ $errors->first('currency_code') }}</span>
                          </div>
                        </div>
                      </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.start_date')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group">
                        {!! Form::text('start_date',set_date_on_picker(@$promo->start_date), ['class' => 'form-control datepicker', 'id' => 'input_password',]) !!}
                           <span class="text-danger">{{ $errors->first('convert_start_date') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.end_date')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group">
                        {!! Form::text('end_date',set_date_on_picker(@$promo->end_date), ['class' => 'form-control datepicker', 'id' => 'input_password',]) !!}
                           <span class="text-danger">{{ $errors->first('convert_end_date') }}</span>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.status')<span class="required text-danger">*</span></label>
                      <div class="col-sm-4">
                        <div class="form-group">
                        {!! Form::select('status',['1'=>trans('admin_messages.active'),'0'=>trans('admin_messages.inactive')],@$promo->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
                               <span class="text-danger">{{ $errors->first('status') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="panel" ng-init="translations = {{json_encode(old('translations') ?: @$promo->translations)}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}}; result_translations = {{json_encode(@$promo->translations)}}" ng-cloak>
                  <div class="panel-header">
                    <h4 class="box-title text-center">Translations</h4>
                  </div>
                  <div class="panel-body" ng-init="languages = {{json_encode($language)}}">
                    <input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">
                    <div  ng-repeat="translation in translations">
                      <input type="hidden" name="translations[@{{$index}}][id]" value="@{{translation.id}}">
                     

                       <div class="row" >
                        <label for="input_language_@{{$index}}" class="col-sm-2 col-form-label">Language<em class="text-danger">*</em></label>
                        <div class="col-sm-4">
                        <div class="form-group">
                          <select name="translations[@{{$index}}][locale]" class="form-control" id="input_language_@{{$index}}" ng-model="translation.locale" >
                            <option value="" ng-if="translation.locale == ''">Select Language</option>
                            @foreach($language as $key => $value)
                              <option value="{{$key}}" ng-if="(('{{$key}}' | checkKeyValueUsedInStack : 'locale': translations) || '{{$key}}' == translation.locale) && '{{$key}}' != 'en'">{{$value}}</option>
                            @endforeach
                          </select>
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.locale'][0] }}</span>
                          </div>
                        </div>
                        <div class="col-sm-1">
                          <button class="btn btn-danger btn-xs" ng-click="translations.splice($index, 1); removed_translations.push(translation.id)">
                            <i class="fa fa-trash"></i>
                          </button>
                        </div>
                      </div>

                      <div class="row">
                        <label for="input_name_@{{$index}}" class="col-sm-2 col-form-label">Code<em class="text-danger">*</em></label>
                        <div class="col-sm-4">
                          <div class="form-group">
                          {!! Form::text('translations[@{{$index}}][lang_code]', '@{{translation.code}}', ['class' => 'form-control ', 'id' => 'input_name_@{{$index}}', 'placeholder' => 'Code']) !!}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.lang_code'][0] }}</span>
                        </div>
                        </div>
                      </div>                     

                      <legend ng-if="$index+1 < translations.length"></legend>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <div class="row" ng-show="(translations | checkActiveTranslation: languages).length <  {{count($language) - 1}}">
                      <div class="col-sm-12">
                        <button type="button" class="btn btn-info" ng-click="translations.push({locale:''});" >
                          <i class="fa fa-plus"></i> Add Translation
                        </button>
                      </div>
                    </div>
                  </div>
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
$('#promo_type').change(function() {
  if($(this).val()==1) {
    $('.promo_price').hide();
    $('.promo_percentage').show();
  } else if($(this).val()==0) {
    $('.promo_price').show();
    $('.promo_percentage').hide();
  } else {
    $('.promo_price').hide();
    $('.promo_percentage').hide();
  }
});
$(document).ready(function(){
  if($('#promo_type').val()==1) {
    $('.promo_price').hide();
    $('.promo_percentage').show();
  } else if($('#promo_type').val()==0){
    $('.promo_price').show();
    $('.promo_percentage').hide();
  } else {
    $('.promo_price').hide();
    $('.promo_percentage').hide();
  }
});


</script>
@endpush