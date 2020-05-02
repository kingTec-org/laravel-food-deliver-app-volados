<?php $__env->startSection('main'); ?>
<div class="content" ng-controller="menu_editor" ng-cloak>
  <div class="container-fluid">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-rose card-header-text">
          <div class="card-text">
            <h4 class="card-title"><?php echo e(@$form_name); ?></h4>
          </div>
        </div>
        <input type="hidden" id="store_id" name="store_id" value="<?php echo e(@$store->user_id); ?>" ng-init="store_id=<?php echo e($store->user_id); ?>">
        <div class="card-body" ng-init="menu=<?php echo e($menu); ?>; category_index = null; menu_index_open = null;menu_index = null;menu_item_index = null; menu_item_details = {};">
          <div class="menu-container row m-0 mt-4">
            <div class="col-md-6 col-lg-3 d-md-flex align-items-end flex-column p-0">
              <ul class="menu-list" ng-if="menu.length">
                <li ng-repeat="menulist in menu" ng-init="initToggleBar()" ng-class="menu_index == $index ? 'open active' : '' " >
                  <a ng-click= "select_menu($index)" href="javascript:void(0)">
                    <i class="icon icon-angle-arrow-pointing-to-right-1 mr-2" ng-class="menu_index == $index ? 'custom-rotate-down':'' "></i>
                    {{ menulist.menu}}
                  </a>

                  <div class="tooltip-link">
                    <a href="javascript:void(0)" class="icon icon-question-mark"></a>
                    <div class="tooltip-content">
                      <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_modal" ng-click="menu_time($index,menulist.menu_id,menulist.menu)">
                        <i class="icon icon-pencil-edit-button"></i>
                        Edit
                      </a>
                      <a href="javascript:void(0)" data-toggle="modal" data-target="#delete_modal" class="category_delete" ng-click="set($index,'menu')">
                        <i class="icon icon-rubbish-bin"></i>
                        Delete
                      </a>
                    </div>
                  </div>

                  <div class="sub-menu-list">
                    <ul>
                      <li ng-repeat="menucategory in menulist.menu_category"
                      ng-click= "category($index, $parent.$index)" ng-class="category_index == $index && menu_index == $parent.$index ? 'active' : '' ">
                      <a href="javascript:void(0)">
                        {{ menucategory.menu_category  }}
                        <div class="float-right">
                          <i data-toggle="modal" data-target="#sub_edit_modal" ng-click="edit_category(menucategory.menu_category_id,menucategory.menu_category,menucategory)" class="icon icon-pencil-edit-button"></i>
                          <i class="icon icon-rubbish-bin ml-2" data-toggle="modal" data-target="#delete_modal"  ng-click="set($index,'category');">
                          </i>
                        </div>
                      </a>
                    </li>
                  </ul>
                  <a href="javascript:void(0)" data-target="#add_modal"
                  ng-click="add_category(menulist.menu_id)" data-toggle="modal"
                  class="text-uppercase theme-color">
                  add category
                </a>
              </div>
            </li>
          </ul>
          <div class="w-100 mt-auto pt-4">
            <button type="button" data-target="#edit_modal" data-toggle="modal" class="text-uppercase btn text-center" ng-click="add_menu_pop()">
              Add Menu
            </button>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 d-md-flex align-items-end flex-column p-0 mt-5 mt-md-0" ng-show="category_index !== null">
          <ul class="menu-list" ng-if="menu[menu_index].menu_category[category_index].menu_item.length">
            <li ng-repeat="menu_item in menu[menu_index].menu_category[category_index].menu_item" ng-class="menu_item_index == $index ? 'active' : '' " ng-click="select_menu_item($index)">
              <a href="javascript:void(0)" class="clearfix" ng-click="set($index,'item');">{{menu_item.menu_item_name}}
                <i data-toggle="modal" data-target="#delete_modal" ng-click="set($index,'item');" class="icon icon-rubbish-bin ml-2 float-right">
                </i>
              </a>
            </li>
          </ul>
          <div class="w-100 mt-auto pt-4 text-md-right text-lg-left">
            <button type="button" class="btn text-center text-uppercase" ng-click="add_new_item()">
              Add Item
            </button>
          </div>
        </div>

        <div class="item_all_details col-md-12 col-lg-6 d-md-flex align-items-end flex-column p-0 mt-5 mt-lg-0" ng-show="menu_item_index !== null">
          <div class="panel-content w-100">
            <form class="form_valitate" id="menu_item_form">
              <div>
                <label>
                  Item Name <em class="text-danger">*</em>
                </label>
                <input type="text" name="menu_item_name" placeholder="Item Name" ng-model="menu_item_details.menu_item_name">
              </div>
             <div class="item-info border-0 my-3">
                <label>
                  Item Description <em class="text-danger">*</em>
                </label>
                <textarea class="form-control" placeholder="Description" name="menu_item_desc" ng-model="menu_item_details.menu_item_desc"> {{menu_item_details.menu_item_desc}}</textarea>
              </div>

            <div class="row my-3">
              <div class="col-md-4">
                <label>Price<em class="text-danger">*</em></label>
                <input type="text" name="menu_item_price" ng-model="menu_item_details.menu_item_price">
                <!-- <div class="select mt-3"><em class="text-danger">*</em>
                  <?php echo Form::select('item_type', ['0'=>'Veg','1' =>'Non-veg'], '', ['class' => '','placeholder' =>'select type','ng-model'=>'menu_item_details.menu_item_type']); ?>

                </div> -->
              </div>
              <div class="col-md-4 my-3 mt-md-0">
                <label>Tax % <em class="text-danger">*</em></label>
                <input type="text" name="menu_item_tax" ng-model="menu_item_details.menu_item_tax">
              </div>
              <div class="col-md-4">
               <label>Status<em class="text-danger">*</em></label>
               <?php echo Form::select('item_status', ['1'=>'Active','0' =>'Inactive'], '', ['class' => '','placeholder' =>'select status','ng-model'=>'menu_item_details.menu_item_status']); ?>

             </div>
           </div>

           <div class="mt-3">
            <div class="file-input">
              <input type="file" name="item_image" ng-model="menu_item_details.item_image"  demo-file-model="myFile"  class="form-control" id ="myFileField">
            </div>
            <span class="rec-info d-block mt-2">
              (Recommended size: 1350*310)
            </span>
            <div class="mt-3">
              <img ng-show="menu_item_details.item_image" ng-src="{{menu_item_details.item_image}}" with="100">
            </div>
          </div>
          <div class="mt-3">
            <div class="panel" ng-init="menu_item_translations = <?php echo e(json_encode(old('menu_item_translations') ?: array())); ?>; item_remove_translations =  []; errors = <?php echo e(json_encode($errors->getMessages())); ?>;" ng-cloak>



              <div class="panel-body">
                <input type="hidden" name="item_remove_translations" ng-value="item_remove_translations.toString()">


                <div class="card" ng-repeat="translation in menu_item_translations">

                  <div class="col-sm-12 static_remove">
                    <h4 class="box-title text-center">Translations</h4>
                    <button class="btn btn-danger btn-xs" ng-hide="menu_item_translations.length <  <?php echo e(count($language) - 1); ?>" ng-click="menu_item_translations.splice($index, 1); item_remove_translations.push(translation.id)">
                      Remove
                    </button>
                  </div>

                  <input type="hidden" name="menu_item_translations[{{$index}}][id]" value="{{translation.id}}">


                  <div class="card-body">
                    <div class="row" >
                      <label for="input_language_{{$index}}" class="col-sm-3 col-form-label">Language<em class="text-danger">*</em></label>
                      <div class="col-sm-8">
                        <div class="form-group">

                          <select name="menu_item_translations[{{$index}}][locale]" class="form-control" id="input_language_{{$index}}" ng-model="translation.locale" >
                            <option value='' ng-if="translation.locale == ''">Select Language</option>
                            <?php $__currentLoopData = @$language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($key); ?>" ng-if="(('<?php echo e($key); ?>' | checkKeyValueUsedInStack : 'locale': menu_item_translations) || '<?php echo e($key); ?>' == translation.locale) && '<?php echo e($key); ?>' != 'en'"><?php echo e($value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </select>                      

                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <label for="input_name_{{$index}}" class="col-sm-3 col-form-label">Name<em class="required text-danger">*</em></label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <?php echo Form::text('menu_item_translations[{{$index}}][name]', '{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_{{$index}}', 'placeholder' => 'Item Name','ng-model' => 'translation.name']); ?>


                        </div>

                      </div>
                    </div>



                    <div class="row">
                      <label for="input_content_{{$index}}" class="col-sm-3 col-form-label">Description<em class="required text-danger">*</em></label>
                      <div class="col-sm-8">
                        <div class="form-group">
                          <?php echo Form::textarea('menu_item_translations[{{$index}}][description]', '{{translation.description}}', ['class' => 'form-control', 'id' => 'input_description_{{$index}}', 'placeholder' => 'description','ng-model' => 'translation.description']); ?>


                        </div>

                      </div>
                    </div>

                  </div>
                  <legend ng-if="$index+1 < menu_item_translations.length"></legend>
                </div>
              </div>
              <div class="">
                <div class="row" ng-show="menu_item_translations.length <  <?php echo e(count(@$language) - 1); ?>">
                  <div class="col-sm-12">
                    <button type="button" class="btn btn-info" ng-click="menu_item_translations.push({locale:''});" >
                      <!-- <i class="fa fa-plus"></i> -->
                      Add Translation
                    </button>
                  </div>
                </div>                    
              </div>

            </div> 

          </div>


        </form>

      </div>
      <div class="w-100 text-right mt-auto pt-4">
        <button type="button" class="btn btn-rose w-100 text-uppercase" ng-click="update_item()">Submit Changes</button>
      </div>
    </div>
  </div>
