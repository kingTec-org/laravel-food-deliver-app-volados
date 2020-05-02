@extends('admin/template')
@section('main')
<div class="content" ng-controller="cancelOrderController">
  <div class="container-fluid">
    <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">{{$form_name}}</h4>
                  </div>
                </div>
                <div class="card-body ">
                {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'order_cancel_form']) !!}
                  @csrf
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.user_type')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                         {!! Form::select('type',$type,@$order_cancel_reason->type, ['placeholder'=>'Select...','class' => 'form-control', 'id' => 'input_type',]) !!}
                           <span class="text-danger">{{ $errors->first('type') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.reason')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                        {!! Form::text('reason',@$order_cancel_reason->name, ['class' => 'form-control','size'=>'3x3', 'id' => 'input_reason',]) !!}
                           <span class="text-danger">{{ $errors->first('reason') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.status')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                        {!! Form::select('status',['1'=>trans('admin_messages.active'),'0'=>trans('admin_messages.inactive')],@$order_cancel_reason->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
                               <span class="text-danger">{{ $errors->first('status') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="panel" ng-init="translations = {{json_encode(old('translations') ?: @$order_cancel_reason->translations)}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}}; result_translations = {{json_encode(@$order_cancel_reason->translations)}}" ng-cloak>
                
                  <div class="panel-body" ng-init="languages = {{json_encode($language)}}">
                    <input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">
                    <div  ng-repeat="translation in translations">
                       <div class="col-sm-12 static_remove">
                          <button class="btn btn-danger btn-xs" ng-click="translations.splice($index, 1); removed_translations.push(translation.id)">
                           Remove
                          </button>
                        </div>
                      <input type="hidden" name="translations[@{{$index}}][id]" value="@{{translation.id}}">
                     
                       <div class="panel-header">
                    <h4 class="box-title text-center">Translations</h4>
                  </div>
                       <div class="row" >
                        <label for="input_language_@{{$index}}" class="col-sm-2 col-form-label">Language<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
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
                    
                      </div>

                      <div class="row">
                        <label for="input_name_@{{$index}}" class="col-sm-2 col-form-label">Reason<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                          <div class="form-group">
                          {!! Form::text('translations[@{{$index}}][reason]', '@{{translation.name}}', ['class' => 'form-control ', 'id' => 'input_name_@{{$index}}', 'placeholder' => 'Reason']) !!}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.reason'][0] }}</span>
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
                          <!-- <i class="fa fa-plus"></i>  -->
                          Add Translation
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