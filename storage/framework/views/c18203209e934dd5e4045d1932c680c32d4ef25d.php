<?php $__env->startSection('main'); ?>
<div class="flash-container">
      <?php if(Session::has('message')): ?>
          <div class="alert <?php echo e(Session::get('alert-class')); ?> text-center" role="alert">
              <a href="#" class="alert-close" data-dismiss="alert">&times;</a> <?php echo e(Session::get('message')); ?>

          </div>
      <?php endif; ?>
  </div>
<main id="site-content" role="main" class="log-user driver document-page" ng-controller="document_upload">
	<div class="container">
		<div class="profile mb-5">
			<div class="d-md-flex">
			<?php echo $__env->make('driver.partner_navigation', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<div class="profile-info col-12 col-md-9 col-lg-9">


					<div class="documents">
						<div class="document-row py-4 d-md-flex align-items-center text-center">
							<div class="col-md-5 text-md-left">
								<p><?php echo e(trans('messages.driver.driver_license')); ?><span> - (<?php echo e(trans('messages.driver.back_reverse')); ?>)</span></p>
							</div>
							<div class="col-md-4">
								<img src="<?php echo e($driver_licence_back); ?>" id="doc_back">
							</div>
							<div class="col-md-3 text-md-right">
								<a href="javascript:void(0)" class="btn btn-theme text-capitalize" data-toggle="modal" data-target="#back-document-modal"><?php echo e(trans('messages.driver.upload')); ?></a>
							</div>
						</div>
						<div class="document-row py-4 d-md-flex align-items-center text-center">
							<div class="col-md-5 text-md-left">
								<p><?php echo e(trans('messages.driver.driver_license')); ?> <span>- (<?php echo e(trans('messages.driver.front')); ?>)</span></p>
							</div>
							<div class="col-md-4">
								<img src="<?php echo e($driver_licence_front); ?>" id="doc_front">
							</div>
							<div class="col-md-3 text-md-right">
								<a href="javascript:void(0)" class="btn btn-theme text-capitalize" data-toggle="modal" data-target="#front-document-modal"><?php echo e(trans('messages.driver.upload')); ?></a>
							</div>
						</div>
						<div class="document-row py-4 d-md-flex align-items-center text-center">
							<div class="col-md-5 text-md-left">
								<p><?php echo e(trans('messages.driver.motor_insurance_certificate')); ?></p>
							</div>
							<div class="col-md-4">
								<img src="<?php echo e($driver_insurance); ?>" id="doc_insurance">
							</div>
							<div class="col-md-3 text-md-right">
								<a href="javascript:void(0)" class="btn btn-theme text-capitalize" data-toggle="modal" data-target="#ins-document-modal"><?php echo e(trans('messages.driver.upload')); ?></a>
							</div>
						</div>
						<div class="document-row py-4 d-md-flex align-items-center text-center">
							<div class="col-md-5 text-md-left">
								<p><?php echo e(trans('messages.driver.certificate_of_registration')); ?></p>
							</div>
							<div class="col-md-4">
								<img src="<?php echo e($driver_registeration_certificate); ?>" id="doc_registration">
							</div>
							<div class="col-md-3 text-md-right">
								<a href="javascript:void(0)" class="btn btn-theme text-capitalize" data-toggle="modal" data-target="#reg-document-modal"><?php echo e(trans('messages.driver.upload')); ?></a>
							</div>
						</div>
						<div class="document-row py-4 d-md-flex align-items-center text-center">
							<div class="col-md-5 text-md-left">
								<p><?php echo e(trans('messages.driver.contract_carriage_permit')); ?></p>
							</div>
							<div class="col-md-4">
								<img src="<?php echo e($driver_motor_certiticate); ?>" id="doc_certificate">
							</div>
							<div class="col-md-3 text-md-right">
								<a href="javascript:void(0)" class="btn btn-theme text-capitalize" data-toggle="modal" data-target="#contract-document-modal"><?php echo e(trans('messages.driver.upload')); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="driver modal fade document-popup" id="back-document-modal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?php echo e(trans('messages.driver.driver_license')); ?> - (<?php echo e(trans('messages.driver.back_reverse')); ?>)</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<button class="btn btn-theme w-100 text-uppercase" type="button" ng-click="selectDocument()">
						<i class="icon h4 mr-2 mb-0 icon-z-open-folder"></i>
						<?php echo e(trans('messages.driver.select_file_and_upload')); ?>

					</button>
					<input type="file" ng-model="document_back" style="display:none" accept="image/*" id="document" name='document_back' accept=".jpg,.jpeg,.png" onchange="angular.element(this).scope().documentNameChanged(this)" />
				</div>
			</div>
		</div>
	</div>

	<div class="driver modal fade document-popup" id="front-document-modal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?php echo e(trans('messages.driver.driver_license')); ?> - (<?php echo e(trans('messages.driver.front')); ?>)</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<button class="btn btn-theme w-100 text-uppercase" type="button" ng-click="selectDocument1()">
						<i class="icon h4 mr-2 mb-0 icon-z-open-folder"></i>
						<?php echo e(trans('messages.driver.select_file_and_upload')); ?>

					</button>
					<input type="file" ng-model="document_front" style="display:none" accept="image/*" id="document_front" name='document_front' accept=".jpg,.jpeg,.png" onchange="angular.element(this).scope().documentNameChanged1(this)" />
				</div>
			</div>
		</div>
	</div>

	<div class="driver modal fade document-popup" id="ins-document-modal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?php echo e(trans('messages.driver.motor_insurance_certificate')); ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<button class="btn btn-theme w-100 text-uppercase" type="button" ng-click="selectDocument2()">
						<i class="icon h4 mr-2 mb-0 icon-z-open-folder"></i>
						<?php echo e(trans('messages.driver.select_file_and_upload')); ?>

					</button>
					<input type="file" ng-model="document_insurance" style="display:none" accept="image/*" id="document_insurance" name='document_insurance' accept=".jpg,.jpeg,.png" onchange="angular.element(this).scope().documentNameChanged2(this)" />
				</div>
			</div>
		</div>
	</div>

	<div class="driver modal fade document-popup" id="reg-document-modal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?php echo e(trans('messages.driver.certificate_of_registration')); ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<button class="btn btn-theme w-100 text-uppercase" type="button" ng-click="selectDocument3()">
						<i class="icon h4 mr-2 mb-0 icon-z-open-folder"></i>
						<?php echo e(trans('messages.driver.select_file_and_upload')); ?>

					</button>
					<input type="file" ng-model="document_register" style="display:none" accept="image/*" id="document_register" name='document_register' accept=".jpg,.jpeg,.png" onchange="angular.element(this).scope().documentNameChanged3(this)" />
				</div>
			</div>
		</div>
	</div>

	<div class="driver modal fade document-popup" id="contract-document-modal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title"><?php echo e(trans('messages.driver.contract_carriage_permit')); ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">
							<i class="icon icon-close-2"></i>
						</span>
					</button>
				</div>
				<div class="modal-body">
					<button class="btn btn-theme w-100 text-uppercase" type="button" ng-click="selectDocument4()">
						<i class="icon h4 mr-2 mb-0 icon-z-open-folder"></i>
						<?php echo e(trans('messages.driver.select_file_and_upload')); ?>

					</button>
					<input type="file" ng-model="document_certificate" style="display:none" accept="image/*" id="document_certificate" name='document_certificate' accept=".jpg,.jpeg,.png" onchange="angular.element(this).scope().documentNameChanged4(this)" />
				</div>
			</div>
		</div>
	</div>

</main>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('driver.template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>