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
                {!! Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'add_user_form']) !!}
                  @csrf
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.first_name')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group">
                         {!! Form::text('first_name',@$user->first_name, ['class' => 'form-control', 'id' => 'input_first_name',]) !!}
                           <span class="text-danger">{{ $errors->first('first_name') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.last_name')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group">
                         {!! Form::text('last_name',@$user->last_name, ['class' => 'form-control', 'id' => 'input_last_name',]) !!}
                           <span class="text-danger">{{ $errors->first('last_name') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.email')<span class="required text-danger">*</span></label>
                      <div class="col-sm-10">
                        <div class="form-group">
                         {!! Form::text('email',@$user->email, ['class' => 'form-control', 'id' => 'input_email',]) !!}
                           <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.password')
                        @if(@$user->email=='')
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

                          <select id="phone_code_country" name="country_code" class="form-control">
                                @foreach ($country as $key => $country)
                                    <option value="{{ $country->phone_code }}" {{ $country->phone_code == @$user->country_code ? 'selected' : '' }} >{{ $country->name }}</option>
                                @endforeach
                            </select>

                               <span class="text-danger">{{ $errors->first('country_code') }}</span>
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="form-group">
                               {!! Form::text('text',@$user->country_code?'+'.$user->country_code:'', ['readonly'=>'readonly','class'=>'form-control','id'=>'apply_country_code']); !!}
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">

                               {!! Form::text('mobile_number',@$user->mobile_number, ['class' => 'form-control', 'id' =>'input_mobile_number','placeholder'=>trans('admin_messages.phone_no')]) !!}
                               <span class="text-danger">{{ $errors->first('mobile_number') }}</span>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                      <label class="col-sm-2 col-form-label">@lang('admin_messages.status')<span class="required text-danger">*</span></label>
                      <div class="col-sm-4">
                        <div class="form-group">
                        {!! Form::select('status',['1'=>trans('admin_messages.active'),'0'=>trans('admin_messages.inactive')],@$user->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']); !!}
                               <span class="text-danger">{{ $errors->first('status') }}</span>
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
  $('#phone_code_country').change(function() {
    $('#apply_country_code').val('');
    if($(this).val())
    $('#apply_country_code').val('+'+$(this).val());
  });
</script>
@endpush