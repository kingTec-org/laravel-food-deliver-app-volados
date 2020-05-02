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
							<input type="text" name="q" autocomplete="off" maxlength="1024" value="" placeholder={{trans('messages.help.search')}} id="help_search">						</div>
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
							@foreach($remain_help_subcategory as $help_category)
							<li>
								<a class="help_nav_link {{$help_category->id==$subcategory_id?'active':''}}"  href="{{route('help_subcategory',['page'=>$page,'category_id'=>$help_category->category_id,'subcategory_id'=>$help_category->id])}}">
										
									{{$help_category->name_lang}}
								</a> 
							</li>
							@endforeach
							
						</ul>
					</div>

					<div class="help-faq col-md-8 col-lg-9 p-0">
						<div class="breadcrumb">
							<ul>
								<li>
									<a href="{{route('help_page',$page)}}">
										{{trans('messages.help.for')}} 

										
										 
										 {{trans('messages.help.'.$page)}}										
										 
									</a>
									<i class="icon icon-angle-arrow-pointing-to-right-1 mx-2"></i>
								</li>
								<li>
									<a href="{{route('help_category',['page'=>$page,'category_id'=>$category->id])}}">
										{{$category->category_name_lang}}
									</a>
									<i class="icon icon-angle-arrow-pointing-to-right-1 mx-2"></i>
								</li>
							</ul>
						</div>
						



						<div class="help-info active" help-attr="riders">
							
						@foreach($help_subcategory as $sub_category)
							<div class="help-list">
								<div class="list-info">
									<span>
										<h3>
											{{$sub_category->name_lang}}
										</h3>
									</span>
									<!-- {{$sub_category->description_lang}} -->

									<ul>

										@foreach($sub_category->help as $question)
										<li>
											<a href="{{route('help_question',['page'=>$page,'category_id'=>$question->category_id,'subcategory_id'=>$question->subcategory_id,'question_id'=>$question->id])}}">
												{{$question->question_lang}}
											</a>
										</li>
										@endforeach
									</ul> 
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