@extends('admin/template')
@section('main')
<div class="content" ng-controller="categoryController">
  <div class="container-fluid">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header card-header-rose card-header-text">
          <div class="card-text">
            <h4 class="card-title">{{$form_name}}</h4>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'ciusine_form','files'=>'true']) !!}
          @csrf
          <input type="hidden" name="action" class="form_type" value="edit">
          <div class="row">
                        <label for="input_language" class="col-sm-2 col-form-label">Language<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                        <div class="form-group">
                          {!! Form::select('language', $language, 'en', ['class' => 'form-control', 'id' => 'input_language', 'disabled' =>'disabled']) !!}
                          </div>
                        </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">@lang('admin_messages.name')<span class="required text-danger">*</span></label>
            <div class="col-sm-6">
              <div class="form-group">
               {!! Form::text('name',@$category->name, ['class' => 'form-control', 'id' => 'input_email',]) !!}
               <span class="text-danger">{{ $errors->first('name') }}</span>
             </div>
           </div>
         </div>
        <div class="row">
          <label class="col-sm-2 col-form-label">@lang('admin_messages.description')<em class="text-danger">*</em></label>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::textarea('description',@$category->description, ['class' => 'form-control','size'=>'3x3', 'id' => 'input_password',]) !!}
              <span class="text-danger">{{ $errors->first('description') }}</span>
            </div>
          </div>
        </div>

        <div class="row">
          <label class="col-md-2 col-form-label">
            @lang('admin_messages.image')<em class="text-danger">*</em>
          </label>
          <div class="col-md-4">
            <div class="fileinput fileinput-new" data-provides="fileinput">
              <div class="fileinput-new thumbnail">
                @if(@$category->category_image!='')
                <img src="{{$category->category_image}}" alt="...">
                @endif
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail"></div>
              <div class="image_upload">
                <span class="btn btn-rose btn-round btn-file">
                  <span class="fileinput-new">@lang('admin_messages.select_image')</span>
                  <span class="fileinput-exists">@lang('admin_messages.change')</span>
                  {!! Form::file('image',['class' => 'form-control', 'id' => 'image','data-error-placement'=>'container','data-error-container'=>'#error-box']) !!}
                </span>
                <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> @lang('admin_messages.remove')</a>
                <span id="error-box"></span>
              </div>
              <small>@lang('admin_messages.size')(250x140)</small>
              <span class="text-danger d-block">{{ $errors->first('image') }}</span>
            </div>
          </div>
        </div>

        <div class="row">
          <label class="col-sm-2 col-form-label">@lang('admin_messages.status')<span class="required text-danger">*</span></label>
          <div class="col-sm-6">
            <div class="form-group">
              {!! Form::select('status',['1'=>trans('admin_messages.active'),'0'=>trans('admin_messages.inactive')],@$category->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
              <span class="text-danger">{{ $errors->first('status') }}</span>
            </div>
          </div>
        </div>

            <div class="panel" ng-init="translations = {{json_encode(old('translations') ?: @$category->translations)}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}}; result_translations = {{json_encode(@$category->translations)}}" ng-cloak>
                
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
                        <label for="input_name_@{{$index}}" class="col-sm-2 col-form-label">Name<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                          <div class="form-group">
                          {!! Form::text('translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control ', 'id' => 'input_name_@{{$index}}', 'placeholder' => 'Name']) !!}
                          <span class="text-danger ">@{{ errors['translations.'+$index+'.name'][0] }}</span>
                        </div>
                        </div>
                      </div>   

                      <div class="row"  ng-init="multiple_editors($index)">
                        <label for="input_content_@{{$index}}" class="col-sm-2 col-form-label">Description<em class="text-danger">*</em></label>
                        <div class="col-sm-6">
                        <div class="form-group">
                          <input type="hidden" class="index_val" value='@{{$index}}'>
                          <textarea class="form-control" id="description_@{{$index}}" name="translations[@{{$index}}][description]" cols="3" rows="3">@{{translation.description}}</textarea>
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
checkbox();
$('#is_dietary').click(function(){
  checkbox();
})
function checkbox(){
    if($('#is_dietary:checked').length > 0)
      $('.is_dietary_icon').show();
    else
      $('.is_dietary_icon').hide();
}

</script>
@endpush