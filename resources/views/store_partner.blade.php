@extends('template')

@section('main')
<main id="site-content" role="main">
	<div class="partners">
		@include ('partner_navigation')
		<div id="sales">
			<div class="d-md-flex align-items-center justify-content-between">
				<h1 class="title">Sales</h1>
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="weekly-tab" data-toggle="tab" href="#weekly" role="tab" aria-controls="weekly" aria-selected="true">7 days</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="false">30 days</a>
					</li>
				</ul>
			</div>
			<div class="panel-content mt-3 my-md-5">
				<div class="tab-pane fade active show" id="weekly" role="tabpanel" aria-labelledby="weekly-tab">
					<div class="d-md-flex align-items-center justify-content-between">
						<div class="net-pay col-md-4">
							<h2>$115.09</h2>
							<p>Net Payout</p>
						</div>
						<div class="net-chart col-md-8 mt-5 mt-md-0">
							<img src="{{url('/')}}/images/chart1.png"/>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
					<div class="d-md-flex align-items-center justify-content-between">
						<div class="net-pay col-md-4">
							<h2>$255.09</h2>
							<p>Net Payout</p>
						</div>
						<div class="net-chart col-md-8 mt-5 mt-md-0">
							<img src="{{url('/')}}/images/chart2.png"/>
						</div>
					</div>
				</div>
				<div class="menu-items mt-5">
					<h3>Top Selling Menu Items</h3>
					<ul class="clearfix mt-3">
						<li><span>7</span>Bacon Burger Combo</li> 
						<li><span>4</span>Cheeseburger Combo</li> 
						<li><span>6</span>Kids Chicken Tender Meal</li> 
						<li><span>3</span>Bacon Burger</li> 
						<li><span>2</span>Swiss and Mushroom Burger</li> 
					</ul>
				</div>
			</div>
			<div class="my-5 select col-12 col-md-6 col-lg-4 p-0">
				<select>
					<option>Past month</option>
					<option>Past week</option>
					<option>Yesterday, 08/28</option>
				</select>
			</div>
		</div>
		<div id="service">
			<h1 class="title">Service Quality</h1>
			<div class="mt-3">
				<p class="light-color">Focus on speed and convenience to keep your {{site_setting('site_name')}} customers happy.</p>
			</div>
			<div class="panel-content mt-3 my-md-5">
				<div class="text-right">
					<p class="light-color">Based on past 30 days</p>
				</div>
				<div class="service-row">
					<h3>Menu Availability</h3>
					<div class="mt-4 d-block row d-lg-flex align-items-center">
						<div class="col-12 col-lg-6">
							<div class="actual-hr d-md-flex align-items-center row">
								<div class="col-12 col-md-4">
									<p>Actual open hours</p>
								</div>
								<div class="col-12 col-md-7 offset-md-1 d-md-flex align-items-center">
									<div class="bar-info w-100 pr-md-3">
										<span class="bar"></span>
									</div>
									<p class="text-nowrap">35 hours</p>
								</div>
							</div>
							<div class="expected-hr d-md-flex align-items-center row mt-4 mt-md-0">
								<div class="col-12 col-md-4">
									<p>Expected open hours</p>
								</div>
								<div class="col-12 col-md-7 offset-md-1 d-md-flex align-items-center">
									<div class="bar-info w-100 pr-md-3">
										<span class="bar"></span>
									</div>
									<p class="text-nowrap">66 hours</p>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-6 mt-4 mt-lg-0">
							<div class="hrs-info pd-15">
								<h4>Stay online during open hours</h4>
								<p>You were offline 31 hours when customers were expecting you to be open.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="service-row">
					<h3>Accepted Orders</h3>
					<div class="mt-4 d-block row d-lg-flex align-items-center">
						<div class="col-12 col-lg-6">
							<div class="accepted-hr d-md-flex align-items-center row">
								<div class="col-12 col-md-4">
									<p>Your Store</p>
								</div>
								<div class="col-12 col-md-7 offset-md-1 d-md-flex align-items-center">
									<div class="bar-info w-100 pr-md-3">
										<span class="bar"></span>
									</div>
									<p class="text-nowrap">100%</p>
								</div>
							</div>
							<div class="expected-hr d-md-flex align-items-center row mt-4 mt-md-0">
								<div class="col-12 col-md-4">
									<p>Other top stores</p>
								</div>
								<div class="col-12 col-md-7 offset-md-1 d-md-flex align-items-center">
									<div class="bar-info w-100 pr-3">
										<span class="bar"></span>
									</div>
									<p class="text-nowrap">66 hours</p>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-6 mt-4 mt-lg-0">
							<div class="hrs-info pd-15">
								<h4>Thanks for being reliable</h4>
								<p>You're fulfilling all the orders that come in.</p>
							</div>
						</div>
					</div>
				</div>
				<div class="service-row">
					<h3>Order Acceptance Speed</h3>
					<div class="mt-4 d-block row d-lg-flex align-items-center">
						<div class="col-12 col-lg-6">
							<div class="speed-hr d-md-flex align-items-center row">
								<div class="col-12 col-md-4">
									<p>Your Store</p>
								</div>
								<div class="col-12 col-md-7 offset-md-1 d-md-flex align-items-center">
									<div class="bar-info w-100 pr-md-3">
										<span class="bar"></span>
									</div>
									<p class="text-nowrap">87 seconds</p>
								</div>
							</div>
							<div class="expected-hr d-md-flex align-items-center row mt-4 mt-md-0">
								<div class="col-12 col-md-4">
									<p>Other top stores</p>
								</div>
								<div class="col-12 col-md-7 offset-md-1 d-md-flex align-items-center">
									<div class="bar-info w-100 pr-md-3">
										<span class="bar"></span>
									</div>
									<p class="text-nowrap">11 seconds</p>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-6 mt-4 mt-lg-0">
							<div class="hrs-info pd-15">
								<h4>Slow acceptance</h4>
								<p>Slow acceptance times may lead more customers to cancel their orders.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="customer-satisfaction">
			<div class="d-md-flex justify-content-between align-items-center">
				<h1 class="title">Customer Satisfaction</h1>
				<p class="light-color">Based on past 3 months</p>
			</div>
			<div class="panel-content my-3 my-md-5">
				<div class="service-row">
					<div class="d-block row d-lg-flex align-items-center">
						<div class="col-12 col-lg-6">
							<h3>78%</h3>
							<p>satisfaction rating</p>
							<div class="cust-hr d-md-flex align-items-center row">
								<div class="col-12 d-md-flex align-items-center">
									<p class="text-nowrap d-block d-md-none text-right mt-3">100%</p>
									<div class="w-100 pr-md-3">
										<div class="bar-info">
											<span class="bar"></span>
											<span class="bar bar-percentage"></span>
										</div>
										<p class="text-right">Top Stores: 100%</p>
									</div>
									<p class="text-nowrap d-none d-md-block">100%</p>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-6 mt-4 mt-lg-0">
							<div class="hrs-info">
								<h5>See what people are saying about your dishes to learn what they like the most</h5>
								<p class="light-color">Customers like your item, Address lower-rated dishes to improve your satisfaction rating.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="ratings-table mb-4">
			<h5>Ratings</h5>
			<div class="table-responsive">
				<table>
					<thead>
						<tr>
							<th>Item</th>
							<th>Satisfaction Rating</th>
							<th>Negative Feedback</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Swiss and Mushroom Burger</td>
							<td>
								<div class="d-flex align-items-center">
									<div class="bar">
										<span class="bar-process yellow"></span>
									</div>
									<span class="text-nowrap ml-3">70% (5)</span>
								</div>
							</td>
							<td>
								<div class="feedbacks">
									<label>
										<span>Portion Size</span>
										<span>1</span>
									</label>
									<label>
										<span>Temperature</span>
										<span>1</span>
									</label>
									<label>
										<span>Presentation</span>
										<span>1</span>
									</label>
									<label>
										<span>Taste</span>
										<span>1</span>
									</label>
								</div>
							</td>
							<td class="text-right"><i class="icon icon-comment-black-rectangular-speech-bubble-interface-symbol"></i></td>
						</tr>
						<tr>
							<td>Swiss and Mushroom Burger</td>
							<td>
								<div class="d-flex align-items-center">
									<div class="bar">
										<span class="bar-process green"></span>
									</div>
									<span class="text-nowrap ml-3">100% (5)</span>
								</div>
							</td>
							<td>
								<div class="feedbacks">
									<label>
										<span>Portion Size</span>
										<span>1</span>
									</label>
									<label>
										<span>Temperature</span>
										<span>3</span>
									</label>
									<label>
										<span>Presentation</span>
										<span>1</span>
									</label>
									<label>
										<span>Taste</span>
										<span>1</span>
									</label>
								</div>
							</td>
							<td class="text-right"><i class="icon icon-comment-black-rectangular-speech-bubble-interface-symbol"></i></td>
						</tr>
						<tr>
							<td>Swiss and Mushroom Burger</td>
							<td>
								<div class="d-flex align-items-center">
									<div class="bar">
										<span class="bar-process yellow"></span>
									</div>
									<span class="text-nowrap ml-3">70% (5)</span>
								</div>
							</td>
							<td>
								<div class="feedbacks">
									<label>
										<span>Portion Size</span>
										<span>2</span>
									</label>
									<label>
										<span>Temperature</span>
										<span>1</span>
									</label>
									<label>
										<span>Presentation</span>
										<span>4</span>
									</label>
									<label>
										<span>Taste</span>
										<span>1</span>
									</label>
								</div>
							</td>
							<td class="text-right"><i class="icon icon-comment-black-rectangular-speech-bubble-interface-symbol"></i></td>
						</tr>
						<tr>
							<td>Swiss and Mushroom Burger</td>
							<td>
								<div class="d-flex align-items-center">
									<div class="bar">
										<span class="bar-process red"></span>
									</div>
									<span class="text-nowrap ml-3">80% (5)</span>
								</div>
							</td>
							<td>
								<div class="feedbacks">
									<label>
										<span>Portion Size</span>
										<span>1</span>
									</label>
									<label>
										<span>Temperature</span>
										<span>2</span>
									</label>
									<label>
										<span>Presentation</span>
										<span>1</span>
									</label>
									<label>
										<span>Taste</span>
										<span>4</span>
									</label>
								</div>
							</td>
							<td class="text-right"><i class="icon icon-comment-black-rectangular-speech-bubble-interface-symbol"></i></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</main>
@stop