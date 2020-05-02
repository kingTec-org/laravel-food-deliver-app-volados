<?php $__env->startSection('main'); ?>
<div class="content" ng-controller="store">
  <div class="container-fluid">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-rose card-header-text">
          <div class="card-text">
            <h4 class="card-title"><?php echo e($form_name); ?></h4>
          </div>
        </div>
        <div class="card-body">
          <?php echo Form::open(['url' => $form_action, 'class' => 'form-horizontal','id'=>'add_user_form','files'=>'true']); ?>

          <?php echo csrf_field(); ?>
          <div class="row">
            <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.full_name'); ?><span class="required text-danger">*</span></label>
            <div class="col-sm-10">
              <div class="form-group">
               <?php echo Form::text('name',@$store->name, ['class' => 'form-control', 'id' => 'input_user_name',]); ?>

               <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
             </div>
           </div>
         </div>
         <div class="row">
          <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.email'); ?><span class="required text-danger">*</span></label>
          <div class="col-sm-10">
            <div class="form-group">
             <?php echo Form::text('email',@$store->email, ['class' => 'form-control', 'id' => 'input_email',]); ?>

             <span class="text-danger"><?php echo e($errors->first('email')); ?></span>
           </div>
         </div>
       </div>
       <div class="row">
        <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.password'); ?>
          <?php if(@$store->email==''): ?>
          <span class="required text-danger">*</span>
          <?php endif; ?>
        </label>
        <div class="col-sm-10">
          <div class="form-group">
            <?php echo Form::text('password','', ['class' => 'form-control', 'id' => 'input_password',]); ?>

            <span class="text-danger"><?php echo e($errors->first('password')); ?></span>
          </div>
        </div>
      </div>

      <div class="row">
        <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.phone_no'); ?><span class="required text-danger">*</span></label>
        <div class="col-sm-2">
          <div class="form-group">
            <?php
              $country_code=(request()->old('phone_country_code'))?request()->old('phone_country_code'):@$store->country_code;
            ?>
              <select id="phone_code_country" name="phone_country_code" class="form-control">
                                <?php $__currentLoopData = $country; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($country->phone_code); ?>" <?php echo e($country->phone_code == $country_code ? 'selected' : ''); ?> ><?php echo e($country->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
           <span class="text-danger"><?php echo e($errors->first('phone_country_code')); ?></span>
         </div>
       </div>
       <div class="col-sm-2">
        <div class="form-group">
         <?php echo Form::text('text',@$store->country_code?'+'.$store->country_code:'', ['readonly'=>'readonly','class'=>'form-control','id'=>'apply_country_code']);; ?>

       </div>
     </div>
     <div class="col-sm-6">
      <div class="form-group">

       <?php echo Form::text('mobile_number',@$store->mobile_number, ['class' => 'form-control', 'id' =>'input_mobile_number','placeholder'=>trans('admin_messages.phone_no')]); ?>

       <span class="text-danger"><?php echo e($errors->first('mobile_number')); ?></span>
     </div>
   </div>
 </div>
 <div class="row">
  <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.date_of_birth'); ?><span class="required text-danger">*</span></label>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo Form::text('date_of_birth',set_date_on_picker(@$store->date_of_birth), ['class' => 'form-control datepickerdob', 'id' => 'input_password',]); ?>

      <span class="text-danger"><?php echo e($errors->first('convert_dob')); ?></span>
    </div>
  </div>
</div>

