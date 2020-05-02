@extends('template2')

@section('main')
<div class="flash-container">

  </div>
<main id="site-content" role="main" class="log-user" ng-controller="home_page">
	<div class="container"><br><br><br>
		
		{!! $page->content !!}
	</div>
</main>
@stop