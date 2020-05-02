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
                {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'metas_form']) !!}
                  @csrf
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.page')</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                         {!! Form::text('page',@$meta->url, ['readonly'=>'true','class' => 'form-control', 'id' => 'input_page',]) !!}
                           <span class="text-danger">{{ $errors->first('page') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.title')</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                         {!! Form::text('title',@$meta->title, ['class' => 'form-control', 'id' => 'input_title',]) !!}
                           <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.description')</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                        {!! Form::textarea('description',@$meta->description, ['class' => 'form-control','size'=>'3x3', 'id' => 'input_password',]) !!}
                           <span class="text-danger">{{ $errors->first('description') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.keywords')</label>
                      <div class="col-sm-10">
                        <div class="form-group">
                         {!! Form::text('keywords',@$meta->keywords, ['class' => 'form-control', 'id' => 'input_keywords',]) !!}
                           <span class="text-danger">{{ $errors->first('keywords') }}</span>
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