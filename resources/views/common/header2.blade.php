@php
if(request()->device=='mobile'){
$view_device='mobile';
}
@endphp
@if(!isset($view_device))
<header ng-controller="header_controller" ng-cloak ng-init="order_data={{json_encode(session('order_data'))}};schedule_status= '{{session('schedule_data') ? trans('messages.store.'.strtolower(session('schedule_data')['status'])):trans('messages.store.asap')}}';schedule_time_value={{json_encode(time_data('schedule_time'))}}">
  <div class="container">
    <div class="top-panel d-block d-md-flex align-items-center justify-content-between">
      <div class="logo text-center">
       @if (@$page->user_page==1)
       <a href="{{route('store.signup')}}">
        <img src="{{site_setting('1','1')}}"/>
      </a>
      @elseif (@$page->user_page==2)
      <a href="{{route('driver.signup')}}">
        <img src="{{site_setting('1','1')}}"/>
      </a>
      @else
      <a href="{{route('home')}}">
        <img src="{{site_setting('1','1')}}"/>
      </a>
      @endif
    </div>
    <input type="hidden" id="orderdata" value="{{json_encode(session('order_data'))}}">
    @if (Route::current()->uri() == '/' && Route::current()->uri() !== 'checkout')
    <div class="flex-grow-1 header-search d-none d-md-block text-center pl-md-4">
      <form class="d-inline-flex justify-content-center" name="search">
        <div class="search-input flex-grow-1 px-2">
          <!-- <svg width="20px" height="22px" viewBox="0 0 16 16" version="1.1"><g id="Symbols" stroke="none" stroke-width="2" fill="none" fill-rule="evenodd"><g id="Icons-/-Semantic-/-Location" stroke="#f68202"><g id="Group" transform="translate(1.500000, 0.000000)"><path d="M6.5,15.285929 L10.7392259,10.9636033 C13.0869247,8.56988335 13.0869247,4.68495065 10.7392259,2.29123075 C8.39683517,-0.0970769149 4.60316483,-0.0970769149 2.26077415,2.29123075 C-0.0869247162,4.68495065 -0.0869247162,8.56988335 2.26077415,10.9636033 L6.5,15.285929 Z" id="Combined-Shape"></path><circle id="Oval-3" cx="6.5" cy="6.5" r="2"></circle></g></g></g></svg> -->
          <input type="text" class="w-100 text-truncate" placeholder="{{trans('messages.enter_delivery_address')}}" value="{{session('location')}}" id="header_location_val" />
        </div>
        <button class="btn btn-theme" type="submit" id="find_food_header">{{trans('messages.find_item')}}</button>
      </form>
    </div>
    @endif
    @if (Route::current()->uri() == 'checkout')
    <div class="flex-grow-1"></div>
    @endif
    @if (Route::current()->uri() !== '/' && Route::current()->uri() !== 'checkout' &&  !Route::current()->named("store.*"))
    <div class="flex-grow-1 header-search d-flex align-items-center justify-content-center">
      <div class="categories-menu d-block mx-md-3 mx-lg-5 text-nowrap">
        <i class="icon icon-dots-menu d-none d-md-inline-flex align-items-center">
          <span>{{trans('messages.store.categories')}}</span>
        </i>
        <div class="category-list">
          <div class="container">
            <div class="row">
              <div class="d-block d-md-none text-right w-100 pr-15 close_opt">
                <i class="icon icon-close-2 sm-category-close"></i>
              </div>

              <input type="hidden" class="city" id="header_city" value="{{session('locality')}}">

              <div class="col-12 col-md-6 float-left recommended">
                <label>{{trans('messages.store.recommended')}}</label>
                <ul class="clearfix">
                  @if(menu_category('recommended')!='')
                  @foreach(menu_category('recommended') as $recomm)

                  <li><a href="{{route('search')}}?q={{$recomm->name}}" class="recommended_val" data-id="{{$recomm->id}}">{{$recomm->name}}</a></li>

                  @endforeach
                  @endif

                </ul>
              </div>
              <div class="col-12 mt-4 mt-md-0 col-md-6 float-right most-popular">
                <label>{{trans('messages.store.most_popular')}}</label>
                <div class="search-list clearfix">
                 @if(menu_category('most_popular')!='')
                 @foreach(menu_category('most_popular') as $popular)

                 <div class="search-item" style="background-image: url({{$popular->category_image}});">
                  <a href="{{route('search')}}?q={{$popular->name}}" data-id="{{$popular->id}}" class="popular_val">
                    <div class="search-info row d-flex align-items-center h-100">
                      <p class="mx-auto">{{$popular->name}}</p>
                    </div>
                  </a>
                </div>

                @endforeach
                @endif

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form class="search-form d-none d-lg-block m-0" ng-cloak>
      <div class="search-type d-none d-md-none d-lg-flex justify-content-center align-items-center" ng-cloak>

        <button class="btn btn-theme schedule-btn" type="button" id="schedule_button" style="display: none;">@{{schedule_status}}  </button>
        <input class="btn btn-theme schedule-btn" type="hidden" ng-model="schedule_status_clone"  style="display: none;"></input>
        <input class="btn btn-theme schedule-btn" type="hidden" id="schedule_status_session"  style="display: none;" value="{{@session('schedule_data')[status]}}"></input>
        <div class="schedule-dropdown">
          <input type="hidden" id="schedule_data" value="{{json_encode(session('schedule_data'))}}">
          <div class="asap">
            <h3 class="schedule-option d-flex align-items-center {{@session('schedule_data')[status]!='Schedule'?'active':''}} " data-val="ASAP">
              <a class="w-100" href="#"><i class="icon icon-clock"></i>{{trans('messages.store.asap')}}</a>
              <i class="icon icon-checked"></i>
            </h3>
          </div>
          <div class="schedule-order">
            <h3 class="schedule-option d-flex align-items-center {{@session('schedule_data')[status]=='Schedule'?'active':''}} " data-val="Schedule">
              <a class="w-100" href="#"><i class="icon icon-clock"></i>{{trans('messages.store.schedule_order')}}</a>
              <i class="icon icon-checked"></i>
            </h3>
            <div class="schedule-form pd-15">
              <div class="form-group">
                <label>{{trans('messages.store.date')}}</label>
                <div class="select" ng-init="schedule_date_value='{{session('schedule_data')['date']?:date('Y-m-d')}}';schedule_time_set='{{session('schedule_data')['time']}}'">
                  <select id="schedule_date" ng-model="schedule_date_value">
                    <option disabled="disabled" value="">{{trans('messages.store_dashboard.select')}}</option>
                    @foreach(date_data() as $key=>$data)

                    <option value="{{$key}}" {{ ($key == session('schedule_data')['date']) ? 'selected' : '' }}>{{date('Y', strtotime($data)).', '.trans('messages.driver.'.date('M', strtotime($data))).' '.date('d', strtotime($data))}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label>{{trans('messages.store.time')}}</label>
                <div class="select">
                  <select id="schedule_time">
                    <option ng-selected="schedule_time_set==key" ng-repeat="(key ,value) in schedule_time_value" value="@{{key}}" ng-if="(key | checkTimeInDay :schedule_date_value)">
                      @{{value}}
                    </option>
                  </select>
                </div>
              </div>
              <button class="w-100 btn btn-theme" id="set_time" type="submit">{{trans('messages.store.set_time')}}</button>
            </div>
          </div>
        </div>
        <span class="d-inline-block text-nowrap mx-2">{{trans('messages.store.to')}}</span>
        <div class="search-input flex-grow-1">
          <svg width="20px" height="22px" viewBox="0 0 16 16" version="1.1"><g id="Symbols" stroke="none" stroke-width="2" fill="none" fill-rule="evenodd"><g id="Icons-/-Semantic-/-Location" stroke="#f68202"><g id="Group" transform="translate(1.500000, 0.000000)"><path d="M6.5,15.285929 L10.7392259,10.9636033 C13.0869247,8.56988335 13.0869247,4.68495065 10.7392259,2.29123075 C8.39683517,-0.0970769149 4.60316483,-0.0970769149 2.26077415,2.29123075 C-0.0869247162,4.68495065 -0.0869247162,8.56988335 2.26077415,10.9636033 L6.5,15.285929 Z" id="Combined-Shape"></path><circle id="Oval-3" cx="6.5" cy="6.5" r="2"></circle></g></g></g></svg>
          <input type="text" class="w-100 text-truncate" id="location_search" placeholder="{{ trans('messages.store.enter_your_address') }}" value="{{session('locality')}}" />
        </div>
      </div>
      <span class="d-none text-danger location_error_msg">{{trans('messages.store.enter_your_delivery_address_to_see')}} </span>
    </form>
  </div>
  @endif
  <div class="main-menu">
    <nav class="navbar navbar-expand-md px-0">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto" ng-cloak>
          @if(is_user())
          <li class="nav-item dropdown">
            <a class="nav-link d-inline-block align-middle user-name p-0 dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon icon-z1 d-inline-block user-icon">
                @if(@Auth::guard('web') == '')
                <img src="{{url('/')}}/images/user.png" class="profile_picture"/>
                @else
                <img src="{{@Auth::guard('web')->user()->eater_image}}" class="profile_picture"/>
                @endif
              </i>
            </a>
            <div class="dropdown-menu">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                  <a class="nav-link" href="{{route('user_profile')}}">
                    <i class="icon icon-user"></i>
                    {{trans('messages.profile.profile')}}
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('user_payment')}}">
                    <i class="icon icon-credit-card"></i>
                    {{trans('messages.profile.payment')}}
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('orders')}}">
                    <i class="icon icon-vegetables"></i>
                    {{trans('messages.profile.orders')}}
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="{{route('logout')}}">
                    <i class="icon icon-logout"></i>
                    {{trans('messages.profile.log_out')}}
                  </a>
                </li>
              </ul>
            </div>
          </li>
          @else
          <li class="nav-item">
            <a class="nav-link btn btn-secondary" href="{{route('login')}}">{{trans('messages.profile.sign_in')}}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-theme" href="{{route('signup')}}" name="signup">{{trans('messages.profile.register')}}</a>
          </li>
          @endif
          @if(session('locality') || total_count_card() > 0)
          <li class="nav-item">
            <a class="nav-link p-0" href="{{route('checkout')}}" id="card_page">
              <i class="icon icon-shopping-bag-1 {{total_count_card() > 0 ? 'active':''}}"></i>
              <span class="cart-count ml-1">
                <span id="count_card" class="text-hide" ng-cloak>
                  {{total_count_card()}}
                </span>
              </span>
            </a>
          </li>
          @endif
        </ul>
      </div>
    </nav>
  </div>
</div>
</div>
<div class="flash-container">
  @if(Session::has('message'))
  <div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
    <a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
  </div>
  @endif
</div>
</header>
@endif