</div>
</div>
</div>

<!-- Add category model !-->
<div class="modal fade" id="add_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <i class="icon icon-close-2"></i>
        </button>
      </div>
      <div class="modal-body item_all_details">
        <form class="form_valitate" id="category_add_form">
          <div class="form-group d-flex menu-name">
            <input class="pl-0 w-100" type="text" placeholder="Category name" name="category_name" ng-model="category_name" data-error-placement = "container" data-error-container= "#category-error-box" maxlength = '150' />

          </div>
          <span id="category-error-box"></span>
          <div class="panel" ng-init="category_translations = <?php echo e(json_encode(old('category_translations') ?: array())); ?>; category_remove_translations =  []; errors = <?php echo e(json_encode($errors->getMessages())); ?>;" ng-cloak>



            <div class="panel-body">
              <input type="hidden" name="category_remove_translations" ng-value="category_remove_translations.toString()">


              <div class="card" ng-repeat="translation in category_translations">

                <div class="col-sm-12 static_remove">
                 <h4 class="box-title text-left">Translations</h4>
                 <button class="btn btn-danger btn-xs" ng-hide="category_translations.length <  <?php echo e(count($language) - 1); ?>" ng-click="category_translations.splice($index, 1); category_remove_translations.push(translation.id)">
                  Remove
                </button>
              </div>

              <input type="hidden" name="category_translations[{{$index}}][id]" value="{{translation.id}}">


              <div class="card-body">
                <div class="row" >
                  <label for="input_language_{{$index}}" class="col-sm-2 col-form-label">Language<em class="text-danger">*</em></label>
                  <div class="col-sm-6">
                    <div class="form-group">

                      <select name="category_translations[{{$index}}][locale]" class="form-control" id="input_language_{{$index}}" ng-model="translation.locale" >
                        <option value='' ng-if="translation.locale == ''">Select Language</option>
                        <?php $__currentLoopData = @$language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e($key); ?>" ng-if="(('<?php echo e($key); ?>' | checkKeyValueUsedInStack : 'locale': category_translations) || '<?php echo e($key); ?>' == translation.locale) && '<?php echo e($key); ?>' != 'en'"><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>                      

                    </div>
                  </div>
                </div>

                <div class="row">
                  <label for="input_name_{{$index}}" class="col-sm-2 col-form-label">Name<em class="required text-danger">*</em></label>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <?php echo Form::text('category_translations[{{$index}}][name]', '{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_{{$index}}', 'placeholder' => 'Category name','ng-model' => 'translation.name','maxlength'=> '150']); ?>


                    </div>

                  </div>
                </div>
              </div>

              <legend ng-if="$index+1 < category_translations.length"></legend>
            </div>
          </div>
          <div class="panel-footer">
            <div class="row" ng-show="category_translations.length <  <?php echo e(count(@$language) - 1); ?>">
              <div class="col-sm-12">
                <button type="button" class="btn btn-info" ng-click="category_translations.push({locale:''});" >
                  <!-- <i class="fa fa-plus"></i> -->
                  Add Translation
                </button>
              </div>
            </div>                    
          </div>

        </div> 
        <div class="mt-3 pt-4 modal-footer px-0 text-right">
          <button data-dismiss="modal" type="cancel" class="btn">CANCEL</button>
          <button type="submit" class="btn btn-rose ml-2" ng-click="save_category('add')">SUBMIT</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<!-- End Add category model !-->

