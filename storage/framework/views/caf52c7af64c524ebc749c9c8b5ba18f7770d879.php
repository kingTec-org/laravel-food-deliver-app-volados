<?php $__env->startSection('main'); ?>
<main id="site-content" role="main" ng-controller="store_dashboard">
	<div class="partners">

		<?php if($user->status_text!='active' && $user->status_text!='inactive'): ?>
		<div class="verification-steps mt-3 mb-5 my-md-5">
			<div class="verify-head d-flex align-items-center">
				<i class="icon icon-thumbs-up mr-3"></i>
				<h2><?php echo e(trans('messages.store_dashboard.verification_step')); ?></h2>
			</div>

			<div class="verify-steps">
				<ul>
					<li class="<?php echo e($document? 'completed':''); ?>">
						<a href="<?php echo e(route('store.profile','#document')); ?>">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							<?php echo e(trans('messages.store_dashboard.add_document')); ?>

						</a>
					</li>
					<li class="<?php echo e($open_time? 'completed':''); ?>">
						<a href="<?php echo e(route('store.profile','#open_time')); ?>">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							<?php echo e(trans('messages.store_dashboard.add_open_time')); ?>

						</a>
					</li>
					<li class="<?php echo e($profile_step? 'completed':''); ?>">
						<a href="<?php echo e(route('store.profile')); ?>">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							<?php echo e(trans('messages.store_dashboard.complete_profile')); ?>

						</a>
					</li>
					<li class="<?php echo e($payout_preference? 'completed':''); ?>">
						<a href="<?php echo e(route('store.payout_preference')); ?>">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							<?php echo e(trans('messages.store_dashboard.add_payout_preference')); ?>

						</a>
					</li>
					<li class="<?php echo e($menu? 'completed':''); ?>">
						<a href="<?php echo e(route('store.menu')); ?>">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							<?php echo e(trans('messages.store_dashboard.add_menu')); ?>

						</a>
					</li>
					<li class="<?php echo e(($menu && $payout_preference && $profile_step && $open_time && $document)? '':'d-none'); ?>">
						<a href="javascript:void(0)">
							<i class="icon icon-success"></i>
							<i class="icon icon-cancel-button"></i>
							<?php echo e(trans('messages.store_dashboard.waiting_for_approval')); ?>

						</a>
					</li>
				</ul>
			</div>
		</div>
		<?php endif; ?>
		<?php echo $__env->make('store.navigation', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<?php echo Charts::assets(); ?>

		<div id="sales">
			<div class="d-md-flex align-items-center justify-content-between">
				<h1 class="title"><?php echo e(trans('messages.store_dashboard.sales')); ?></h1>
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="weekly-tab" data-toggle="tab" href="#weekly" role="tab" aria-controls="weekly" aria-selected="true">7 <?php echo e(trans('messages.store_dashboard.days')); ?></a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="monthly-tab" data-toggle="tab" href="#monthly" role="tab" aria-controls="monthly" aria-selected="false">30 <?php echo e(trans('messages.store_dashboard.days')); ?></a>
					</li>
				</ul>
			</div>
			<div class="panel-content my-3 my-md-5">
				<div class="tab-pane fade active" id="weekly" role="tabpanel" aria-labelledby="weekly-tab">
					<div class="d-md-flex align-items-center justify-content-between">
						<div class="net-pay col-md-4">
							<h2>$<?php echo e($last_seven_total_payouts); ?></h2>
							<p><?php echo e(trans('messages.store_dashboard.net_payout')); ?></p>
						</div>

						<div class="net-chart col-md-8 mt-5 mt-md-0">
							<?php if(isset($seven_chart)): ?>
								<center>
									<?php echo $seven_chart->render(); ?>

								</center>
							<?php else: ?>
							<h3><?php echo e(trans('messages.store_dashboard.last_seven_days')); ?></h3>
							<?php endif; ?>
						</div>
					</div>
					<?php if(count($top_sale_thirty_days)>0): ?>
					<div class="menu-items mt-5 dashboard_menu">
						<h3><?php echo e(trans('messages.store_dashboard.selling_menu_items')); ?></h3>
						<ul class="clearfix mt-3">
							<?php $__currentLoopData = $top_sale_saven_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top_saven): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<li><span><?php echo e($top_saven->total_times); ?></span><?php echo e($top_saven->menu_item->name); ?></li>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</ul>
					</div>
					<?php endif; ?>
				</div>
				<div class="tab-pane fade active " id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
					<div class="d-md-flex align-items-center justify-content-between">
						<div class="net-pay col-md-4">
							<h2>$<?php echo e($last_thirty_total_payouts); ?></h2>
							<p><?php echo e(trans('messages.store_dashboard.net_payout')); ?></p>
						</div>
						<div class="net-chart col-md-8 mt-5 mt-md-0">
							<?php if(isset($thirty_chart)): ?>
								<center>
									<?php echo $thirty_chart->render(); ?>

								</center>
							<?php else: ?>
							<h3><?php echo e(trans('messages.store_dashboard.last_thirty_days')); ?></h3>
							<?php endif; ?>

						</div>
					</div>
					<?php if(count($top_sale_thirty_days)>0): ?>
					<div class="menu-items mt-5 dashboard_menu">
						<h3><?php echo e(trans('messages.store_dashboard.selling_menu_items')); ?></h3>
						<ul class="clearfix mt-3">
							<?php $__currentLoopData = $top_sale_thirty_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $top_saven): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
							<li><span><?php echo e($top_saven->total_times); ?></span><?php echo e($top_saven->menu_item->name); ?></li>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</ul>
					</div>
					<?php endif; ?>
				</div>

					</div><!--
					<div class="my-5 select col-12 col-md-6 col-lg-4 p-0">
						<select>
							<option>Past month</option>
							<option>Past week</option>
							<option>Yesterday, 08/28</option>
						</select>
					</div> -->
				</div>
				<div id="service">
					<h1 class="title"><?php echo e(trans('messages.store_dashboard.service_quality')); ?></h1>
					<div class="mt-3">
						<p class="light-color"><?php echo e(trans('messages.store_dashboard.speed_and_convenience',['site_name'=> site_setting('site_name')])); ?></p>
					</div>
					<div class="panel-content mt-3 my-md-5">
						<div class="text-right">
							<p class="light-color"><?php echo e(trans('messages.store_dashboard.based_on_past_30_days')); ?></p>
						</div>

						<div class="service-row">
							<h3><?php echo e(trans('messages.profile_orders.orders')); ?></h3>
							<div class="mt-4 d-block row d-lg-flex align-items-center">
								<div class="col-12 col-lg-6">
									<div class="accepted-hr d-md-flex align-items-center row">
										<div class="col-12 col-md-4">
											<p><?php echo e(trans('messages.store_dashboard.accept_orders')); ?></p>
										</div>
										<div class="col-12 col-md-7 offset-md-1 d-md-flex new_bar align-items-center">
											<div class="bar-info w-100 pr-md-3">
												<span class="bar"></span>
												<span style="width: <?php echo e($accepted_rating); ?>%" class="bar bar-percentage <?php echo e((($accepted_rating >= 80) ?'bar-green':(($accepted_rating >= 50)?'bar-yellow':'bar-red'))); ?> "></span>
											</div>
											<p class="text-nowrap"><?php echo e($accepted_rating); ?>%</p>
										</div>
									</div>
									<div class="expected-hr d-md-flex align-items-center row mt-4 mt-md-0">
										<div class="col-12 col-md-4">
											<p><?php echo e(trans('messages.store_dashboard.cancel_orders')); ?></p>
										</div>
										<div class="col-12 col-md-7 offset-md-1 d-md-flex new_bar align-items-center">
											<div class="bar-info w-100 pr-3">
												<span class="bar"></span>
												<span style="width: <?php echo e($canceled_rating); ?>%" class="bar bar-percentage <?php echo e((($canceled_rating >= 80) ?'bar-green':(($canceled_rating >= 50)?'bar-yellow':'bar-red'))); ?> "></span>
											</div>
											<p class="text-nowrap"><?php echo e($canceled_rating); ?>%</p>
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-6 mt-4 mt-lg-0">
									<?php if($accepted_rating == 100): ?>
									<div class="hrs-info pd-15">
										<h4><?php echo e(trans('messages.store_dashboard.thanks_for_being_reliable')); ?></h4>
										<p><?php echo e(trans('messages.store_dashboard.you_fulfilling_all_orders')); ?></p>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div id="customer-satisfaction">
					<div class="d-md-flex justify-content-between align-items-center">
						<h1 class="title"><?php echo e(trans('messages.store_dashboard.customer_satisfaction')); ?></h1>
						<p class="light-color"><?php echo e(trans('messages.store_dashboard.based_on_past_30_days')); ?></p>
					</div>
					<div class="panel-content my-3 my-md-5">
						<div class="service-row">
							<div class="d-block row d-lg-flex align-items-center">
								<div class="col-12 col-lg-6">
									<h3><?php echo e($retauarnt_rating); ?>%</h3>
									<p><?php echo e(trans('messages.store_dashboard.satisfaction_rating')); ?></p>
									<div class="cust-hr d-md-flex align-items-center row">
										<div class="col-12 d-md-flex new_bar align-items-center">
											<!-- <p class="text-nowrap d-block d-md-none text-right mt-3">100%</p> -->
											<div class="w-100 pr-md-3">
												<div class="bar-info">
													<span class="bar"></span>
													<span style="width: <?php echo e($retauarnt_rating); ?>%" class="bar bar-percentage <?php echo e((($retauarnt_rating >= 80) ?'bar-green':(($retauarnt_rating >= 50)?'bar-yellow':'bar-red'))); ?> "></span>
												</div>
											</div>
											<p class="text-nowrap d-md-block"><?php echo e($retauarnt_rating); ?>%</p>
										</div>
									</div>
								</div>
								<div class="col-12 col-lg-6 mt-4 mt-lg-0">
									<div class="hrs-info">
										<h5><?php echo e(trans('messages.store_dashboard.see_what_people_are_saying')); ?></h5>
										<p class="light-color"><?php echo e(trans('messages.store_dashboard.customers_like_your_item')); ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ratings-table mb-4">
					<h5><?php echo e(trans('messages.store_dashboard.ratings')); ?></h5>
					<div class="table-responsive">
						<table>
							<thead>
								<tr>
									<th><?php echo e(trans('messages.store_dashboard.item')); ?></th>
									<th><?php echo e(trans('messages.store_dashboard.satisfaction_rating')); ?></th>
									<th><?php echo e(trans('messages.store_dashboard.negative_feedback')); ?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php $__currentLoopData = $review_column; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review_id => $reviews): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td><?php echo e($reviews['name']); ?></td>
									<td>
										<div class="d-flex align-items-center">
											<div class="bar">
												<span style="width: <?php echo e($reviews['prasantage']); ?>%" class="bar-process <?php if($reviews['prasantage']==100): ?>  green <?php elseif($reviews['prasantage']>50): ?> yellow <?php elseif($reviews['prasantage']<50): ?> red <?php endif; ?>"></span>
											</div>
											<span class="text-nowrap ml-3"><?php echo e($reviews['prasantage']); ?>% (<?php echo e($reviews['count_thumbs']); ?>)</span>
										</div>
									</td>
									<td>
										<div class="feedbacks">
											<?php if(isset($reviews['issues_column'])): ?>
											<?php $__currentLoopData = $reviews['issues_column']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<label>
												<span><?php echo e($key); ?></span>
												<span><?php echo e($issue); ?></span>
											</label>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											<?php endif; ?>
										</div>
									</td>
									<td class="text-right" >
										<input type="hidden" name="comments" value="<?php echo e($reviews['review_comments']); ?>" id="comments_<?php echo e($review_id); ?>">
										<a href="javascript:void(0)"><i ng-click="show_comments(<?php echo e($review_id); ?>)"  class="icon icon-comment-black-rectangular-speech-bubble-interface-symbol"></i></a>
									</td>
								</tr>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
								<?php if(count($review_column) < 1): ?>
								<tr>
									<td colspan="4" class="text-center"> <?php echo e(trans('messages.store_dashboard.no_item_rating_found')); ?></td>
								</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</main>

		<!-- Add category model !-->
		<div class="modal fade" id="comments_modal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<i class="icon icon-close-2"></i>
						</button>
					</div>
					<div class="modal-body">
						<form class="form_valitate">
							<div class="form-group d-flex menu-name">
								<ul class="comment_list dotted">

									<li>sadfaszd</li>
								</ul>
							</div>
							<div class="mt-3 pt-4 modal-footer px-0 text-right">
								<button data-dismiss="modal" type="cancel" class="btn btn-primary theme-color"><?php echo e(trans('messages.store_dashboard.close')); ?></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<!-- End Add category model !-->

		<?php $__env->stopSection(); ?>
		<?php $__env->startPush('scripts'); ?>
		<script type="text/javascript">

		</script>
		<?php $__env->stopPush(); ?>
<?php echo $__env->make('template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>