<div class="row">
  <label class="col-sm-2 col-form-label">User status<span class="required text-danger">*</span></label>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo Form::select('user_status',['1'=>trans('admin_messages.active'),'0'=>trans('admin_messages.inactive'),'4'=>trans('admin_messages.pending'),'5'=>trans('admin_messages.waiting_for_approval')],@$store->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']);; ?>

      <span class="text-danger"><?php echo e($errors->first('user_status')); ?></span>
    </div>
  </div>
</div>
<div class="row">
  <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.store_name'); ?><span class="required text-danger">*</span></label>
  <div class="col-sm-10">
    <div class="form-group">
     <?php echo Form::text('store_name',@$store->store->name, ['class' => 'form-control', 'id' => 'input_store_name',]); ?>

     <span class="text-danger"><?php echo e($errors->first('store_name')); ?></span>
   </div>
 </div>
</div>
<div class="row">
  <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.store_description'); ?><span class="required text-danger">*</span></label>
  <div class="col-sm-10">
    <div class="form-group">
     <?php echo Form::text('store_description',@$store->store->description, ['class' => 'form-control', 'id' => 'input_store_description',]); ?>

     <span class="text-danger"><?php echo e($errors->first('store_description')); ?></span>
   </div>
 </div>
</div>



<div class="row" ng-init="
  country='<?php echo e(@$store->user_address->country); ?>';
  postal_code='<?php echo e((request()->old('postal_code'))?request()->old('postal_code'):@$store->user_address->postal_code); ?>';
  city='<?php echo e((request()->old('city'))?request()->old('city'):@$store->user_address->city); ?>';
  state='<?php echo e((request()->old('state'))?request()->old('state'):@$store->user_address->state); ?>';
  address_line_1='<?php echo e((request()->old('street'))?request()->old('street'):@$store->user_address->street); ?>';
  latitude='<?php echo e((request()->old('latitude'))?request()->old('latitude'):@$store->user_address->latitude); ?>';
  longitude='<?php echo e((request()->old('longitude'))?request()->old('longitude'):@$store->user_address->longitude); ?>';
  country_code='<?php echo e((request()->old('country_code'))?request()->old('country_code'):@$store->user_address->country_code); ?>';">
  <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.address'); ?><span class="required text-danger">*</span></label>

  <div class="col-sm-10">
    <div class="form-group">
     <?php echo Form::text('address',@$store->user_address->address,['id'=>'location_val','class'=>'form-control']); ?>

     <span class="text-danger"><?php echo e($errors->first('address')); ?></span>

   </div>
 </div>
</div>
<div class="d-none">
  <?php echo Form::text('country_code','',['value'=>'','id'=>'addresss_country_code','ng-model'=>'country_code']); ?>

  <?php echo Form::text('postal_code','',['value'=>'','id'=>'addresss_postal_code','ng-model'=>'postal_code']); ?>

  <?php echo Form::text('city','',['value'=>'','id'=>'addresss_city','ng-model'=>'city']); ?>

  <?php echo Form::text('state','',['value'=>'','id'=>'addresss_state','ng-model'=>'state']); ?>

  <?php echo Form::text('street','',['value'=>'','id'=>'addresss_address_line_1','ng-model'=>'address_line_1']); ?>

  <?php echo Form::text('latitude','',['value'=>'','id'=>'addresss_latitude','ng-model'=>'latitude']); ?>

  <?php echo Form::text('longitude','',['value'=>'','id'=>'addresss_longitude','ng-model'=>'longitude']); ?>

</div>
<div class="row">
  <label class="col-md-2 col-form-label">
   <?php echo app('translator')->getFromJson('admin_messages.banner_image'); ?>
   <span class="required text-danger">*</span>
 </label>
 <div class="col-md-5 pt-md-4">
  <div class="fileinput fileinput-new" data-provides="fileinput">
    <div class="fileinput-new thumbnail">
      <img src="<?php if(isset($store->store->store_image)): ?><?php echo e($store->store->store_image); ?><?php else: ?><?php echo e(getEmptyStoreImage()); ?><?php endif; ?>" alt="...">
    </div>
    <div class="fileinput-preview fileinput-exists thumbnail"></div>
    <div>
      <span class="btn btn-rose btn-round btn-file">
        <span class="fileinput-new"><?php echo app('translator')->getFromJson('admin_messages.select_image'); ?></span>
        <span class="fileinput-exists"><?php echo app('translator')->getFromJson('admin_messages.change'); ?></span>
        <?php echo Form::file('banner_image',['class' => 'form-control', 'id' => 'input_banner_image']); ?>

      </span>
      <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> <?php echo app('translator')->getFromJson('admin_messages.remove'); ?></a>
    </div>
    <span class="text-danger"><?php echo e($errors->first('banner_image')); ?></span>
  </div>
</div>
</div>

<div class="row">
  <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.price_rating'); ?><span class="required text-danger">*</span></label>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo Form::select('price_rating',priceRatingList(),@$store->store->price_rating, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']);; ?>

      <span class="text-danger"><?php echo e($errors->first('price_rating')); ?></span>
    </div>
  </div>
