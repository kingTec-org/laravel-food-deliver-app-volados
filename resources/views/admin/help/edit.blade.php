@extends('admin/template')
@section('main')
<div class="content" ng-controller="help" >
  <div class="container-fluid">
    <div class="col-md-12">
              <div class="card ">
                <div class="card-header card-header-rose card-header-text">
                  <div class="card-text">
                    <h4 class="card-title">Edit Help</h4>
                  </div>
                </div>
                
                  <div class="card-body ">
                 {!! Form::open(['url' => 'admin/edit_help/'.$result->id, 'class' => 'form-horizontal','id'=>'help_categpry_form']) !!}
                    <div class="row">
                        <label for="input_language" class="col-sm-2 col-form-label">Category<em class="text-danger">*</em></label>
                        <div class="col-sm-4">
                        <div class="form-group">
                            {!! Form::select('category_id', $category->pluck('name', 'id'), $result->category_id, ['class' => 'form-control', 'id' => 'input_category_id', 'placeholder' => 'Select', 'ng-change' => 'change_category(category_id)', 'ng-model' => 'category_id', 'ng-init' => 'category_id = '.$result->category_id]) !!}
                          </div>
                        </div>
                      </div>


                     <div class="row">
                        <label for="input_language" class="col-sm-2 col-form-label">Sub Category<em class="text-danger">*</em></label>
                        <div class="col-sm-4">
                        <div class="form-group">
                           <select class="form-control" id="input_subcategory_id" name="subcategory_id" ng-model="subcategory_id">
                             <option value="">Select</option>
                             <option ng-repeat="item in subcategory" value="@{{ item.id }}">@{{ item.name }}</option>
                            </select>
                            <input type="hidden" id="hidden_subcategory_id" value="{{ $result->subcategory_id }}">
                          </div>
                        </div>
                      </div>


                       <div class="row">
                        <label for="input_language" class="col-sm-2 col-form-label">Question<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::text('question', $result->question, ['class' => 'form-control', 'id' => 'input_question', 'placeholder' => 'Question']) !!}
                            <span class="text-danger">{{ $errors->first('question') }}</span>
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <label for="input_language" class="col-sm-2 col-form-label">Answer<em class="text-danger">*</em></label>
                        <div class="col-sm-10">
                        <div class="form-group">
                           <textarea id="txtEditor" name="txtEditor" class="txteditor"></textarea>
                            {!! Form::textarea('answer', $result->answer, ['id' => 'answer', 'hidden' => 'true', 'class' => 'txteditor']) !!}
                            <span class="text-danger">{{ $errors->first('answer') }}</span>
                          </div>
                        </div>
                      </div>


                      <div class="row">
                        <label for="input_language" class="col-sm-2 col-form-label">Suggested<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                        <div class="form-group radio_div"  style="line-height: 1;">
                              <span class="radio_but"> {!! Form::radio('suggested', 1, ($result->suggested == 1 ) ? 1 : 0) !!} Yes</span>
                            <span class="radio_but">  {!! Form::radio('suggested', 0, ($result->suggested == 0 ) ? 1 : 0) !!} No</span>
                            <span class="text-danger">{{ $errors->first('suggested') }}</span>
                          </div>
                        </div>
                      </div>


                       <div class="row">
                        <label for="input_language" class="col-sm-2 col-form-label">Status<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                        <div class="form-group">
                             {!! Form::select('status', array('Active' => 'Active', 'Inactive' => 'Inactive'), $result->status ? 'Active' : 'Inactive', ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                              <span class="text-danger">{{ $errors->first('status') }}</span>
                          </div>
                        </div>
                      </div>


                  
                     <div class="panel" ng-init="translations = {{json_encode(old('translations') ?: $result->translations)}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}}; result_translations = {{json_encode($result->translations)}}" ng-cloak>
              
                        <div class="panel-body" ng-init="languages = {{json_encode($languages)}}">
                          <input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">
                          <div class="" ng-repeat="translation in translations">
                         
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
                              <label for="input_name_@{{$index}}" class="col-sm-2 col-form-label">Question<em class="text-danger">*</em></label>
                              <div class="col-sm-6">
                                <div class="form-group">
                                {!! Form::text('translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control ', 'id' => 'input_name_@{{$index}}', 'placeholder' => 'Name']) !!}
                                <span class="text-danger ">@{{ errors['translations.'+$index+'.name'][0] }}</span>
                              </div>
                            </div>
                            </div>

                            <div class="row"  ng-init="multiple_editors($index)">
                              <label for="input_content_@{{$index}}" class="col-sm-2 col-form-label">Answer<em class="text-danger">*</em></label>
                              <div class="col-sm-10">
                                <div class="form-group">
                                <textarea id="editor_@{{$index}}" name="translations[@{{$index}}][txtEditor]" data-index="@{{$index}}" class="txteditor editors"></textarea>
                                <textarea class="contents " id="content_@{{$index}}" name="translations[@{{$index}}][description]" hidden="true" class="txteditor">@{{translation.description}}</textarea>
                               
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
                              <button class="btn btn-fill btn-rose btn-wd" type="submit" name="submit"  value="site_setting">
                                @lang('admin_messages.submit')
                              </button>
                            </div>
                            <div class="clearfix"></div>
                          </div>



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