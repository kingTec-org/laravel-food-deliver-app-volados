<?php
if(request()->device=='mobile'){
$view_device='mobile';
}
?>
<div class="modal fade" id="schedule_modal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h3 class="modal-title"><?php echo e(trans('messages.store.start_new_cart')); ?></h3>
			</div>
			<div class="modal-body">
				<p><?php echo e(trans('messages.store.some_items_may_not_available_for_selected_time')); ?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" data-val="cancel">
					<?php echo e(trans('admin_messages.cancel')); ?>

				</button>
				<button type="button" class="btn btn-primary schedule_modal" data-dismiss="modal" data-val="ok">
					<?php echo e(trans('messages.store.confirm')); ?>

				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="schedule_modal1" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h3 class="modal-title">
					<?php echo e(trans('messages.store.start_new_cart')); ?>

				</h3>
			</div>
			<div class="modal-body">
				<p class="schedule_modal_text">
					<?php echo e(trans('messages.store.some_items_may_not_available_for_selected_time')); ?>

				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" data-val="cancel">
					<?php echo e(trans('admin_messages.cancel')); ?>

				</button>
				<button type="button" class="btn btn-primary schedule_modal1" data-dismiss="modal" data-val="ok">
					<?php echo e(trans('messages.store.confirm')); ?>

				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="schedule_modal_mob" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
				<h3 class="modal-title"><?php echo e(trans('messages.store.start_new_cart')); ?></h3>
			</div>
			<div class="modal-body">
				<p class="schedule_modal_text"><?php echo e(trans('messages.store.some_items_may_not_available_for_selected_time')); ?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" data-val="cancel">
					<?php echo e(trans('admin_messages.cancel')); ?>

				</button>
				<button type="button" class="btn btn-primary schedule_modal_mob" data-dismiss="modal" data-val="ok">
					<?php echo e(trans('messages.store.confirm')); ?>

				</button>
			</div>
		</div>
	</div>
</div>
<?php if(!isset($view_device)): ?>
<footer ng-controller="footer">
	<div class="container">
		<div class="footer-logo d-md-flex align-items-center py-4">
			<a href="<?php echo e(home_page_link()); ?>">
				<img src="<?php echo e(site_setting('1','5')); ?>"/>
			</a>
		</div>
		<div class="footer-links py-4">
			<div class="row">
				<div class="social-links col-12 col-md-3 col-lg-4">
					<div class="select_lang">  
						<?php echo Form::select('language',$language, (Session::get('language')) ? Session::get('language') : $default_language[0]->value, ['class' => 'language-selector footer-select selectpicker', 'aria-labelledby' => 'language-selector-label', 'id' => 'language_footer']); ?>

					</div>
					<ul>
						<?php if(site_setting('join_us_facebook')): ?>
						<li>
							<a href="<?php echo e(site_setting('join_us_facebook')); ?>">
								<i class="icon icon-facebook-letter-logo"></i>
							</a>
						</li>
						<?php endif; ?>

						<?php if(site_setting('join_us_twitter')): ?>
						<li>
							<a href="<?php echo e(site_setting('join_us_twitter')); ?>">
								<i class="icon icon-twitter-logo-silhouette"></i>
							</a>
						</li>
						<?php endif; ?>

						<?php if(site_setting('join_us_youtube')): ?>
						<li>
							<a href="<?php echo e(site_setting('join_us_youtube')); ?>">
								<i class="icon icon-youtube"></i>
							</a>
						</li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="user-links col-12 col-md-4 offset-md-1 col-lg-4 offset-lg-0">
					<ul>				
						<?php if(@$static_pages_changes[0] != ''): ?>							
						<?php $__currentLoopData = $static_pages_changes[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page_url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li>
							<a href="<?php echo e(route('page',$page_url->url)); ?>">
								<?php echo e($page_url->name); ?>

							</a>
						</li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
						<li>
							<a href="<?php echo e(route('help_page',current_page())); ?>">
								<?php echo e(trans('messages.footer.help')); ?>

							</a>
						</li>
					</ul>
				</div>
				<div class="help-links col-12 col-md-3 offset-md-1 col-lg-4 offset-lg-0">
					<ul>
						<?php if(get_current_root()!='store'): ?>
						<li>
							<a href="<?php echo e(route('driver.signup')); ?>">
								<?php echo e(trans('messages.footer.become_a_delivery_partner')); ?>

							</a>
						</li>
						<li>
							<a href="<?php echo e(route('store.signup')); ?>">
								<?php echo e(trans('messages.footer.become_a_store_partner')); ?>

							</a>
						</li>
						<?php endif; ?>

						<?php if(@$static_pages_changes[1] != ''): ?>							
						<?php $__currentLoopData = $static_pages_changes[1]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page_url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<li>
							<a href="<?php echo e(route('page',$page_url->url)); ?>">
								<?php echo e($page_url->name); ?>

							</a>
						</li>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>

		<div class="copyright py-4">
			<div class="row">
				<div class="col-12 text-center">
					<p>© 2019 <a href="https://www.trioangle.com/" class="d-inline-block">Trioangle Technologies</a> Inc.</p>
				</div>
			</div>
		</div>
	</div>
</footer>
<?php endif; ?>
<a href="#top" class="btn-theme scroll-top">
	<i class="icon icon-up-arrow-1"></i>
</a>

<?php $__env->startPush('scripts'); ?>
<script type="text/javascript"></script>
<?php $__env->stopPush(); ?>