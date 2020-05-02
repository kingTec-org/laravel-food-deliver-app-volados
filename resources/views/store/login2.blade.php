@extends('template')

@section('main')
<div class="flash-container">
      @if(Session::has('message'))
          <div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
              <a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
          </div>
      @endif
  </div>
<main id="site-content" role="main" class="log-user">
	<div class="container">
		<div class="login-form py-5 mb-5 col-md-8 col-lg-5 col-xl-4 mx-auto">
			<h4 class="text-center my-3">Test@gmail.com</h4>
			{!! Form::open(['url'=>route('store.password'),'method'=>'post','class'=>'mt-4' , 'id'=>'login_form'])!!}
						@csrf
				<div class="form-group">
					<label>{{trans('messages.profile.enter_your_password')}}</label>

					{!! Form::password('textInputPassword',['placeholder' => 'Password','ng-model' => 'textInputPassword'])!!}
				</div>
				<button class="btn btn-theme w-100 mt-3 d-flex justify-content-between align-items-center" type="submit">{{trans('messages.profile.next_button')}} <i class="icon icon-right-arrow"></i></button>
			</form>
		</div>
	</div>
</main>
@stop