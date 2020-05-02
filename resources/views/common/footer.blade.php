@php
if(request()->device=='mobile'){
$view_device='mobile';
}
@endphp
<div class="modal fade" id="schedule_modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h3 class="modal-title">{{trans('messages.store.start_new_cart')}}</h3>
			</div>
			<div class="modal-body">
				<p>{{trans('messages.store.some_items_may_not_available_for_selected_time')}}</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" data-val="cancel">
					{{ trans('admin_messages.cancel') }}
				</button>
				<button type="button" class="btn btn-primary schedule_modal" data-dismiss="modal" data-val="ok">
					{{trans('messages.store.confirm')}}
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="schedule_modal1" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h3 class="modal-title">
					{{trans('messages.store.start_new_cart')}}
				</h3>
			</div>
			<div class="modal-body">
				<p class="schedule_modal_text">
					{{trans('messages.store.some_items_may_not_available_for_selected_time')}}
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" data-val="cancel">
					{{ trans('admin_messages.cancel') }}
				</button>
				<button type="button" class="btn btn-primary schedule_modal1" data-dismiss="modal" data-val="ok">
					{{trans('messages.store.confirm')}}
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="schedule_modal_mob" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h3 class="modal-title">{{trans('messages.store.start_new_cart')}}</h3>
			</div>
			<div class="modal-body">
				<p class="schedule_modal_text">{{trans('messages.store.some_items_may_not_available_for_selected_time')}}</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" data-val="cancel">
					{{ trans('admin_messages.cancel') }}
				</button>
				<button type="button" class="btn btn-primary schedule_modal_mob" data-dismiss="modal" data-val="ok">
					{{trans('messages.store.confirm')}}
				</button>
			</div>
		</div>
	</div>
</div>
@if(!isset($view_device))
<footer ng-controller="footer">
	<div class="container">
		<div class="footer-logo d-md-flex align-items-center py-4">
			<a href="{{home_page_link()}}">
				<img src="{{site_setting('1','5')}}"/>
			</a>
		</div>
		<div class="footer-links py-4">
			<div class="row">
				<div class="social-links col-12 col-md-3 col-lg-4">
					<div class="select_lang">  
						{!! Form::select('language',$language, (Session::get('language')) ? Session::get('language') : $default_language[0]->value, ['class' => 'language-selector footer-select selectpicker', 'aria-labelledby' => 'language-selector-label', 'id' => 'language_footer']) !!}
					</div>
					<ul>
						@if(site_setting('join_us_facebook'))
						<li>
							<a href="{{site_setting('join_us_facebook')}}">
								<i class="icon icon-facebook-letter-logo"></i>
							</a>
						</li>
						@endif

						@if(site_setting('join_us_twitter'))
						<li>
							<a href="{{site_setting('join_us_twitter')}}">
								<i class="icon icon-twitter-logo-silhouette"></i>
							</a>
						</li>
						@endif

						@if(site_setting('join_us_youtube'))
						<li>
							<a href="{{site_setting('join_us_youtube')}}">
								<i class="icon icon-youtube"></i>
							</a>
						</li>
						@endif
					</ul>
				</div>
				<div class="user-links col-12 col-md-4 offset-md-1 col-lg-4 offset-lg-0">
					<ul>				
						@if(@$static_pages_changes[0] != '')							
						@foreach($static_pages_changes[0] as $page_url)
						<li>
							<a href="{{route('page',$page_url->url)}}">
								{{$page_url->name}}
							</a>
						</li>
						@endforeach
						@endif
						<li>
							<a href="{{route('help_page',current_page())}}">
								{{trans('messages.footer.help')}}
							</a>
						</li>
					</ul>
				</div>
				<div class="help-links col-12 col-md-3 offset-md-1 col-lg-4 offset-lg-0">
					<ul>
						@if(get_current_root()!='store')
						<li>
							<a href="{{route('driver.signup')}}">
								{{trans('messages.footer.become_a_delivery_partner')}}
							</a>
						</li>
						<li>
							<a href="{{route('store.signup')}}">
								{{trans('messages.footer.become_a_store_partner')}}
							</a>
						</li>
						@endif

						@if(@$static_pages_changes[1] != '')							
						@foreach($static_pages_changes[1] as $page_url)
						<li>
							<a href="{{route('page',$page_url->url)}}">
								{{$page_url->name}}
							</a>
						</li>
						@endforeach
						@endif
					</ul>
				</div>
			</div>
		</div>

		<div class="copyright py-4">
			<div class="row">
				<div class="col-12 text-center">
					<p>© 2019 <a href="https://www.trioangle.com/" class="d-inline-block">Trioangle Technologies</a> Inc.</p>
				</div>
			</div>
		</div>
	</div>
</footer>
@endif
<a href="#top" class="btn-theme scroll-top">
	<i class="icon icon-up-arrow-1"></i>
</a>

@push('scripts')
<script type="text/javascript"></script>
@endpush