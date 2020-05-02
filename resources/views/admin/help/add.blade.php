@extends('admin/template')
@section('main')
<div class="content" ng-controller="help">
  <div class="container-fluid">
    <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">{{$form_name}}</h4>
                  </div>
                </div>
                <div class="card-body ">
                {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'help_categpry_form']) !!}
                  @csrf

                     
                     <div class="row">
                        <label for="input_language" class="col-sm-2 col-form-label">Language<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                        <div class="form-group">
                          {!! Form::select('language', $language, 'en', ['class' => 'form-control', 'id' => 'input_language', 'disabled' =>'disabled']) !!}
                          </div>
                        </div>
                      </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.category_id')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6" ng-init="category_id='{{@$help->category_id?$help->category_id:''}}'">
                        <div class="form-group">
                        {!! Form::select('category_id', $category->pluck('name', 'id'), @$help->category_id, ['class' => 'form-control', 'id' => 'input_category_id', 'placeholder' => 'Select', 'ng-change' => 'change_category(category_id)', 'ng-model' => 'category_id']) !!}

                          <span class="text-danger">{{ $errors->first('category_id') }}</span>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.subcategory_id')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group" ng-init="subcategory_id='{{@$help->subcategory_id?$help->subcategory_id:''}}';subcategory={{json_encode($subcategory)}}">
                         <select class="form-control"  id="input_subcategory_id"  name="subcategory_id">
                           <option value="">Select</option>
                           <option ng-repeat="item in subcategory" ng-selected="subcategory_id==item.id" value="@{{ item.id }}">@{{ item.name }}</option>
                          </select>
                          <span class="text-danger">{{ $errors->first('subcategory_id') }}</span>
                        </div>
                      </div>
                    </div>  
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.question')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                        {!! Form::text('question',@$help->question, ['class' => 'form-control','size'=>'3x3', 'id' => 'input_name',]) !!}
                           <span class="text-danger">{{ $errors->first('question') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.answer')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group">                         
                          
                           <!-- <textarea id="answer" name="answer" class="gre">
                              {{@$help->answer}}
                            </textarea> -->
                            <textarea id="txtEditor" name="txtEditor" class="txteditor"></textarea>
                            <textarea id="answer" name="answer" hidden="true">{{ old('answer') }}</textarea>
                          <span class="text-danger">{{ $errors->first('answer') }}</span>

                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.suggested')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group radio_div">
                           <span class="radio_but"> {!! Form::radio('suggested', 1, (@$help->suggested == 1) ? 1 : 0) !!} Yes</span>
                             <span class="radio_but"> {!! Form::radio('suggested', 0, (@$help->suggested == 0) ? 1 : 0) !!} No</span>
                             <span class="radio_but">
                             </span>
                        </div>
                        <span class="text-danger">{{ $errors->first('suggested') }}</span>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.status')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                          {!! Form::select('status',$status_list,@$help->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
                          <span class="text-danger">{{ $errors->first('status') }}</span>
                        </div>
                      </div>
                    </div>


                  <div class="panel" ng-init="translations = {{json_encode(old('translations') ?: array())}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}};" ng-cloak>

                  <div class="panel-body">
                      <input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">
                     
                      <div ng-repeat="translation in translations">
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
                      <label for="input_name_@{{$index}}" class="col-sm-2 col-form-label">@lang('admin_messages.question')<span class="required text-danger">*</span></label>
                      <div class="col-sm-6">
                        <div class="form-group">
                        {!! Form::text('translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_@{{$index}}', 'placeholder' => 'Question']) !!}
                           <span class="text-danger">{{ $errors->first('question') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row"  >
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.answer')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group" ng-init="multiple_editors($index)">                         
                                                    
                          <textarea class="editors" id="editor_@{{$index}}" name="translations[@{{$index}}][txtEditor]"></textarea>
                          <textarea class="contents" id="content_@{{$index}}" name="translations[@{{$index}}][description]" hidden="true">@{{translation.description}}</textarea>
                          {{--{!! Form::textarea('translations[@{{$index}}][description]', '@{{translation.description}}', ['class' => 'form-control', 'id' => 'input_content_@{{$index}}', 'placeholder' => 'Description', 'hidden' => true]) !!}--}}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.description'][0] }}</span>

                        </div>
                      </div>
                    </div>

                      <legend ng-if="$index+1 < translations.length"></legend>
                    </div>
                  </div>
                  <div class="panel-footer">
                    <div class="row" ng-show="translations.length <  {{count($language) - 1}}">
                      <div class="col-sm-12">
                        <button type="button" class="btn btn-info" ng-click="translations.push({locale:''});" >
                          Add Translation
                        </button>
                      </div>
                    </div>

                           </div>
              </div>

                    <div class="card-footer">
                      <div class="ml-auto">
                        <button class="btn btn-fill btn-rose btn-wd" type="submit" name="submit"  value="site_setting">
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
$('.Editor-editor').html($('#answer').val());
</script>
@endpush