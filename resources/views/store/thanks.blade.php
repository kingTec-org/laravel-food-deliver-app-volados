@extends('template')

@section('main')
<main id="site-content" role="main">
	<div class="signup-page" ng-controller="store_signup">
		<div class="banner-info">
			<div class="container">
				<div class="banner-txt">
					<div class="col-md-5 col-lg-6 p-0">
						<h1>The fast way to <span>get item to your customers</span>
						</h1>
					</div>
					<div class="banner-form">
					<h3> Thanks! </h3>
					<p> Someone from our team will get in touch with you soon.</p>
					</div>
				</div>
			</div>
		</div>

		<div class="signup-slider mb-5 owl-carousel">
			<div class="slide-txt" style="background-image: url('{{url('/')}}/images/banner1.jpg');">
				<div class="container">
					<div class="col-md-5 col-lg-6 p-0">
						<h1>Amarit Dulyapaibul</h1>
						<p>Lettuce Entertain You, Chicago</p>
					</div>
				</div>
			</div>
			<div class="slide-txt" style="background-image: url('{{url('/')}}/images/banner2.jpg');">
				<div class="container">
					<div class="col-md-5 col-lg-6 p-0">
						<h1>Amarit Dulyapaibul</h1>
						<p>Lettuce Entertain You, Chicago</p>
					</div>
				</div>
			</div>
			<div class="slide-txt" style="background-image: url('{{url('/')}}/images/banner3.jpg');">
				<div class="container">
					<div class="col-md-5 col-lg-6 p-0">
						<h1>Amarit Dulyapaibul</h1>
						<p>Lettuce Entertain You, Chicago</p>
					</div>
				</div>
			</div>
		</div>

		<div class="stores-info my-5">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="res-img">
							<img src="{{url('/')}}/images/res1.gif"/>
						</div>
						<h2>Do more business</h2>
						<p>{{site_setting('site_name')}} makes a real impact on your business. When your item is featured in the app, new customers can discover it and loyal customers can enjoy it more often. We’ve seen stores increase sales, lower marketing costs, and hire new employees to capitalize on {{site_setting('site_name')}} demand.</p>
					</div>
					<div class="col-md-4">
						<div class="res-img">
							<img src="{{url('/')}}/images/res2.gif"/>
						</div>
						<h2>Deliver faster</h2>
						<p>{{site_setting('site_name')}} is the fast way to get item to your customers. With hundreds of delivery partners on the road, you can deliver in an average of 15 minutes and maintain the best possible item quality. You can also track orders from the floor, right to a customer’s door.</p>
					</div>
					<div class="col-md-4">
						<div class="res-img">
							<img src="{{url('/')}}/images/res3.gif"/>
						</div>
						<h2>Partner with professionals</h2>
						<p>When you partner with {{site_setting('site_name')}}, we’re in the weeds with you. We’ll get you set up, promote your menu, and work with you to improve pickup and delivery times. We’ll continue to test and learn together to boost volume, keep operations smooth, and impress your customers.</p>
					</div>
				</div>
			</div>
		</div>

		<div class="profile-slider owl-carousel">
			<div class="item d-md-flex align-items-center flex-md-row-reverse">			
				<div class="slider-img col-md-5" style="background-image: url('{{url('/')}}/images/banner1.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4>“'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).”</h4>
						<p><strong>john</strong>
							<span>Lettuce Entertain You, General Manager</span>
						</p>
					</div>
				</div>
			</div>
			<div class="item d-md-flex align-items-center flex-md-row-reverse">			
				<div class="slider-img col-md-5" style="background-image: url('{{url('/')}}/images/banner2.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4>“change and provide honest feedback so we can improve.”</h4>
						<p><strong>Mark</strong>
							<span>Owner</span>
						</p>
					</div>
				</div>
			</div>
			<div class="item d-md-flex align-items-center flex-md-row-reverse">
				<div class="slider-img col-md-5" style="background-image: url('{{url('/')}}/images/banner3.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4>“They give us the autonomy to make change and provide honest feedback so we can improve.”</h4>
						<p>
							<strong>Nick</strong>
							<span>Chef</span>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
@stop