<!-- category model !-->
<div class="modal fade" id="sub_edit_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <i class="icon icon-close-2"></i>
        </button>
      </div>
      <div class="modal-body item_all_details" >
        <form class="form_valitate" id = "category_edit_form">
          <div class="form-group d-flex menu-name">
            <input class="pl-0 w-100 flex-grow-1" placeholder="Category name"  type="text" ng-model="category_name" name="category_name" ng-value="" data-error-placement = "container" data-error-container= "#category-edit-error-box" maxlength = '150' />
            <input class="pl-0 w-100 flex-grow-1" type="hidden" name="category_id" />
            <!-- <i class="icon icon-pencil-edit-button"></i> -->
          </div>
          <span id="category-edit-error-box"></span>

          <div class="panel" ng-init="category_translations = <?php echo e(json_encode(old('category_translations') ?: array())); ?>; category_remove_translations =  []; errors = <?php echo e(json_encode($errors->getMessages())); ?>;" ng-cloak>

            <div class="panel-body">
              <input type="hidden" name="category_remove_translations" ng-value="category_remove_translations.toString()">


              <div class="card" ng-repeat="translation in category_translations">

                <div class="col-sm-12 static_remove">
                  <h4 class="box-title text-center">Translations</h4>
                  <button class="btn btn-danger btn-xs" ng-hide="category_translations.length <  <?php echo e(count($language) - 1); ?>" ng-click="category_translations.splice($index, 1); category_remove_translations.push(translation.id)">
                    Remove
                  </button>
                </div>

                <input type="hidden" name="category_translations[{{$index}}][id]" value="{{translation.id}}">


                <div class="card-body">
                  <div class="row" >
                    <label for="input_language_{{$index}}" class="col-sm-2 col-form-label">Language<em class="text-danger">*</em></label>
                    <div class="col-sm-6">
                      <div class="form-group">

                        <select name="category_translations[{{$index}}][locale]" class="form-control" id="input_language_{{$index}}" ng-model="translation.locale" >
                          <option value='' ng-if="translation.locale == ''">Select Language</option>
                          <?php $__currentLoopData = @$language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                          <option value="<?php echo e($key); ?>" ng-if="(('<?php echo e($key); ?>' | checkKeyValueUsedInStack : 'locale': category_translations) || '<?php echo e($key); ?>' == translation.locale) && '<?php echo e($key); ?>' != 'en'"><?php echo e($value); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>                      

                        
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <label for="input_name_{{$index}}" class="col-sm-2 col-form-label">Name<em class="required text-danger">*</em></label>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <?php echo Form::text('category_translations[{{$index}}][name]', '{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_{{$index}}', 'placeholder' => 'Category name','ng-model' => 'translation.name','maxlength'=> '150']); ?>


                      </div>

                    </div>
                  </div>
                </div>

                <legend ng-if="$index+1 < category_translations.length"></legend>
              </div>
            </div>
            <div class="panel-footer">
              <div class="row" ng-show="category_translations.length <  <?php echo e(count(@$language) - 1); ?>">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-info" ng-click="category_translations.push({locale:''});" >
                    <!-- <i class="fa fa-plus"></i> -->
                    Add Translation
                  </button>
                </div>
              </div>                    
            </div>
          </div>




          <div class="mt-3 pt-4 modal-footer px-0 text-right">
            <button data-dismiss="modal" type="cancel" class="btn">
              CANCEL
            </button>
            <button type="submit" class="btn btn-rose ml-2" ng-click="save_category('edit')">
              SUBMIT
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End category model !-->