</div>
<div class="row">
  <label class="col-md-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.category'); ?><span class="required text-danger">*</span></label>
  <div class="col-md-9">
    <div class="form-group form-check-group category row">
      <?php $__currentLoopData = $category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category_key => $category_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="form-check col-md-6">
        <label class="form-check-label">
         <?php echo Form::checkbox('category[]',$category_key,in_array($category_key,$store_category), ['class'=>'form-check-input','data-error-placement'=>"container" ,'data-error-container'=>".category_error"]);; ?>

         <span class="form-check-sign">
          <span class="check"></span>
        </span>
        <?php echo e($category_value); ?>

      </label>
    </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <span class="text-danger"><?php echo e($errors->first('category')); ?></span>
  </div>
  <span class="category_error"> </span>
</div>
</div>
<div class="row">
  <label class="col-sm-2 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.store_status'); ?><span class="required text-danger">*</span></label>
  <div class="col-sm-4">
    <div class="form-group">
      <?php echo Form::select('store_status',['1'=>trans('admin_messages.available'),'0'=>trans('admin_messages.unavailable')],@$store->store->status, ['placeholder' => trans('admin_messages.select'),'class'=>'form-control']);; ?>

      <span class="text-danger"><?php echo e($errors->first('store_status')); ?></span>
    </div>
  </div>
</div>
<div ng-init="default_img='<?php echo e($default_img); ?>';all_document=<?php echo e(old('document')?json_encode(old('document')):json_encode(@$store_document?:array(0))); ?>;errors = <?php echo e(json_encode($errors->getMessages())); ?>">
  <h4 class="my-3 px-md-3 my-md-4 text-left"><?php echo app('translator')->getFromJson('admin_messages.documents'); ?></h4>
</div>

<div ng-repeat="document in all_document" ng-cloak>
  <p ng-show="all_document.length > 1" style="float: right">
    <a href="javascript:void(0)" ng-click="delete_document($index)">
      <i class="material-icons btn-red">delete</i>
    </a>
  </p>
  <div class="row">
    <label class="col-md-3 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.document_name'); ?><span class="required">*</span></label>
    <div class="col-md-4">
      <div class="form-group">
        <input type="hidden" name="document[{{$index}}][id]" ng-value="document.id" class="form-control" id="document_id">
        <input type="text" name="document[{{$index}}][name]" ng-model="document.name" class="form-control" id="name">
        <span class="text-danger">{{ errors['document.'+$index+'.name'][0] }}</span>
      </div>
    </div>
  </div>
  <div class="row">
    <label class="col-md-3 col-form-label"><?php echo app('translator')->getFromJson('admin_messages.document_image'); ?><span class="required">*</span></label>
    <div class="col-md-9 pt-md-4">
      <div class="fileinput fileinput-new" data-provides="fileinput">
        <div class="fileinput-new thumbnail">
         <img ng-if="document.file.file_extension!='pdf'" src="{{document.document_file?document.document_file:(document.document_old_file?document.document_old_file:default_img)}}" alt="...">
         <a ng-if="document.file.file_extension=='pdf'"  href="{{document.document_file?document.document_file:(document.document_old_file?document.document_old_file:default_img)}}" alt="...">
         {{document.file.name}}
         </a>

       </div>
       <div class="fileinput-preview fileinput-exists thumbnail"></div>
       <div>
        <span class="btn btn-rose btn-round btn-file">
          <span class="fileinput-new"><?php echo app('translator')->getFromJson('admin_messages.select_file'); ?></span>
          <span class="fileinput-exists"><?php echo app('translator')->getFromJson('admin_messages.change'); ?></span>
          <input type="file" name="document[{{$index}}][document_file]" ng-model="document.document_file" class="form-control" id="document_file">
        </span>
        <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> <?php echo app('translator')->getFromJson('admin_messages.remove'); ?></a>
      </div>
      <p class="logo_error"></p>
      <span class="text-danger">{{ errors['document.'+$index+'.document_file'][0] }}</span>
    </div>
  </div>
</div>

</div>

<div class="col-12 my-4 text-right">
  <a href="javascript:void(0)" ng-click="add_document()" class="theme-color h6 p-0">
    + Add
  </a>
</div>


<div class="card-footer">
  <div class="ml-auto">

    <button class="btn btn-fill btn-rose btn-wd" type="submit"  value="site_setting">
      <?php echo app('translator')->getFromJson('admin_messages.submit'); ?>
    </button>
  </div>
  <div class="clearfix"></div>
</div>

</form>
</div>
</div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script type="text/javascript">
  $('#phone_code_country').change(function() {
    $('#apply_country_code').val('');
    if($(this).val())
      $('#apply_country_code').val('+'+$(this).val());
  });

</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin/template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>