@extends('admin/template')
@section('main')
<div class="content" ng-controller="page">
  <div class="container-fluid">
    <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">{{$form_name}}</h4>
                  </div>
                </div>
                <div class="card-body ">
                {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'static_page_form']) !!}
                  @csrf
                    <span class="text-danger">(*)Fields are Mandatory</span>
                <div class="row">
                  <label for="input_language" class="col-sm-2 col-form-label">Language<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    {!! Form::select('language', $language, 'en', ['class' => 'form-control', 'id' => 'input_language', 'disabled' =>'disabled']) !!}
                  </div>
                </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.name')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                         {!! Form::text('name',@$static_page->name, ['class' => 'form-control', 'id' => 'input_email',]) !!}
                           <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.url')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                        {!! Form::text('url',@$static_page->url, ['class' => 'form-control', 'id' => 'input_password',]) !!}
                           <span class="text-danger">{{ $errors->first('url') }}</span>
                        </div>
                      </div>
                    </div>
                    
                     <div class="row">
                        <label for="input_content" class="col-sm-2 col-form-label">Content<em class="text-danger">*</em></label>
                        <div class="col-sm-10">

                           <div class="form-group">
                          <textarea id="txtEditor" name="txtEditor"></textarea>
                          <textarea id="content" name="content" hidden="true">{{ old('content') }}</textarea>
                          <span class="text-danger">{{ $errors->first('content') }}</span>
                      </div>
                    </div>
                      </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.footer')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                        {!! Form::select('footer',['1'=>trans('admin_messages.yes'),'0'=>trans('admin_messages.no')],@$static_page->footer, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
                               <span class="text-danger">{{ $errors->first('footer') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.user_page')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                        {!! Form::select('user_page',$user_pages,@$static_page->user_page, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
                               <span class="text-danger">{{ $errors->first('user_page') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.status')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                        {!! Form::select('status',['1'=>trans('admin_messages.active'),'0'=>trans('admin_messages.inactive')],@$static_page->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
                               <span class="text-danger">{{ $errors->first('status') }}</span>
                        </div>
                      </div>
                    </div>



                    <div class="panel" ng-init="translations = {{json_encode(old('translations') ?: array())}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}};">
           
                  <div class="panel-body">
                    <input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">
                     
                    <div  ng-repeat="translation in translations">
                       <div class="col-sm-12 static_remove">
                        <button class="btn btn-danger btn-xs" ng-hide="translations.length <  {{count($language) - 1}}" ng-click="translations.splice($index, 1); removed_translations.push(translation.id)">
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
                        <label for="input_name_@{{$index}}" class="col-sm-2 col-form-label">Name<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                           <div class="form-group">
                          {!! Form::text('translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_@{{$index}}', 'placeholder' => 'Name']) !!}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.name'][0] }}</span>
                        </div>
                      </div>
                      </div>

                      <div class="row"  ng-init="multiple_editors($index)">
                        <label for="input_content_@{{$index}}" class="col-sm-2 col-form-label">Content<em class="text-danger">*</em></label>
                        <div class="col-sm-10">
                           <div class="form-group">
                          <textarea class="editors" id="editor_@{{$index}}" name="translations[@{{$index}}][txtEditor]"></textarea>
                          <textarea class="contents" id="content_@{{$index}}" name="translations[@{{$index}}][content]" hidden="true">@{{translation.content}}</textarea>
                          {{--{!! Form::textarea('translations[@{{$index}}][content]', '@{{translation.content}}', ['class' => 'form-control', 'id' => 'input_content_@{{$index}}', 'placeholder' => 'Content', 'hidden' => true]) !!}--}}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.content'][0] }}</span>
                        </div>
                      </div>
                      </div>

                      <legend ng-if="$index+1 < translations.length"></legend>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <div class="row" ng-show="translations.length <  {{count($language) - 1}}">
                      <div class="col-sm-12">
                        <button ng-if="translations.length<{{count($language)}}" type="button" class="btn btn-info" ng-click="translations.push({locale:''});" >
                          <!-- <i class="fa fa-plus"></i> -->
                           Add Translation
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                    <div class="card-footer">
                      <div class="ml-auto">
                        <button type="submit" class="submit_form btn btn-fill btn-rose btn-wd"  value="site_setting" name="submit" >
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
$("#txtEditor").Editor(); 
$('.Editor-editor').html($('#content').val());
</script>
@endpush