<!-- Menu edit modal !-->
<div class="modal fade" id="edit_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <i class="icon icon-close-2"></i>
        </button>
      </div>
      <div class="modal-body item_all_details">
        <form class="update_menu_time">
          <div class="form-group d-flex menu-name">
            <input class="pl-0 w-100 flex-grow-1" placeholder="Menu name" type="text" name="menu_name" ng-model="menu_name" data-error-placement = "container" data-error-container= ".menu_name_error" maxlength = '150'/>
            <!-- <i class="icon icon-pencil-edit-button"></i> -->
          </div>
          <span class="menu_name_error d-block mb-3"></span>
          <!-- Translation -->

          <div class="panel" ng-init="translations = <?php echo e(json_encode(old('translations') ?: array())); ?>; removed_translations =  []; errors = <?php echo e(json_encode($errors->getMessages())); ?>;" ng-cloak>



            <div class="panel-body">
              <input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">


              <div class="card" ng-repeat="translation in translations">

                <div class="col-sm-12 static_remove">
                  <h4 class="box-title text-center">Translations</h4>
                  <button class="btn btn-danger btn-xs" ng-hide="translations.length <  <?php echo e(count($language) - 1); ?>" ng-click="translations.splice($index, 1); removed_translations.push(translation.id)">
                    Remove
                  </button>
                </div>

                <input type="hidden" name="translations[{{$index}}][id]" value="{{translation.id}}">

                <div class="card-body">
                  <div class="row" >
                    <label for="input_language_{{$index}}" class="col-sm-2 col-form-label">Language<em class="text-danger">*</em></label>
                    <div class="col-sm-6">
                      <div class="form-group">

                        <select name="translations[{{$index}}][locale]" class="form-control" id="input_language_{{$index}}" ng-model="translation.locale" >
                          <option value='' ng-if="translation.locale == ''">Select Language</option>
                          <?php $__currentLoopData = @$language; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                          <option value="<?php echo e($key); ?>" ng-if="(('<?php echo e($key); ?>' | checkKeyValueUsedInStack : 'locale': translations) || '<?php echo e($key); ?>' == translation.locale) && '<?php echo e($key); ?>' != 'en'"><?php echo e($value); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>                      

                        
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <label for="input_name_{{$index}}" class="col-sm-2 col-form-label">Name<em class="required text-danger">*</em></label>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <?php echo Form::text('translations[{{$index}}][name]', '{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_{{$index}}', 'placeholder' => 'Menu Name','ng-model' => 'translation.name','maxlength'=> '150']); ?>


                      </div>
                    </div>
                  </div>
                  
                </div>
                <legend ng-if="$index+1 < translations.length"></legend>
              </div>
            </div>
            <div class="panel-footer">
              <div class="row" ng-show="translations.length <  <?php echo e(count(@$language) - 1); ?>">
                <div class="col-sm-12">
                  <button type="button" class="btn btn-info" ng-click="translations.push({locale:''});" >
                    <!-- <i class="fa fa-plus"></i> -->
                    Add Translation
                  </button>
                </div>
              </div>                    
            </div>
          </div> 
          <!-- <div class="menu-available" ng-init="menu_timing = '';day_name =<?php echo e(json_encode(day_name())); ?>">
            <p>When is this menu available?</p>
            <div class="d-md-flex menu-view select-day" ng-repeat="available in menu_timing" >
              <div class="select">
                <select id="menu-select{{$index}}" ng-model="available.day" name="menu_timing_day[]">
                  <option value="">Select a day</option>
                  <option value="{{key}}"  ng-selected="available.day==key" ng-repeat="(key,value) in day_name track by $index" ng-if="(key | checkKeyValueUsedInStack : 'day': menu_timing) || available.day==key">{{value}}</option>
                </select>
              </div>
              <div class="added-times d-flex ml-3 align-items-start">
                <div class="select-time d-flex">
                  <div class="select">
                    <?php echo Form::select('menu_timing_start_time[]',time_data('time'),'', ['data-end_time'=>'{{available.end_time}}','placeholder'=>'select','ng-model'=>'available.start_time', 'id'=>'start-select{{$index}}']);; ?>

                  </div>
                  <span class="m-2">to</span>
                  <div class="select">
                    <?php echo Form::select('menu_timing_end_time[]',time_data('time'),'', ['placeholder'=>'select','ng-model'=>'available.end_time', 'id'=>'end-select{{$index}}']);; ?>

                  </div>
                  <a href="javascript:void(0)" ng-click="remove_menu_time($index,available.id)" class="icon icon-rubbish-bin d-inline-block m-2 mr-0 text-danger"></a>
                </div>
              </div>
            </div>
            <a href="javascript:void(0)" class="theme-color text-uppercase d-block mt-3" ng-click="add_menu_time()" ng-show="menu_timing.length < 7" style="width: max-content;">
              <i class="icon icon-add mr-3"></i>
              add more
            </a>
          </div> -->
          <div class="mt-3 pt-4 modal-footer px-0 text-right">
            <button data-dismiss="modal" class="btn">
              CANCEL
            </button>
            <button type="submit" class="btn btn-rose ml-2" ng-click="update_menu_time()">
              SUBMIT
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- End menu edit modal !-->

