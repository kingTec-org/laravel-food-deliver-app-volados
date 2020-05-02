<header>
  <div class="container">
    <div class="top-panel d-block d-md-flex align-items-center justify-content-between">
      <div class="logo text-center">
        @if(current_page()=='store')
        <a href="{{url('store') }}">
          <img src="{{site_setting('1','1')}}">
        </a>
        @else
        <a href="{{url('home') }}">
          <img src="{{site_setting('1','1')}}">
        </a>
        @endif
      </div>

      @if (Route::current()->uri() == '/' & Route::current()->uri() !== 'checkout')
      <div class="flex-grow-1 header-search d-none d-md-block text-center pl-md-4">
        <form class="d-inline-flex justify-content-center" name="search">
          <div class="search-input flex-grow-1 px-2">
            <!-- <svg width="20px" height="22px" viewBox="0 0 16 16" version="1.1"><g id="Symbols" stroke="none" stroke-width="2" fill="none" fill-rule="evenodd"><g id="Icons-/-Semantic-/-Location" stroke="#f68202"><g id="Group" transform="translate(1.500000, 0.000000)"><path d="M6.5,15.285929 L10.7392259,10.9636033 C13.0869247,8.56988335 13.0869247,4.68495065 10.7392259,2.29123075 C8.39683517,-0.0970769149 4.60316483,-0.0970769149 2.26077415,2.29123075 C-0.0869247162,4.68495065 -0.0869247162,8.56988335 2.26077415,10.9636033 L6.5,15.285929 Z" id="Combined-Shape"></path><circle id="Oval-3" cx="6.5" cy="6.5" r="2"></circle></g></g></g></svg> -->
            <input type="text" class="w-100 text-truncate" placeholder="{{trans('messages.enter_delivery_address')}}" value="{{session('location')}}" id="header_location_val" />
          </div>
          <button class="btn btn-theme" type="submit" id="find_food_header">
            {{trans('messages.find_item')}}
          </button>
        </form>
      </div>
      @endif

      @if (Route::current()->uri() == 'checkout')
      <div class="flex-grow-1"></div>
      @endif
      @if (Route::current()->uri() !== '/' && Route::current()->uri() !== 'checkout' &&  !Route::current()->named("store.*"))
      <div class="flex-grow-1 header-search d-flex align-items-center justify-content-center">
        <div class="categories-menu d-block mx-md-3 mx-lg-5 text-nowrap">
          <i class="icon icon-dots-menu d-none d-md-flex align-items-center">
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
                    <li>
                      <a href="{{session('city')}}/{{$recomm->name}}" class="recommended_val" data-id="{{$recomm->id}}">
                        {{$recomm->name}}
                      </a>
                    </li>
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
                      <a href="{{session('city')}}/{{$popular->name}}" data-id="{{$popular->id}}" class="popular_val">
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
        <form class="d-none d-md-none d-lg-block m-0">
          <div class="search-type d-none d-md-none d-lg-flex justify-content-center align-items-center">
            <button class="btn btn-theme schedule-btn">Schedule</button>
            <div class="schedule-dropdown">
              <div class="asap">
                <h3 class="schedule-option d-flex align-items-center">
                  <a class="w-100" href="#"><i class="icon icon-clock"></i>ASAP</a>
                  <i class="icon icon-checked"></i>
                </h3>
              </div>
              <div class="schedule-order">
                <h3 class="schedule-option d-flex align-items-center">
                  <a class="w-100" href="#"><i class="icon icon-clock"></i>Schedule Order</a>
                  <i class="icon icon-checked"></i>
                </h3>
                <div class="schedule-form pd-15">
                  <div class="form-group">
                    <label>Date</label>
                    <div class="select">
                      <select>
                        <option>Mon, May 1</option>
                        <option>Mon, May 2</option>
                        <option>Mon, May 3</option>
                        <option>Mon, May 4</option>
                        <option>Mon, May 5</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Time</label>
                    <div class="select">
                      <select>
                        <option>2:00 PM - 3:00 PM</option>
                        <option>4:00 PM - 5:00 PM</option>
                        <option>5:00 PM - 6:00 PM</option>
                        <option>6:00 PM - 7:00 PM</option>
                        <option>7:00 PM - 8:00 PM</option>
                      </select>
                    </div>
                  </div>
                  <button class="w-100 btn btn-theme" type="submit">Set Time</button>
                </div>
              </div>
            </div>
            <span class="d-inline-block text-nowrap mx-2">{{trans('messages.store.to')}}</span>
            <div class="search-input flex-grow-1">
              <svg width="20px" height="22px" viewBox="0 0 16 16" version="1.1"><g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="Icons-/-Semantic-/-Location" stroke="#262626"><g id="Group" transform="translate(1.500000, 0.000000)"><path d="M6.5,15.285929 L10.7392259,10.9636033 C13.0869247,8.56988335 13.0869247,4.68495065 10.7392259,2.29123075 C8.39683517,-0.0970769149 4.60316483,-0.0970769149 2.26077415,2.29123075 C-0.0869247162,4.68495065 -0.0869247162,8.56988335 2.26077415,10.9636033 L6.5,15.285929 Z" id="Combined-Shape"></path><circle id="Oval-3" cx="6.5" cy="6.5" r="2"></circle></g></g></g></svg>
              <input type="text" class="w-100 text-truncate" id="location_search" placeholder="{{ trans('messages.store.enter_your_address') }}" value="{{session('locality')}}" />
            </div>
          </div>
          <span class="d-none text-danger location_error_msg">Enter your delivery address to see if this store is available in your area </span>
        </form>
      </div>
      @endif
      <div class="main-menu">
        <nav class="navbar navbar-expand-md px-0">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto" ng-controller="store_side_bar">
              @if(is_user() || !Route::current()->named("store.*"))
              <li class="nav-item dropdown">
                <a class="nav-link d-inline-block align-middle user-name p-0 dropdown-toggle" data-toggle="dropdown" href="#">
                  <i class="icon icon-z1 d-inline-block user-icon">
                    <img src="{{url('/')}}/images/user.png"/>
                  </i>
                </a>
                <div class="dropdown-menu">
                  <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                      <a class="nav-link" href="#">
                        <i class="icon icon-user"></i>
                        Profile
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#">
                        <i class="icon icon-credit-card"></i>
                        Payment
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('orders')}}">
                        <i class="icon icon-vegetables"></i>
                        Orders
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('logout')}}">
                        <i class="icon icon-logout"></i>
                        Log out
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              @else

              @if(@get_current_store_id()=='')
              <li class="nav-item">
                <a class="nav-link btn btn-primary" href="{{route('store.login')}}">{{trans('messages.profile.sign_in')}}</a>
              </li>
              @else

              @if(get_current_login_user_details('status')==1)
              <li class="nav-item">
                <label class="switch">
                  <input type="checkbox" id="store_status_toogle" class="status_check" {{get_store_user_id(get_current_store_id(),'status')=='1'?"checked=checked":''}}>
                  <div class="toggle-slider round">
                    <span class="store_status_avai {{get_store_user_id(get_current_store_id(),'status')=='1'?'':'d-none'}}">
                      {{ trans('admin_messages.available') }}
                    </span>
                    <span class="store_status_unavai {{get_store_user_id(get_current_store_id(),'status')=='1'?'d-none':''}}">
                      {{ trans('admin_messages.unavailable') }}
                    </span>
                  </div>
                </label>
              </li>
              @endif

              <li class="nav-item dropdown">
                <a class="nav-link d-inline-block align-middle user-name p-0 dropdown-toggle" data-toggle="dropdown" href="#">
                  <i class="icon icon-z1 d-inline-block user-icon">
                    <img src="{{url('/')}}/images/user.png"/>
                  </i>
                </a>
                <div class="dropdown-menu">
                  <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                      <a class="nav-link" href="{{url('store/profile')}}">
                        <i class="icon icon-user"></i>
                        {{ trans('messages.profile.profile') }}
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="{{route('store.logout')}}">
                        <i class="icon icon-logout"></i>
                        {{ trans('messages.profile.log_out') }}
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              @endif

              @if(is_user() || !Route::current()->named("store.*"))
              <li class="nav-item">
                <a class="nav-link btn btn-theme" href="{{route('signup')}}" name="signup">Register</a>
              </li>
              @endif

              @endif
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </div>
</header>

@if(Session::has('message'))
<div class="alert {{ Session::get('alert-class') }} text-center" role="alert">
  <a href="#" class="alert-close" data-dismiss="alert">&times;</a> {{ Session::get('message') }}
</div>
@endif