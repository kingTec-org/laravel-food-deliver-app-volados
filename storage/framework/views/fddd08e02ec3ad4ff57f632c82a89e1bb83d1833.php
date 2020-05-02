<?php $__env->startSection('main'); ?>
<div class="flash-container">
	<?php if(Session::has('message')): ?>
	<div class="alert <?php echo e(Session::get('alert-class')); ?> text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> <?php echo e(Session::get('message')); ?>

	</div>
	<?php endif; ?>
</div>
<main id="site-content" role="main" class="driver" ng-controller="driver_signup">
	<div class="signup-page">
		<div class="banner-info">
			<div class="container">
				<div class="banner-txt">
					<div class="col-md-5 col-lg-6 col-xl-7 p-0">
						<h1><?php echo e(trans('messages.driver.deliver_with')); ?> <?php echo e(site_setting('site_name')); ?> <span><?php echo e(trans('messages.driver.own_schedule')); ?></span>
						</h1>
					</div>
					<div id="driver-form" class="banner-form">
						<h4><?php echo e(trans('messages.driver.sign_up_now')); ?></h4>
						<?php echo Form::open(['url'=>route('driver.signup'),'method'=>'post','class'=>'mt-4' , 'id'=>'signup_form']); ?>

						<?php echo csrf_field(); ?>
						<input type="hidden" name="user_type" value="Driver">
						<div class="form-group">
							<input type="text" name="email" placeholder="<?php echo e(trans('messages.driver.email')); ?>"  autocomplete="off" />
							<span class="text-danger"><?php echo e($errors->first('email')); ?></span>
						</div>
						<div class="form-group">
							<div class="row">
								<div class="col-md-6">
									<input type="text" name="first_name" placeholder="<?php echo e(trans('messages.driver.first_name')); ?>" autocomplete="off" />
									<span class="text-danger"><?php echo e($errors->first('first_name')); ?></span>
								</div>
								<div class="col-md-6">
									<input type="text" name="last_name" placeholder="<?php echo e(trans('messages.driver.last_name')); ?>" autocomplete="off" />
									<span class="text-danger"><?php echo e($errors->first('last_name')); ?></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="d-flex w-100">

									<div class="select mob-select col-md-3">
									<span class="phone_code">+<?php echo e(@session::get('phone_code')); ?></span>

									<select id="phone_code" name="country_code" class="form-control">
						                    <?php $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						                        <option value="<?php echo e($country->phone_code); ?>" <?php echo e($country->phone_code == @session::get('phone_code') ? 'selected' : ''); ?> ><?php echo e($country->name); ?></option>
						                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						                </select>

									</div>
									<input type="text" name="phone_number" id="phone_number" placeholder="<?php echo e(trans('messages.driver.phone_number')); ?>" />
							</div>
							<span class="text-danger"><?php echo e($errors->first('phone_number')); ?></span>
						</div>

						<div class="form-group">
							<input type="password" name="password" placeholder="<?php echo e(trans('messages.driver.create_password')); ?>" autocomplete="off" />
							<span class="text-danger"><?php echo e($errors->first('password')); ?></span>
						</div>

						<div class="form-group">
							<input type="text" name="address" placeholder="<?php echo e(trans('messages.driver.city')); ?>" id="driver_address" />
							<span class="text-danger"><?php echo e($errors->first('address')); ?></span>
							<input type="hidden" name="city" id='city' value="">
							<input type="hidden" name="state" id="state" value="">
							<input type="hidden" name="country" id="country" value="">
							<input type="hidden" name="address_line1" id="address_line1" value="">
							<input type="hidden" name="postal_code" id="postal_code">
							<input type="hidden" name="latitude" id="latitude" value="">
							<input type="hidden" name="longitude" id="longitude" value="">
						</div>

						<p><?php echo e(trans('messages.driver.agree_to')); ?> <?php echo e(site_setting('site_name')); ?>'s <a href="<?php echo e(route('page','terms_driver')); ?>" class="theme-color"><?php echo e(trans('messages.driver.terms_of_use')); ?></a> <?php echo e(trans('messages.driver.acknowledge')); ?> <a href="<?php echo e(route('page','privacy_driver')); ?>" class="theme-color"><?php echo e(trans('messages.driver.privacy_policy')); ?>.</a></p>
						<p><?php echo e(trans('messages.driver.agree', ['site_name'=>site_setting('site_name')])); ?></p>
						<button type="submit" name="step" value="basics" class="btn btn-theme btn-arrow w-100 text-left text-uppercase"><?php echo e(trans('messages.driver.submit')); ?>

						</button>
						<span class="mt-3 d-block"><?php echo e(trans('messages.driver.have_an_account')); ?>

							<a href="<?php echo e(route('driver.login')); ?>" class="theme-color"><?php echo e(trans('messages.driver.log_in')); ?></a>
						</span>
						<?php echo e(Form::close()); ?>

					</div>
				</div>
			</div>
		</div>

		<div class="driver-banner mb-5">
			<div class="signup-slider owl-carousel">
				<div class="slide-txt" style="background-image: url('<?php echo e(url('/')); ?>/images/banner1.jpg');">
				</div>
				<div class="slide-txt" style="background-image: url('<?php echo e(url('/')); ?>/images/banner2.jpg');">
				</div>
				<div class="slide-txt" style="background-image: url('<?php echo e(url('/')); ?>/images/banner3.jpg');">
				</div>
			</div>
			<div class="pattern">
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
		<div class="stores-info my-5">
			<div class="container info-top">
				<h1><?php echo e(trans('messages.driver.new_way')); ?> <span><?php echo e(trans('messages.driver.partner')); ?> <?php echo e(site_setting('site_name')); ?></span></h1>
				<div class="row">
					<div class="col-md-4">
						<div class="res-img">
							<img src="<?php echo e(url('/')); ?>/images/icon1.png"/>
						</div>
						<h2><?php echo e(trans('messages.driver.Work_on_schedule')); ?></h2>
						<p><?php echo e(trans('messages.driver.take_trip')); ?></p>
					</div>
					<div class="col-md-4">
						<div class="res-img">
							<img src="<?php echo e(url('/')); ?>/images/icon2.png"/>
						</div>
						<h2><?php echo e(trans('messages.driver.choose_your_wheels')); ?></h2>
						<p><?php echo e(trans('messages.driver.rules')); ?></p>
					</div>
					<div class="col-md-4">
						<div class="res-img">
							<img src="<?php echo e(url('/')); ?>/images/icon3.png"/>
						</div>
						<h2><?php echo e(trans('messages.driver.earn_good_money')); ?></h2>
						<p><?php echo e(trans('messages.driver.make_money')); ?></p>
					</div>
				</div>
				<div class="start-info my-5">
					<h1><?php echo e(trans('messages.driver.ready_to_started')); ?></h1>
					<h2><?php echo e(trans('messages.driver.start_earning')); ?></h2>
					<a href="#driver-form" class="driver-form-link btn btn-theme btn-arrow text-uppercase mt-3 mt-md-4"><?php echo e(trans('messages.driver.get_started')); ?>

					</a>
				</div>
			</div>
		</div>
		<div class="mt-4 py-4 referral">
			<div class="container">
				<div class="referral-offer">
				<!-- 	<p>
						*Offer details for referrals: This offer is only valid for new delivery partners who directly received the email about the guarantee and who complete the required number of delivery trips within 90 days of signing up. Total payout is your total fares not including <?php echo e(site_setting('site_name')); ?> fees and incentives. All delivery trips must be with unique customers and canceled trips do not apply to this offer. Please note that guarantee amounts may vary by city and by vehicle type. We reserve the right to withhold or deduct payments if we believe an error has happened or if anything was fraudulent, illegal or in violation of the driver terms or these referral terms.
					</p> -->
				</div>
			</div>
		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('driver.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>