<!--category delete !-->
<div class="modal fade" id="delete_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <i class="icon icon-close-2"></i>
        </button>
        <h3 class="modal-title">Delete this <span>{{delete_name}}</span>?</h3>
      </div>
      <div class="modal-body">
        <p>Are you sure want to delete this {{delete_name}}. This action cannot be undone.</p>
        <p class="text-danger delete_item_msg"> </p>
      </div>
      <div class="modal-footer text-right">
        <button type="reset " data-dismiss="modal" class="btn">CANCEL</button>
        <button type="submit" class="btn btn-rose ml-2" ng-click="remove_item(remove_id,delete_name)">SUBMIT</button>
      </div>
    </div>
  </div>
</div>

<!--category delete !-->
<div class="modal fade" id="delete_error_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <i class="icon icon-close-2"></i>
        </button>
        <h4 class="text-danger modal-title delete_item_msg"></h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer text-right">
        <button data-dismiss="modal" type="reset" class="btn">
          CANCEL
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <i class="icon icon-close-2"></i>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group d-flex menu-name">
            <input class="pl-0 w-100 flex-grow-1 light-color" type="text" name="" value="Choice of Toppings" readonly/>
            <!-- <i class="icon icon-pencil-edit-button"></i> -->
          </div>
          <div class="menu-available">
            <p>How many items can the customer choose?</p>
            <div class="d-md-flex">
              <div class="select w-50 mr-2">
                <select>
                  <option>Select a range</option>
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                </select>
              </div>
              <div class="added-times d-flex ml-3 align-items-center">
                <input type="text" name="" value="0" readonly>
                <span class="d-inline-block mx-2">to</span>
                <input type="text" name="" value="5">
              </div>
            </div>
            <div class="my-4 required-list">
              <p class="mb-1">Is this required?</p>
              <ul>
                <li>
                  <label>
                    <input type="radio" name="">
                    Required
                  </label>
                </li>
                <li>
                  <label>
                    <input type="radio" name="">
                    Optional
                  </label>
                </li>
              </ul>
            </div>
            <div class="add-list-row row">
              <div class="col-6">
                <label>Item</label>
                <input type="text" value="Add Cheese" />
              </div>
              <div class="col-6">
                <label>Optional fee</label>
                <input type="text" value="$1.00" />
              </div>
            </div>
            <div class="added-list-row my-4">
              <div class="row">
                <div class="col-6">
                  <input type="text" value="Add Cheese" />
                </div>
                <div class="col-6 d-flex align-items-center">
                  <input type="text" value="$1.00" />
                  <i class="icon icon-rubbish-bin ml-2"></i>
                </div>
              </div>
            </div>
            <a href="javascript:void(0)" class="theme-color text-uppercase d-block mt-3">
              <i class="icon icon-add mr-3"></i>
              add another item
            </a>
          </div>
          <div class="mt-3 pt-4 modal-footer px-0 text-right">
            <button type="reset" class="btn">
              CANCEL
            </button>
            <button type="submit" class="btn btn-rose ml-2">
              SUBMIT
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin/template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>