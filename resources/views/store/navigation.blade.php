<nav id="sidebar">
	<button id="sidebarCollapse" type="button" data-toggle="active" data-target="#sidebar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="icon-bar"></span>
	</button>
	<ul class="list-unstyled components">
		<li class="{{navigation_active('store.dashboard') ? 'active':''}}">
			<a href="{{route('store.dashboard')}}">
				<i class="icon icon-dashboard"></i>
				<span>{{ trans('admin_messages.dashboard') }}</span>
			</a>
		</li>

		<li class="d-md-none {{navigation_active('store.profile') ? 'active':''}}">
			<a href="{{url('store/profile')}}">
				<i class="icon icon-user"></i>
				<span>{{ trans('messages.profile.profile') }}</span>
			</a>
		</li>

		<li class="{{navigation_active('store.offers') ? 'active':''}}">
			<a href="{{route('store.offers')}}">
				<i class="icon icon-offer"></i>
				<span>{{ trans('messages.store_dashboard.offers') }}</span>
			</a>
		</li>

		<li class="{{navigation_active('store.payout_preference') ? 'active':''}}">
			<a href="{{route('store.payout_preference')}}">
				<i class="icon icon-credit-card"></i>
				<span>{{ trans('messages.store_dashboard.payout_details') }}</span>
			</a>
		</li>

		<li class="{{navigation_active('store.menu') ? 'active':''}}">
			<a href="{{route('store.menu')}}">
				<i class="icon icon-vegetables"></i>
				<span>{{ trans('admin_messages.category') }}</span>
			</a>
		</li>
		<li class="{{navigation_active('store.preparation') ? 'active':''}}">
			<a href="{{route('store.preparation')}}">
				<i class="icon icon-timer"></i>
				<span>{{ trans('messages.store_dashboard.timings') }}</span>
			</a>
		</li>

		@if(isset($static_pages))
		<!-- 	<li>
				<a href="{{url($static_pages[0]->url)}}">
					<i class="icon icon-question-mark"></i>
					<span>Help</span>
				</a>
			</li> -->
		@endif

		@if(@get_current_store_id()!=='')
		<li class="d-md-none">
			<a href="{{route('store.logout')}}">
				<i class="icon icon-logout"></i>
				<span>{{ trans('messages.profile.log_out') }}</span>
			</a>
		</li>
		@endif
	</ul>
</nav>