<?php $__env->startSection('main'); ?>
<main id="site-content" role="main" ng-controller="preparation_time">
	<div class="partners">
		<?php echo $__env->make('store.navigation', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div class="pickup-times my-md-4 panel-content">
			<h1><?php echo e(trans('messages.store_dashboard.pickup_times')); ?></h1>
			<p><?php echo e(trans('messages.store_dashboard.we_aim_to_have_someone_pick')); ?> <?php echo e(trans('messages.store_dashboard.when_courier_arrives_at_your_store')); ?></p>
			<h6 class="my-4 theme-color">
				<?php echo e(trans('messages.store_dashboard.tips_for_accurate_pickups')); ?>

			</h6>
			<div class="mt-4" ng-init="day_name =<?php echo e(json_encode(day_name())); ?>;preparation_timing=<?php echo e(json_encode($preparation)); ?>;max_time= <?php echo e($max_time); ?> ? <?php echo e($max_time); ?>  :  50" >
				<h2><?php echo e(trans('messages.store_dashboard.average_item_preparation_times')); ?></h2>
				<p><?php echo e(trans('messages.store_dashboard.let_know_how_long_it_usually_takes')); ?></p>
				<form action="<?php echo e(route('store.update_preparation_time')); ?>" method="post" id="store_preparation_time">
					<?php echo csrf_field(); ?>
					<div class="my-3 d-flex align-items-center add-times justify-content-between">
						<input type="text" name="overall_max_time" ng-model="max_time">
						<span class="d-inline-block ml-1"><?php echo e(trans('messages.store_dashboard.minutes')); ?></span>
						<a href="javascript:void(0)" ng-click="default_decrement()">
							<i class="icon icon-remove ml-3"></i>
						</a>
						<a href="javascript:void(0)" ng-click="default_increment()">
							<i class="icon icon-add ml-3"></i>
						</a>
					</div>
					<div class="my-3 d-md-flex align-items-start menu-view added-times-row" ng-repeat="preparation in preparation_timing">
						<div class="d-flex align-items-center add-times">
							<input type="text" name="max_time[]" ng-model="preparation_timing[$index].max_time"  readonly>
							<span class="d-inline-block ml-1 mr-auto"><?php echo e(trans('messages.store_dashboard.minutes')); ?></span>
							<a href="javascript:void(0)" ng-click="decrement($index)">
								<i class="icon icon-remove ml-3"></i>
							</a>
							<a href="javascript:void(0)" ng-click="increment($index)">
								<i class="icon icon-add ml-3"></i>
							</a>
						</div>
						<input type="hidden" name="id[]" value="{{preparation.id}}">
						<div class="select-day">
							<div class="select ml-md-3">
								<select name="day[]" ng-model="preparation_timing[$index].day" id="select_day_{{$index}}">
									<option value=""><?php echo e(trans('messages.store_dashboard.select_a_day')); ?></option>
									<option value="{{key}}" ng-selected="preparation.day==key" ng-repeat="(key,value) in day_name track by $index">{{value}}</option>
									<!-- ng-if="( key | checkKeyValueUsedInStack : 'day': preparation_timing) || preparation.day==key " -->
								</select>
							</div>
						</div>

						<div class="added-times d-md-flex ml-3 align-items-start">
							<div class="d-flex align-items-start justify-content-between select-time">
								<div class="select">
									<?php echo Form::select('from_time[]',time_data('time'),'', ['ng-model'=>'preparation.from_time', 'id'=>'from_time_{{$index}}','class'=>'from_time', 'data-index'=>'{{$index}}','placeholder'=>trans('messages.store_dashboard.select'),'data-end_time'=>'{{preparation.to_time}}']);; ?>

								</div>
								<span class="m-2"><?php echo e(trans('messages.store.to')); ?></span>
								<div class="select">
									<?php echo Form::select('to_time[]',time_data('time'),'', ['ng-model'=>'preparation.to_time','id'=>'to_time_{{$index}}','class'=>'to_time ' ,'data-index'=>'{{$index}}','placeholder'=>trans('messages.store_dashboard.select')]);; ?>

								</div>
							</div>
							<div class="d-flex align-items-start mt-2 mt-md-0 select-status">
								<div class="select ml-md-3">
									<?php echo Form::select('status[]',['0'=>trans('admin_messages.inactive'),'1'=>trans('admin_messages.active')],'', ['ng-model'=>'preparation.status','id'=>'status{{$index}}','class'=>'status ' ,'data-index'=>'{{$index}}','placeholder'=>trans('messages.store_dashboard.select')]);; ?>

								</div>
								<input type="hidden" name="preparation_time" id="preparation_time" value="{{preparation_timing}}">
								<i  class="icon icon-rubbish-bin d-inline-block m-2 mr-0 text-danger" ng-click="remove_preparation($index)"></i>
							</div>
						</div>
					</div>
					<div class="mt-4">
						<a href="javascript:void(0)" class="theme-color" ng-click="add_preparation_time()" ng-show="preparation_timing.length < 7">
							<i class="icon icon-add mr-2"></i>
							<?php echo e(trans('messages.store.add_more')); ?>

						</a>
						<div class="mt-3">
							<button type="submit" class="btn btn-theme" id="timing_save"><?php echo e(trans('messages.store_dashboard.save')); ?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>