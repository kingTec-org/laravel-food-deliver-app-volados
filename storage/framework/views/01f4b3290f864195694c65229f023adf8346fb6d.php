	<div class="profile-img text-center col-12 col-md-3 col-lg-3 d-none d-md-block">
					<img src="<?php echo e(@$profile_image); ?>" class="profile_picture" />
					<?php if($driver_details): ?>
						<h4><?php echo e($driver_details->name); ?></h4>
					<?php endif; ?>
					<div class="pro-nav">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item">
								<a class="nav-link" href="<?php echo e(route('driver.profile')); ?>"><?php echo e(trans('messages.profile.profile')); ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo e(route('driver.payment')); ?>"><?php echo e(trans('messages.profile.earnings')); ?></a>
							</li>
							<!-- <li class="nav-item">
								<a class="nav-link" href="<?php echo e(route('driver.invoice')); ?>">Invoice</a>
							</li> -->
							<li class="nav-item ">
								<a class="nav-link" href="<?php echo e(route('driver.trips')); ?>"><?php echo e(trans('messages.profile.my_trips')); ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo e(route('driver.logout')); ?>"><?php echo e(trans('messages.profile.log_out')); ?></a>
							</li>
						</ul>
					</div>
				</div>
</nav>