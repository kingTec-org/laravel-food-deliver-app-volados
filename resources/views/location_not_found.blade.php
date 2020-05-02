@extends('template2')

@section('main')
<main id="site-content" role="main" ng-controller="location_not_found" class="not-found">
<!-- 	<a href="#" data-toggle="modal" data-target="#myModal" class="toogle_modal1" style="display:none"></a>
	  <div class="modal fade" id="myModal" role="dialog">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h3 class="modal-title">Your cart is no longer valid</h3>
	        </div>
	        <div class="modal-body">
	          <p>Order location is too far from store</p>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-primary" data-dismiss="modal" data-val="ok">Ok</button>
	        </div>
	      </div>
	    </div>
	</div> -->

	<div class="container">
		<div class="text-center pt-5 pb-3">
			<h4>
				Something went wrong ! 
			</h4>
			<p>
				We can't find the page that you're looking for
			</p>
			<div class="loading-img mt-3">
				<img class="img-fluid" src="{{url('/')}}/images/food-loader.gif"/>
			</div>
		</div>
	</div>
</main>
@stop