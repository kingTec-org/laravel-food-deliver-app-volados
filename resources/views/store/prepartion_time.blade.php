@extends('template')

@section('main')
<main id="site-content" role="main">
	<div class="partners">
		@include ('partner_navigation')
		<div class="pickup-times my-4 panel-content">
			<h1>Pickup times</h1>
			<p>We aim to have someone pick up your item as soon as it's ready. When a courier arrives at your store will depend on your average prep times, size of the order, time of the day, traffic, and patterns from past deliveries.</p>
			<h6 class="my-4 theme-color">
				TIPS FOR ACCURATE PICKUPS
			</h6>
			<div class="mt-4">
				<h2>Average item preparation times</h2>
				<p>let us know how long it usually takes to prepare an order, and we'll use this information to improve pickup accuracy.</p>
				<div class="my-3 d-flex align-items-center add-times justify-content-between">
					<input type="text" name="" value="50">
					<span class="d-inline-block ml-1">minutes</span>
					<a href="javascript:void(0)"><i class="icon icon-remove ml-3"></i></a>
					<a href="javascript:void(0)"><i class="icon icon-add ml-3"></i></a>
				</div>
				<div class="my-3 d-md-flex align-items-center added-times-row">
					<div class="d-flex align-items-center add-times justify-content-between">
						<input type="text" name="" value="50" readonly>
						<span class="d-inline-block ml-1">minutes</span>
						<a href="javascript:void(0)"><i class="icon icon-remove ml-3"></i></a>
						<a href="javascript:void(0)"><i class="icon icon-add ml-3"></i></a>
					</div>
					<div class="select ml-md-3">
						<select>
							<option>Mon</option>
							<option>Tue</option>
							<option>Wed</option>
							<option>Thu</option>
						</select>
					</div>
					<div class="added-times d-flex ml-3 align-items-center">
						<input type="text" name="" value="5:00 PM" readonly>
						<span class="d-inline-block mx-2">to</span>
						<input type="text" name="" value="5:00 PM" readonly>
						<i class="icon icon-close-2 d-inline-block ml-2 theme-color"></i>
					</div>
				</div>
				<div class="mt-4">
					<a href="javascript:void(0)" class="theme-color">
						<i class="icon icon-add mr-2"></i>
						ADD MORE
					</a>
					<div class="mt-3">
						<button type="submit" class="btn btn-theme">SAVE</button>
					</div>	
				</div>
			</div>
		</div>
	</div>
</main>
@stop