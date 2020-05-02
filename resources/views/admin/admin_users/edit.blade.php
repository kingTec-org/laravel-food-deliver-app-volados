@extends('admin/template')
@section('main')
<div class="content">
  <div class="container-fluid">
    <div class="col-md-12">
      <div class="card ">
        <div class="card-header card-header-rose card-header-text">
          <div class="card-text">
            <h4 class="card-title"> Edit Admin User </h4>
          </div>
        </div>
        <div class="card-body ">
          {!! Form::open(['url' => route('admin.update_admin',['id' => $result->id]), 'class' => 'form-horizontal','id'=>'help_categpry_form']) !!}
          @csrf
          <div class="row">
            <label class="col-sm-2 col-form-label">@lang('admin_messages.name')<span class="required text-danger">*</span></label>
            <div class="col-sm-10">
              <div class="form-group">
                {!! Form::text('username',$result->username, ['class' => 'form-control','size'=>'3x3', 'id' => 'input_username']) !!}
                <span class="text-danger">{{ $errors->first('username') }}</span>
              </div>
            </div>
          </div>

          <div class="row">
            <label class="col-sm-2 col-form-label">@lang('admin_messages.email')<span class="required text-danger">*</span></label>
            <div class="col-sm-10">
              <div class="form-group">
                {!! Form::text('email',$result->email, ['class' => 'form-control','size'=>'3x3', 'id' => 'input_email']) !!}
                <span class="text-danger">{{ $errors->first('email') }}</span>
              </div>
            </div>
          </div>

          <div class="row">
            <label class="col-sm-2 col-form-label">@lang('admin_messages.password')<span class="required text-danger">*</span></label>
            <div class="col-sm-10">
              <div class="form-group">
                {!! Form::text('password','', ['class' => 'form-control','size'=>'3x3', 'id' => 'input_password']) !!}
                <span class="text-danger">{{ $errors->first('password') }}</span>
              </div>
            </div>
          </div>

          <div class="row">
            <label class="col-sm-2 col-form-label">@lang('admin_messages.status')<span class="required text-danger">*</span></label>
            <div class="col-sm-10">
              <div class="form-group">
                {!! Form::select('status', array('1' => 'Active', '0' => 'Inactive'), $result->status, ['class' => 'form-control', 'id' => 'input_status', 'placeholder' => 'Select']) !!}
                <span class="text-danger">{{ $errors->first('status') }}</span>
              </div>
            </div>
          </div>

          <div class="card-footer">
            <div class="ml-auto">
              <button class="btn btn-fill btn-rose btn-wd" type="submit"  value="edit_admin">
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