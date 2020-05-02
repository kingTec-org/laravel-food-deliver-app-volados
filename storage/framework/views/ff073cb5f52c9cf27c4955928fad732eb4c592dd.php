<?php $__env->startSection('main'); ?>
<div class="flash-container">
	<?php if(Session::has('message')): ?>
	<div class="alert <?php echo e(Session::get('alert-class')); ?> text-center" role="alert">
		<a href="#" class="alert-close" data-dismiss="alert">&times;</a> <?php echo e(Session::get('message')); ?>

	</div>
	<?php endif; ?>
</div>
<main id="site-content" role="main">
	<div class="signup-page" ng-controller="store_signup">
		<div class="banner-info">
			<div class="container">
				<div class="banner-txt">
					<div class="col-md-5 col-lg-6 p-0">
						<h1><?php echo e(trans('messages.store.fast_way')); ?><span><?php echo e(trans('messages.store.grow_bussiness')); ?></span>
						</h1>
					</div>
					<div class="banner-form">
						<h2><?php echo e(trans('messages.store.partner')); ?></h2>
						<?php echo Form::open(['url'=>route('store.signup'),'method'=>'post','class'=>'mt-4' , 'id'=>'signup_form']); ?>

						<?php echo csrf_field(); ?>
						<div class="form-group">
							<?php echo Form::text('name','',['id'=>'name','placeholder' => trans('messages.store.store_name')]); ?>

						</div>

						<div class="form-group">
							<?php echo Form::text('address','',['id'=>'location_val','placeholder' => trans('messages.store.address')]); ?>

							<p class="location_error text-danger"></p>
						</div>

						<div class="form-group">
							<div class="location">
								<?php echo Form::text('city','',['id'=>'city','placeholder' => trans('messages.store.city'),'data-error-placement'=>"container",'data-error-container'=>'.city-error']); ?>

							</div>
							<span class="city-error"></span>
						</div>

						<div class="form-group">
							<div class="row">
								<div class='col-md-6'>
									<?php echo Form::text('first_name','',['placeholder' => trans('messages.store.first_name')]); ?>

								</div>
								<div class='col-md-6 mt-3 mt-md-0'>
									<?php echo Form::text('last_name','',['placeholder' => trans('messages.store.last_name')]); ?>

								</div>
							</div>
						</div>

						<div class="form-group col-12">
							<div class="row">
								<div class="d-flex w-100">
									<div class="select mob-select col-md-3">
										<span class="phone_code">+<?php echo e(@session::get('phone_code')); ?></span>
										<select id="phone_code" name="country" class="form-control">
											<?php $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($country->phone_code); ?>" <?php echo e($country->phone_code == @session::get('phone_code') ? 'selected' : ''); ?> ><?php echo e($country->name); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
									</div>
									<?php echo Form::text('mobile_number','',['placeholder' => trans('messages.store.phone_number'),'class' =>'','data-error-placement'=>'container','data-error-container'=>'.mobile-number-error']); ?>

								</div>
								<span class="mobile-number-error"></span>
							</div>
						</div>

						<div class="form-group">
							<?php echo Form::text('email','',['placeholder' => trans('messages.store.email')]); ?>

							<span class="text-danger"><?php echo e($errors->first('email')); ?></span>
						</div>

						<div class="form-group">
							<div class="select">
								<?php echo Form::select('category', $category, null, ['class' => 'form-control','placeholder' => trans('messages.store.type_of_category'),'data-error-placement'=>'container','data-error-container'=>'.category-error']); ?>

							</div>
							<span class="category-error"></span>
						</div>
						<div class="form-group">
							<?php echo Form::password('password',['placeholder' => trans('messages.store.password') , 'id'=> 'password']); ?>

						</div>
						<div class="form-group">
							<?php echo Form::password('conform_pasword',['placeholder' => trans('messages.store.confirm_password'),'id' => 'conform_password']); ?>

						</div>

						<div style="display:none;">
							<?php echo Form::text('country_code','',['id'=>'country_code']); ?>

							<?php echo Form::text('postal_code','',['id'=>'postal_code']); ?>

							<?php echo Form::text('state','',['id'=>'state']); ?>

							<?php echo Form::text('street','',['id'=>'address_line_1']); ?>

							<?php echo Form::text('latitude','',['id'=>'latitude']); ?>

							<?php echo Form::text('longitude','',['id'=>'longitude']); ?>

						</div>

						<button type="submit" class="btn btn-theme w-100 text-left text-uppercase"><?php echo e(trans('messages.store.submit')); ?>

							<i class="icon icon-right-arrow float-right"></i>
						</button>
					<!-- 	<span class="mt-3 d-block">
							After you submit this form, a member of the <?php echo e(site_setting('site_name')); ?> team will get in touch with you.
						</span> -->
						<?php echo Form::close(); ?>

					</div>
				</div>
			</div>
		</div>

		<div class="signup-slider mb-5 owl-carousel">
			<?php $__currentLoopData = $slider; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
			<div class="slide-txt" style="background-image: url('<?php echo e($image->slider_image); ?>');">
				<div class="container">
					<div class="col-md-5 col-lg-6 p-0">
						<h1><?php echo e($image->title); ?></h1>
						<p><?php echo e($image->description); ?></p>
					</div>
				</div>
			</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>

		<div class="stores-info my-5">
			<div class="container">
				<div class="row">
					<div class="col-md-4">
						<div class="res-img">
							<img src="<?php echo e(url('/')); ?>/images/business.png"/>
						</div>
						<h2><?php echo e(trans('messages.store.more_business')); ?></h2>
						<p><?php echo e(trans('messages.store.impact_your_business', ['site_name'=>site_setting('site_name')] )); ?></p>
					</div>
					<div class="col-md-4">
						<div class="res-img">
							<img src="<?php echo e(url('/')); ?>/images/route.png"/>
						</div>
						<h2><?php echo e(trans('messages.store.deliver_faster')); ?></h2>
						<p><?php echo e(trans('messages.store.item_your_customers', ['site_name'=>site_setting('site_name')] )); ?></p>
					</div>
					<div class="col-md-4">
						<div class="res-img">
							<img src="<?php echo e(url('/')); ?>/images/support.png"/>
						</div>
						<h2><?php echo e(trans('messages.store.partner_with_professionals')); ?></h2>
						<p><?php echo e(trans('messages.store.promote_your_menu', ['site_name'=>site_setting('site_name')] )); ?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="profile-slider owl-carousel">
			<div class="item d-md-flex align-items-center flex-md-row-reverse">
				<div class="slider-img col-md-5" style="background-image: url('<?php echo e(url('/')); ?>/images/banner1.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4><?php echo e(trans('messages.store.general_manager_feedback', ['site_name'=>site_setting('site_name')])); ?></h4>
						<p><strong><?php echo e(trans('messages.store.general_manager_name')); ?></strong>
							<span><?php echo e(trans('messages.store.general_manager')); ?></span>
						</p>
					</div>
				</div>
			</div>
			<div class="item d-md-flex align-items-center flex-md-row-reverse">
				<div class="slider-img col-md-5" style="background-image: url('<?php echo e(url('/')); ?>/images/banner2.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4><?php echo e(trans('messages.store.owner_feedback', ['site_name'=>site_setting('site_name')])); ?></h4>
						<p><strong><?php echo e(trans('messages.store.owner_name')); ?></strong>
							<span><?php echo e(trans('messages.store.owner')); ?></span>
						</p>
					</div>
				</div>
			</div>
			<div class="item d-md-flex align-items-center flex-md-row-reverse">
				<div class="slider-img col-md-5" style="background-image: url('<?php echo e(url('/')); ?>/images/banner3.jpg');">
				</div>
				<div class="slide-txt align-self-stretch my-md-5 d-flex align-items-center col-md-7">
					<div class="slide-txt-in">
						<h4><?php echo e(trans('messages.store.chef_feedback', ['site_name'=>site_setting('site_name')])); ?></h4>
						<p>
							<strong><?php echo e(trans('messages.store.chef_name')); ?></strong>
							<span><?php echo e(trans('messages.store.chef')); ?></span>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
	Lang.setLocale("<?php echo (Session::get('language')) ? Session::get('language') : $default_language[0]->value; ?>");
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>