@extends('template2')

@section('main')
<main id="site-content" role="main" ng-controller="help">
	<div class="help-page">
		<div class="help-top d-flex">
			<div class="help-banner-search">
				<div class="container">
					<div class="banner-content d-md-flex align-items-center">
						<p>
							{{trans('messages.help.we_here_to_help')}}
						</p>
						<div class="search-input ml-md-5 col-md-6 p-0">
							<i class="icon icon-search"></i>
							<input type="text" name="q" autocomplete="off" maxlength="1024" value="" placeholder={{trans('messages.help.search')}} id="help_search">
						</div>
					</div>
				</div>
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
								<a class="help_nav_link active" href="javascript:void(0)" help-attr="riders">
									{{trans('messages.help.for_riders')}}
								</a>
							</li>
							<li>
								<a class="help_nav_link" href="javascript:void(0)" help-attr="partners">
									{{trans('messages.help.for_partners')}}
								</a> 
							</li>
							<li>
								<a class="help_nav_link" href="javascript:void(0)" help-attr="eaters">
									{{trans('messages.help.for_users')}}
								</a> 
							</li>
							<li>
								<a class="help_nav_link" href="javascript:void(0)" help-attr="stores">
									{{trans('messages.help.for_stores')}}
								</a> 
							</li>
						</ul>
					</div>

					<div class="help-faq col-md-8 col-lg-9 p-0">
						<div class="breadcrumb">
							<ul>
								<li>
									<a href="javascript:void(0)">
										for riders
									</a>
									<i class="icon icon-angle-arrow-pointing-to-right-1 mx-2"></i>
								</li>
								<li>
									<a href="javascript:void(0)">
										a guide to gofer
									</a>
								</li>
							</ul>
						</div>
						<div class="help-result active" help-attr="riders">						
							<div class="help-head">
								<h2>
									How to request a ride
								</h2>
							</div>
							<div class="help-list">
								<div class="list-info">
									<ul>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="help-result" help-attr="partners">						
							<div class="help-head">
								<h2>
								How to join as a partner
								</h2>
							</div>
							<div class="help-list">
								<div class="list-info">
									<ul>				
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>					
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="help-result" help-attr="eaters">
							<div class="help-head">
								<h2>
									How to book as a user
								</h2>
							</div>
							<div class="help-list">
								<div class="list-info">
									<ul>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="help-result" help-attr="stores">
							<div class="help-head">
								<h2>
									How to add a store
								</h2>
							</div>
							<div class="help-list">
								<div class="list-info">
									<ul>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
									</ul>
								</div>
							</div>

							<div class="help-list">
								<div class="list-info">
									<ul>									
										<li>
											It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
										</li>
										<li>
											Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
										</li>
										<li>
											Lorem Ipsum is simply dummy text of the printing and typesetting industry.
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@endsection