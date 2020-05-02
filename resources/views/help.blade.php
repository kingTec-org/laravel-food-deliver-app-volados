@extends('template2')

@section('main')
<main id="site-content" role="main" ng-controller="help">
	<div class="help-page">
		<div class="help-top d-flex">
			<div class="whole-banner">


         @if($page=='user')
				<div class="help-banner {{$page=='user'?'active':''}}" help-attr="eaters" style="background-image: url({{url('/')}}/images/banner3.jpg);" >
					<div class="container d-flex h-100">
						<div class="banner-content my-auto">
							<h1>
								{{trans('messages.profile.having_trouble')}}?
							</h1>
							<p>
								{{trans('messages.help.we_here_to_help')}}
							</p>

							<div class="search-input">
								<i class="icon icon-search"></i>
								<input type="text" name="q" autocomplete="off" maxlength="1024" value="" placeholder="{{trans('messages.store.search')}}" id="help_search">
							</div>
						</div>
					</div>
				</div>

				@elseif($page=='driver')

				<div class="help-banner {{$page=='driver'?'active':''}}" help-attr="partners" style="background-image: url({{url('/')}}/images/banner2.jpg);">
					<div class="container d-flex h-100">
						<div class="banner-content my-auto">
							<h1>
								{{trans('messages.profile.having_trouble')}}?
							</h1>
							<p>
								{{trans('messages.help.we_here_to_help')}}
							</p>

							<div class="search-input">
								<i class="icon icon-search"></i>
								<input type="text" name="" placeholder="{{trans('messages.store.search')}}" id="help_search">
							</div>
						</div>
					</div>
				</div>

				@else

				<div class="help-banner {{$page=='store'?'active':''}}" help-attr="stores" style="background-image: url({{url('/')}}/images/default-store.jpg);">
					<div class="container d-flex h-100">
						<div class="banner-content my-auto">
							<h1>
								{{trans('messages.profile.having_trouble')}}?
							</h1>
							<p>
								{{trans('messages.help.we_here_to_help')}}
							</p>

							<div class="search-input">
								<i class="icon icon-search"></i>
								<input type="text" name="" placeholder="{{trans('messages.store.search')}}" id="help_search">
							</div>
						</div>
					</div>
				</div>
				@endif
			</div>

			<div class="site-pattern">
				<svg xmlns="http://www.w3.org/2000/svg">
					<defs>
						<pattern id="a___-1531234641" width="60" height="60" patternUnits="userSpaceOnUse">
							<path class="pattern-stroke" d="M11.5 39.8L0 51.2 8.8 60h12.4l8.8-8.8-11.5-11.4c-1.9-2-5.1-2-7 0z"></path>
							<path class="pattern-stroke" d="M30 38.8L18.5 50.3c-2 2-5.1 2-7.1 0L0 38.8 8.8 30h12.4l8.8 8.8zm11.5 1L30 51.2l8.8 8.8h12.4l8.8-8.8-11.5-11.4c-1.9-2-5.1-2-7 0z"></path>
							<path class="pattern-stroke" d="M60 38.8L48.5 50.3c-2 2-5.1 2-7.1 0L30 38.8l8.8-8.8h12.4l8.8 8.8zm-48.5-29L0 21.2 8.8 30h12.4l8.8-8.8L18.5 9.8c-1.9-2-5.1-2-7 0z"></path>
							<path class="pattern-stroke" d="M30 8.8L18.5 20.3c-2 2-5.1 2-7.1 0L0 8.8 8.8 0h12.4L30 8.8zm11.5 1L30 21.2l8.8 8.8h12.4l8.8-8.8L48.5 9.8c-1.9-2-5.1-2-7 0z"></path>
							<path class="pattern-stroke" d="M60 8.8L48.5 20.3c-2 2-5.1 2-7.1 0L30 8.8 38.8 0h12.4L60 8.8z"></path>
						</pattern>
					</defs>
					<rect fill="url(#a___-1531234641)" height="100%" width="100%"></rect>
				</svg>
			</div>
		</div>

		<div class="help-content py-5">
			<div class="container">
				<div class="d-md-flex">
					<div class="help-nav col-md-4 col-lg-3 pl-0">
						<ul>
							<li>
								<a class="help_nav_link {{$page=='user'?'active':''}}" href="{{route('help_page','user')}}" help-attr="eaters">
									{{trans('messages.help.for_users')}}
								</a>
							</li>
							<li>
								<a class="help_nav_link {{$page=='driver'?'active':''}}" href="{{route('help_page','driver')}}" help-attr="partners">
									{{trans('messages.help.for_partners')}}
								</a>
							</li>

							<li>
								<a class="help_nav_link {{$page=='store'?'active':''}}" href="{{route('help_page','store')}}" help-attr="stores">
									{{trans('messages.help.for_stores')}}
								</a>
							</li>
						</ul>
					</div>

					<div class="help-list col-md-8 col-lg-9 p-0">
						{{--@if($page=='driver' && !auth()->guard('driver')->check())
							<div class="help-sign d-md-flex align-items-center justify-content-between">
								<h2>
									{{trans('messages.help.help_with_sign')}}
								</h2>

								<a href="{{route('driver.login')}}" class="btn driver-theme text-uppercase ml-auto">
									{{trans('messages.help.sign_in')}}
								</a>
							</div>
						@endif--}}


						<div class="help-info flex-wrap row active" help-attr="partners">
							@foreach($help as $help_category)
								<div class="help-list d-flex col-lg-6">
									<div class="list-img">
										<svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 88 88" height="60" width="60"><defs><pattern id="a___115562847" data-name="2/2 - black" width="4" height="4" patternTransform="translate(0 34.35)" patternUnits="userSpaceOnUse" viewBox="0 0 4 4"><path class="cls-1" fill="none" d="M0 0h4v4H0z"></path><path d="M0 0h2v2H0z"></path></pattern></defs><title>ias-icons</title><path d="M5.66 44V6.33A6.33 6.33 0 0 1 12 0H6.33A6.33 6.33 0 0 0 0 6.33V44a6.33 6.33 0 0 0 6.33 6.33H12A6.33 6.33 0 0 1 5.66 44z"></path><rect class="cls-2" fill="#d6d6d5" x="5.66" width="82.34" height="50.35" rx="6.33" ry="6.33"></rect><path class="cls-3" stroke="#000" stroke-miterlimit="10" stroke-width="2" fill="none" d="M52.67 17.32h26.35m-26.35-6.44h26.35M52.67 23.83h26.35"></path><path class="illustration-primary-fill" d="M14.52 40.46V9.91h30.55v30.55z"></path><path class="illustration-secondary-fill" d="M45.08 9.9L14.52 40.45V9.9h30.56z"></path><circle class="cls-6" fill="#f8f8f9" cx="28.6" cy="23.55" r="8.19"></circle><path d="M32.11 40.45h-5.89v-3a8.91 8.91 0 0 1 2.61-6.3l.69-.69a8.91 8.91 0 0 0 2.61-6.3h1.64v16.29h-1.66z"></path><path class="cls-6" fill="#f8f8f9" d="M20.41 27.8h10.85v12.66H20.41z"></path><path class="cls-6" fill="#f8f8f9" d="M28.6 35h6.67a1.53 1.53 0 0 0 1.53-1.53v-10h-8.2V35z"></path><path class="cls-6" fill="#f8f8f9" d="M36.79 25.25v3.26h.82a.38.38 0 0 0 .27-.65 3.7 3.7 0 0 1-1.09-2.61z"></path><path d="M28.6 15.36a8 8 0 0 0-8.19 8V35.4l2.83-2.83A3.58 3.58 0 0 0 24.29 30v-4.16a1.27 1.27 0 0 1 1.27-1.27 1.27 1.27 0 0 1 1.27 1.27V26h.73a2.22 2.22 0 0 0 2.22-2.22v-1a1.61 1.61 0 0 1 1.61-1.61h5.05a8.2 8.2 0 0 0-7.84-5.81z"></path><path d="M28.78 18.11h6.59a3.73 3.73 0 0 1 3.73 3.73v1.26H28.78v-5 .01zm-8.37 22.34h-5.89v-3a8.91 8.91 0 0 1 2.61-6.3l.69-.69a8.91 8.91 0 0 0 2.61-6.3h3.86v12.41a3.88 3.88 0 0 1-3.88 3.88z"></path><path class="cls-7" fill="url(#a___115562847)" d="M52.67 30.42h26.35v10.04H52.67z"></path></svg>
									</div>

									<div class="list-info">
										<a href="{{route('help_category',['page'=>$page,'category_id'=>$help_category->id])}}">
											<h3>
												{{$help_category->category_name_lang}}
											</h3>
										</a>
										<ul>
										@foreach($help_category->subcategory_limit as $help_sub)

											<li>
												<a href="{{route('help_subcategory',['page'=>$page,'category_id'=>$help_category->id,'subcategory_id'=>$help_sub->id])}}">
													{{$help_sub->name_lang}}
												</a>
											</li>
										@endforeach
										</ul>
										@if($help_category->subcategory()->count()>4)
										<a href="javascript:void(0)" class="link-arrow text-capitalize more-underline d-inline-block mt-2">
											more
										</a>
										@endif
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection