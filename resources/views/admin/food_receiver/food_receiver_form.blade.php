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
                {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'issue_form']) !!}
                  @csrf
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.name')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group">
                        {!! Form::text('name',@$food_receiver->name, ['class' => 'form-control','size'=>'3x3', 'id' => 'input_name',]) !!}
                           <span class="text-danger">{{ $errors->first('name') }}</span>
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