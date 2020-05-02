@extends('template')

@section('main')
<main id="site-content" role="main" class="rating-page">
	<div class="container">
		<div class="rating-head py-4 py-md-5">
			<h1 class="title">
				rate your order
			</h1>
		</div>
		<div class="rating-wrap pb-5">
			<div class="rating-list">
				<form class="d-md-flex w-100 align-items-center">
					<div class="rating-id text-center col-md-3 p-0 my-3">
						<img class="user-img" src="{{url('/')}}/images/user.png"/>
						<h4>
							french boys
						</h4>
						<p>
							order id# 12458
						</p>
					</div>
					<div class="rating-content text-center col-md-9">
						<div class="food-rating d-md-flex justify-content-center">
							<div class="rating-img-list col-md-6">
								<div class="rating-img">
									<h2>
										veg burger
									</h2>
									<img src="{{url('/')}}/images/food1.jpg"/>
									<div class="like-icon">
										<i class="icon icon-thumbs-up-1"></i>
										<i class="icon icon-thumbs-down"></i>
									</div>
								</div>
								<div class="mt-5">
									<h3>
										How was it ?
									</h3>
									<ul class="like-group">
										<li>
											<label>
												<input type="radio" name="like-icon" value="like">
												<i class="icon icon-thumbs-up-1"></i>
											</label>
										</li>
										<li>
											<label>
												<input type="radio" name="like-icon" value="dislike">
												<i class="icon icon-thumbs-down"></i>		
											</label>
										</li>
									</ul>
									<ul class="dislike-info mt-3">
										<li>
											<input type="checkbox" name="">
											<span>taste</span>
										</li>
										<li>
											<input type="checkbox" name="">
											<span>size</span>
										</li>
										<li>
											<input type="checkbox" name="">
											<span>presentation</span>
										</li>
										<li>
											<input type="checkbox" name="">
											<span>temperature</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<div class="delivery-rating mt-4 pt-4">
							<h3>
								How was the delivery ?
							</h3>
							<ul class="like-group">
								<li>
									<label>
										<input type="radio" name="delivery-like" value="like">
										<i class="icon icon-thumbs-up-1"></i>
									</label>
								</li>
								<li>
									<label>
										<input type="radio" name="delivery-like" value="dislike">
										<i class="icon icon-thumbs-down"></i>
									</label>
								</li>
							</ul>
							<ul class="dislike-info mt-3">
								<li>
									<input type="checkbox" name="">
									<span>taste</span>
								</li>
								<li>
									<input type="checkbox" name="">
									<span>size</span>
								</li>
								<li>
									<input type="checkbox" name="">
									<span>presentation</span>
								</li>
								<li>
									<input type="checkbox" name="">
									<span>temperature</span>
								</li>
							</ul>
						</div>
						<div class="store-rating rating-img-list mt-4 pt-3">
							<div class="rating-img">
								<h2>
									subway
								</h2>
								<img src="{{url('/')}}/images/food1.jpg"/>
							</div>
							<ul class="ratings mt-3">
								<li>
									<i class="icon icon-star"></i>
								</li>
								<li>
									<i class="icon icon-star"></i>
								</li>
								<li>
									<i class="icon icon-star"></i>
								</li>
								<li>
									<i class="icon icon-star-1"></i>
								</li>
								<li>
									<i class="icon icon-star-1"></i>
								</li>
							</ul>
							<div class="text-center mt-4">
							<a href="javascript:void(0)" class="btn btn-theme">
									submit
								</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
@endsection