@extends('template')

@section('main')
<main id="site-content" role="main" ng-controller="menu_editor">
	<div class="partners">
		@include ('store.navigation')
		<div class="menu-editor mt-md-4 mb-5" >
			<h1>{{ trans('messages.store.menu_editor') }}</h1>
			<div class="mt-4 mb-5 panel-content add_loading">
				<div class="d-md-flex align-items-center justify-content-between">
					<h2>{{ trans('messages.store.craft_your_menu') }}</h2>
					<!-- <p class="required-b">Pending changes</p> -->
				</div>
				<div class="menu-container row m-0 mt-4" ng-init="menu={{ $menu }}; category_index = null; menu_index = null;menu_item_index = null; menu_item_details = {};">
					<div class="col-md-6 col-lg-3 d-md-flex align-items-end flex-column p-0">
						<ul class="menu-list">
							<li ng-repeat="menulist in menu" ng-init="initToggleBar()" ng-class="menu_index == $index ? 'open active' : '' " >
								<a href="javascript:void(0)" ng-click= "select_menu($index)">
									<i class="icon icon-angle-arrow-pointing-to-right-1 mr-2" ng-class="menu_index == $index ? 'custom-rotate-down' : '' "></i>
									@{{ menulist.menu}}
								</a>
								<div id="tooltip_id-@{{ menulist.id}}" class="tooltip-link">
									<a href="javascript:void(0)" class="icon icon-tool-menu"></a>
									<div class="tooltip-content">
										<a href="javascript:void(0)" data-toggle="modal" data-target="#edit_menu_modal" ng-click="menu_time($index,menulist.menu_id,menulist.menu)" class="clearfix">
											<i class="icon icon-pencil-edit-button"></i>
											{{ trans('admin_messages.edit') }}
										</a>
										<a href="javascript:void(0)" data-toggle="modal" data-target="#delete_modal" class="category_delete" ng-click="set($index,'menu')">
											<i class="icon icon-rubbish-bin"></i>
											{{ trans('admin_messages.delete') }}
										</a>
									</div>
								</div>

								<div class="sub-menu-list">
									<ul>
										<li ng-repeat="menucategory in menulist.menu_category"
										ng-click= "category($index, $parent.$index)" ng-class="category_index == $index && menu_index == $parent.$index ? 'active' : '' ">
										<a href="javascript:void(0)" class="clearfix">
											@{{ menucategory.menu_category }}
											<div class="float-right">
												<i onclick="refreshSelect()" data-toggle="modal" data-target="#sub_edit_modal" ng-click="edit_category(menucategory.menu_category_id,menucategory.menu_category,menucategory)" class="icon icon-pencil-edit-button">
												</i>
												<i class="icon icon-rubbish-bin ml-2" data-toggle="modal" data-target="#delete_modal" ng-click="set($index,'category');">
												</i>
											</div>
										</a>
									</li>
								</ul>
								<a href="javascript:void(0)" data-target="#add_category_modal"
								ng-click="add_category(menulist.menu_id)" data-toggle="modal" class="text-uppercase theme-color">
								{{ trans('admin_messages.add_category') }}
							</a>
						</div>
					</li>
				</ul>
				<div class="w-100 mt-auto pt-4">
					<button type="button" data-target="#edit_menu_modal"   data-toggle="modal" class="theme-color text-uppercase bg-white text-center w-100" ng-click="add_menu_pop()" >{{ trans('messages.store_dashboard.add_menu') }}</button>
				</div>
			</div>

					<!-- <div  ng-repeat="menulist in menu">
						<div ng-repeat="menucategory in menulist.menu_category"> -->

							<div class="col-md-6 col-lg-3 d-md-flex align-items-end flex-column p-0 mt-5 mt-md-0" ng-show="category_index !== null">
								<ul class="menu-list" ng-if="menu[menu_index].menu_category[category_index].menu_item.length > 0">
									<li ng-repeat="menu_item in menu[menu_index].menu_category[category_index].menu_item" ng-class="menu_item_index == $index ? 'active' : '' " ng-click="select_menu_item($index)">
										<a href="javascript:void(0)" class="clearfix" ng-click="set($index,'item');">@{{menu_item.menu_item_name}}
											<i data-toggle="modal" data-target="#delete_modal" ng-click="set($index,'item');" class="icon icon-rubbish-bin ml-2 float-right">
											</i>
										</a>
									</li>
								</ul>
								<div class="w-100 mt-auto pt-4 text-md-right text-lg-left">
									<button type="button" class="theme-color bg-white text-center text-uppercase w-100" ng-click="add_new_item()">{{ trans('admin_messages.add_item') }}</button>
								</div>
							</div>

							<div class="item_all_details col-md-12 col-lg-6 d-md-flex align-items-end flex-column p-0 mt-5 mt-lg-0" ng-show="menu_item_index !== null">
								<div class="panel-content w-100">
									<form id="item_form" class="form_valitate">
										<label>{{ trans('admin_messages.item_name') }} <span class="required" aria-required="true">*</span></label>
										<input autocomplete="off" type="text" name="menu_item_name" ng-model="menu_item_details.menu_item_org_name">
										<div class="item-info border-0 mt-3">
											<label>{{ trans('messages.store.item_description') }} </label>
											<textarea name="menu_item_desc" ng-model="menu_item_details.menu_item_org_desc"> @{{menu_item_details.menu_item_desc}}</textarea>
										</div>
										<div class="row my-3">
											<div class="col-md-4">
												<label>{{ trans('admin_messages.price') }} <span class="required" aria-required="true">*</span></label>
												<input autocomplete="off" type="text" name="menu_item_price" ng-model="menu_item_details.menu_item_price">
											</div>
											<div class="col-md-4 my-3 mt-md-0">
												<label>{{ trans('messages.profile_orders.tax') }} %</label>
												<input autocomplete="off" type="text" name="menu_item_tax" ng-model="menu_item_details.menu_item_tax" value="0" placeholder="{{ trans('messages.profile.percentage') }}">
											</div>
											<div class="col-md-4">
												<label>{{ trans('messages.store.item_visibility') }} <span class="required" aria-required="true">*</span></label>
												{!!Form::select('item_status', ['1'=>trans('admin_messages.active'),'0' =>trans('admin_messages.inactive')], '', ['class' => '','placeholder' =>trans('messages.store_dashboard.select_status'),'ng-model'=>'menu_item_details.menu_item_status'])!!}
											</div>
										</div>
										<!-- <div class="row my-3">
											<div class="col-md-6">
												<label>{{ trans('messages.store.item_type') }} <span class="required" aria-required="true">*</span></label>
												{!!Form::select('item_type', ['0'=>trans('messages.store_dashboard.veg'),'1' =>trans('messages.store_dashboard.non_veg')], '', ['class' => '','placeholder' => trans('messages.store_dashboard.select_type'),'ng-model'=>'menu_item_details.menu_item_type'])!!}
											</div>
											<div class="col-md-6">
												<label>{{ trans('messages.store.item_visibility') }} <span class="required" aria-required="true">*</span></label>

												{!!Form::select('item_status', ['1'=>trans('admin_messages.active'),'0' =>trans('admin_messages.inactive')], '', ['class' => '','placeholder' =>trans('messages.store_dashboard.select_status'),'ng-model'=>'menu_item_details.menu_item_status'])!!}
											</div>
										</div> -->
										<div class="row mt-3">
											<div class="col-md-6">
												<label>{{ trans('messages.store.item_image') }}
													<span class="rec-info d-block">
														({{trans('messages.store.recommended')}} {{trans('admin_messages.size')}}: 1350*310)
													</span>
												</label>
												<div class="file-input menu_image">

													<input autocomplete="off" type="file" name="item_image" ng-model="menu_item_details.item_image" demo-file-model="myFile" class="form-control" id ="myFileField" style="visibility:hidden;">
													<a class="choose_file_type banner_choose" id="chooses_file"><span id="banner_name">{{trans('messages.profile.choose_file')}}</span></a>
													<span class="upload_text" id="file_text"></span>
												</div>
											</div>
											<div class="col-md-6 mt-2 mt-md-0">
												<div class="menu_img_rest">
													<img class="img-fluid " ng-show="menu_item_details.item_image && menu_item_details.item_image.length!=null " ng-src="@{{menu_item_details.item_image}}">
												</div>
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-md-12">
												<div class="panel menu_edit_opt" ng-init="menu_item_translations = {{json_encode(old('menu_item_translations') ?: array())}}; item_remove_translations =  []; errors = {{json_encode($errors->getMessages())}};" ng-cloak>



													<div class="panel-body" ng-repeat="translation in menu_item_translations">
														<input type="hidden" name="item_remove_translations" ng-value="item_remove_translations.toString()">


														<div class="card" >
															<h4 class="box-title text-center">{{trans('messages.translations')}}</h4>


															<input type="hidden" name="menu_item_translations[@{{$index}}][id]" value="@{{translation.id}}">


															<div class="card-body">
																<div class="row" >
																	<label for="input_language_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.language')}}<em class="text-danger">*</em></label>
																	<div class="col-sm-8">
																		<div class="form-group">
																			<div class="select">
																				<select name="menu_item_translations[@{{$index}}][locale]" class="form-control" id="input_language_@{{$index}}" ng-model="translation.locale" >
																					<option value='' ng-if="translation.locale == ''">{{trans('messages.select_language')}}</option>
																					@foreach(@$language as $key => $value)

																					<option value="{{$key}}" ng-if="(('{{$key}}' | checkKeyValueUsedInStack : 'locale': menu_item_translations) || '{{$key}}' == translation.locale) && '{{$key}}' != 'en'">{{$value}}</option>
																					@endforeach
																				</select>                      
																			</div>
																		</div>
																	</div>
																</div>

																<div class="row">
																	<label for="input_name_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.name')}}<em class="required text-danger">*</em></label>
																	<div class="col-sm-8">
																		<div class="form-group">
																			{!! Form::text('menu_item_translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_@{{$index}}', 'placeholder' =>trans('messages.item_name'),'ng-model' => 'translation.name']) !!}

																		</div>

																	</div>
																</div>



																<div class="row">
																	<label for="input_content_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.description')}}<em class="required text-danger">*</em></label>
																	<div class="col-sm-8">
																		<div class="form-group">
																			{!! Form::textarea('menu_item_translations[@{{$index}}][description]', '@{{translation.description}}', ['class' => 'form-control', 'id' => 'input_description_@{{$index}}', 'placeholder' => trans('messages.description'),'ng-model' => 'translation.description']) !!}

																		</div>

																	</div>
																</div>

																<div class="col-sm-12 static_remove p-0 text-right">
																	<button class="btn btn-danger btn-xs" ng-hide="menu_item_translations.length <  {{count($language) - 1}}" ng-click="menu_item_translations.splice($index, 1); item_remove_translations.push(translation.id)">
																		{{ trans('messages.remove')}}
																	</button>
																</div>
															</div>
															<legend ng-if="$index+1 < menu_item_translations.length"></legend>
														</div>
													</div>
													<div class="">
														<div class="row" ng-show="menu_item_translations.length <  {{count(@$language) - 1}}">
															<div class="col-sm-12">
																<button type="button" onclick="refreshSelect()" class="btn btn-info" ng-click="menu_item_translations.push({locale:''});" >
																	<!-- <i class="fa fa-plus"></i> -->
																	{{trans('messages.add_translation')}}
																</button>
															</div>
														</div>                    
													</div>

												</div> 
											</div>
										</div>




									</form>


								</div>
								<div class="w-100 text-right mt-auto pt-4">
									<button type="button" class="btn btn-theme w-100 text-uppercase" ng-click="update_item()">{{ trans('admin_messages.submit_changes') }}</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Add category model !-->
			<div class="modal fade" id="add_category_modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">
								<i class="icon icon-close-2"></i>
							</button>
						</div>
						<div class="modal-body item_all_details">
							<form class="form_valitate" id="category_add_form">
								<div class="form-group d-flex menu-name menu_head">
									<input autocomplete="off" class="pl-0" placeholder="{{ trans('messages.store.category_name') }}" type="text" ng-model="category_name"  name="category_name" data-error-placement = "container" data-error-container= "#category-error-box" maxlength = '150' />
									<!-- <i class="icon icon-pencil-edit-button"></i> -->
								</div>
								<span id="category-error-box"></span>
								<div class="panel menu_edit_opt" ng-init="category_translations = {{json_encode(old('category_translations') ?: array())}}; category_remove_translations =  []; errors = {{json_encode($errors->getMessages())}};" ng-cloak>



									<div class="panel-body" ng-repeat="translation in category_translations">
										<input type="hidden" name="category_remove_translations" ng-value="category_remove_translations.toString()">


										<div class="card" >

											<h4 class="box-title text-center">{{trans('messages.translations')}}</h4>


											<input type="hidden" name="category_translations[@{{$index}}][id]" value="@{{translation.id}}">


											<div class="card-body">
												<div class="row" >
													<label for="input_language_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.language')}}<em class="text-danger">*</em></label>
													<div class="col-sm-8">
														<div class="form-group">
															<div class="select">
																<select name="category_translations[@{{$index}}][locale]" class="form-control" id="input_language_@{{$index}}" ng-model="translation.locale" >
																	<option value='' ng-if="translation.locale == ''">{{trans('messages.select_language')}}</option>
																	@foreach(@$language as $key => $value)

																	<option value="{{$key}}" ng-if="(('{{$key}}' | checkKeyValueUsedInStack : 'locale': category_translations) || '{{$key}}' == translation.locale) && '{{$key}}' != 'en'">{{$value}}</option>
																	@endforeach
																</select>                      
															</div>
														</div>
													</div>
												</div>

												<div class="row">
													<label for="input_name_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.name')}}<em class="required text-danger">*</em></label>
													<div class="col-sm-8">
														<div class="form-group">
															{!! Form::text('category_translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_@{{$index}}', 'placeholder' => trans('messages.category_name'),'ng-model' => 'translation.name','maxlength'=> '150']) !!}

														</div>

													</div>
												</div>
												<div class="col-sm-12 static_remove p-0 text-right">
													<button class="btn btn-danger btn-xs" ng-hide="category_translations.length <  {{count($language) - 1}}" ng-click="category_translations.splice($index, 1); category_remove_translations.push(translation.id)">
														{{ trans('messages.remove')}}
													</button>
												</div>

											</div>

											<legend ng-if="$index+1 < category_translations.length"></legend>
										</div>
									</div>
									<div class="panel-footer">
										<div class="row" ng-show="category_translations.length <  {{count(@$language) - 1}}">
											<div class="col-sm-12">
												<button type="button" onclick="refreshSelect()" class="btn btn-info" ng-click="category_translations.push({locale:''});" >
													<!-- <i class="fa fa-plus"></i> -->
													{{ trans('messages.add_translation')}}
												</button>
											</div>
										</div>                    
									</div>

								</div> 
								<div class="mt-3 pt-4 modal-footer px-0 border-0 text-right">
									<button data-dismiss="modal" type="cancel" class="btn btn-primary theme-color">
										{{ trans('messages.store.cancel') }}
									</button>
									<button type="submit" class="btn btn-theme ml-2" ng-click="save_category('add')">{{ trans('messages.store.submit') }}
									</button>
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
						<div class="modal-body item_all_details">
							<form class="form_valitate" id = "category_edit_form">
								<div class="form-group d-flex menu-name menu_head">
									<input autocomplete="off" class="pl-0" placeholder="{{ trans('messages.store.category_name') }}" type="text" ng-model="category_name" name="category_name" ng-value="" data-error-placement = "container" data-error-container= "#category-edit-error-box" maxlength = '150' />

									<!-- <i class="icon icon-pencil-edit-button"></i> -->
								</div>
								<span id="category-edit-error-box"></span>
								<div class="panel menu_edit_opt" ng-init="category_translations = {{json_encode(old('category_translations') ?: array())}}; category_remove_translations =  []; errors = {{json_encode($errors->getMessages())}};" ng-cloak>

									<div class="panel-body"  ng-repeat="translation in category_translations">
										<input type="hidden" name="category_remove_translations" ng-value="category_remove_translations.toString()">


										<div class="card">

											<h4 class="box-title text-center">{{trans('messages.translations')}}</h4>


											<input type="hidden" name="category_translations[@{{$index}}][id]" value="@{{translation.id}}">


											<div class="card-body">
												<div class="row" >
													<label for="input_language_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.language')}}<em class="text-danger">*</em></label>
													<div class="col-sm-8">
														<div class="form-group">
															<div class="select">
																<select name="category_translations[@{{$index}}][locale]" class="form-control" id="input_language_@{{$index}}" ng-model="translation.locale" >
																	<option value='' ng-if="translation.locale == ''">{{trans('messages.select_language')}}</option>
																	@foreach(@$language as $key => $value)

																	<option value="{{$key}}" ng-if="(('{{$key}}' | checkKeyValueUsedInStack : 'locale': category_translations) || '{{$key}}' == translation.locale) && '{{$key}}' != 'en'">{{$value}}</option>
																	@endforeach
																</select>                      
															</div>

														</div>
													</div>
												</div>

												<div class="row">
													<label for="input_name_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.name')}}<em class="required text-danger">*</em></label>
													<div class="col-sm-8">
														<div class="form-group">
															{!! Form::text('category_translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_@{{$index}}', 'placeholder' => trans('messages.category_name'),'ng-model' => 'translation.name','maxlength'=> '150']) !!}

														</div>

													</div>
												</div>
												<div class="col-sm-12 static_remove p-0 text-right">
													<button class="btn btn-danger btn-xs" ng-hide="category_translations.length <  {{count($language) - 1}}" ng-click="category_translations.splice($index, 1); category_remove_translations.push(translation.id)">
														{{ trans('messages.remove')}}
													</button>
												</div>
											</div>

											<legend ng-if="$index+1 < category_translations.length"></legend>
										</div>
									</div>
									<div class="panel-footer">
										<div class="row" ng-show="category_translations.length <  {{count(@$language) - 1}}">
											<div class="col-sm-12">
												<button onclick="refreshSelect()" type="button" class="btn btn-info" ng-click="category_translations.push({locale:''});" >
													<!-- <i class="fa fa-plus"></i> -->
													{{ trans('messages.add_translation')}}
												</button>
											</div>
										</div>                    
									</div>
								</div>
								<div class="mt-3 pt-4 modal-footer px-0 text-right">
									<button data-dismiss="modal" type="cancel" class="btn btn-primary theme-color">
										{{ trans('messages.store.cancel') }}
									</button>
									<button type="submit" class="btn btn-theme ml-2" ng-click="save_category('edit')">{{ trans('messages.store.submit') }}
									</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End category model !-->

			<!-- Menu edit modal !-->

			<div class="modal fade edit_menu_modal " id="edit_menu_modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">
								<i class="icon icon-close-2"></i>
							</button>
						</div>
						<div class="modal-body item_all_details">
							<form class="update_menu_time ">
						<!-- <div class="form-group d-flex menu-name" >
							<input autocomplete="off" class="pl-0" type="text" name="menu_name" ng-model="menu_name" />
							<i class="icon icon-pencil-edit-button"></i>
						</div> -->
						<div class="form-group d-flex menu-name menu_head">
							<input autocomplete="off" class="pl-0" placeholder="{{ trans('messages.store.menu_name') }}" type="text" name="menu_name" ng-model="menu_name" data-error-placement = "container" data-error-container= ".menu_name_error" />
							<!-- <i class="icon icon-pencil-edit-button"></i> -->
						</div>
						<span class="menu_name_error d-block mb-3"></span>


						<!-- Translation -->

						<div class="panel menu_edit_opt" ng-init="translations = {{json_encode(old('translations') ?: array())}}; removed_translations =  []; errors = {{json_encode($errors->getMessages())}};" ng-cloak>



							<div class="panel-body"  ng-repeat="translation in translations">
								<input type="hidden" name="removed_translations" ng-value="removed_translations.toString()">


								<div class="card">

									<h4 class="box-title text-center">{{trans('messages.translations')}}</h4>


									<input type="hidden" name="translations[@{{$index}}][id]" value="@{{translation.id}}">

									<div class="card-body">
										<div class="row" >
											<label for="input_language_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.language')}}<em class="text-danger">*</em></label>
											<div class="col-sm-8">
												<div class="form-group">
													<div class="select">
														<select name="translations[@{{$index}}][locale]" class="form-control" id="input_language_@{{$index}}" ng-model="translation.locale" >
															<option value='' ng-if="translation.locale == ''">{{trans('messages.select_language')}}</option>
															@foreach(@$language as $key => $value)

															<option value="{{$key}}" ng-if="(('{{$key}}' | checkKeyValueUsedInStack : 'locale': translations) || '{{$key}}' == translation.locale) && '{{$key}}' != 'en'">{{$value}}</option>
															@endforeach
														</select>                      
													</div>

												</div>
											</div>
										</div>

										<div class="row">
											<label for="input_name_@{{$index}}" class="col-sm-4 col-form-label">{{trans('messages.name')}}<em class="required text-danger">*</em></label>
											<div class="col-sm-8">
												<div class="form-group">
													{!! Form::text('translations[@{{$index}}][name]', '@{{translation.name}}', ['class' => 'form-control', 'id' => 'input_name_@{{$index}}', 'placeholder' => trans('messages.menu_name'),'ng-model' => 'translation.name','maxlength'=> '150']) !!}

												</div>

											</div>
										</div>
										<div class="col-sm-12 static_remove p-0 text-right">
											<button class="btn btn-danger btn-xs" ng-hide="translations.length <  {{count($language) - 1}}" ng-click="translations.splice($index, 1); removed_translations.push(translation.id)">
												{{ trans('messages.remove')}}
											</button>
										</div>
									</div>
									<legend ng-if="$index+1 < translations.length"></legend>
								</div>
							</div>
							<div class="panel-footer">
								<div class="row" ng-show="translations.length <  {{count(@$language) - 1}}">
									<div class="col-sm-12">
										<button type="button" onclick="refreshSelect()" class="btn btn-info" ng-click="translations.push({locale:''});" >
											<!-- <i class="fa fa-plus"></i> -->
											{{ trans('messages.add_translation')}}
										</button>
									</div>
								</div>                    
							</div>
						</div> 
						<!-- <div class="menu-available" ng-init="menu_timing = '';day_name ={{ json_encode(day_name()) }}">
							<p>{{ trans('messages.store.when_is_this_menu_available') }}</p>
							<div class="d-md-flex menu-view select-day" ng-repeat="available in menu_timing">
								<div class="select">
									<select id="day-@{{$index}}" ng-model="available.day" name="menu_timing_day[]">
										<option value="">{{ trans('messages.store_dashboard.select_a_day') }}</option>
										<option value="@{{key}}" ng-selected="available.day==key" ng-repeat="(key,value) in day_name track by $index" ng-if="(key | checkKeyValueUsedInStack : 'day': menu_timing) || available.day==key">
											@{{value}}
										</option>
									</select>
								</div>
								<div class="added-times ml-3 align-items-start">
									<div class="select-time d-flex">
										<div class="select">
											{!! Form::select('menu_timing_start_time[]',time_data('time'),'', ['placeholder'=>trans('admin_messages.select'),'data-end_time'=>'@{{available.end_time}}','ng-model'=>'available.start_time','id'=>'start-@{{$index}}']); !!}
										</div>
										<span class="m-2">{{ trans('messages.store.to') }}</span>
										<div class="select">
											{!! Form::select('menu_timing_end_time[]',time_data('time'),'', ['placeholder'=>trans('admin_messages.select'),'ng-model'=>'available.end_time','id'=>'end-@{{$index}}']); !!}
										</div>
										<a href="javascript:void(0)" ng-click="remove_menu_time($index,available.id)" class="icon icon-rubbish-bin d-inline-block m-2 mr-0 text-danger"></a>
									</div>
								</div>
							</div>
							<a href="javascript:void(0)" class="theme-color text-uppercase d-inline-block mt-3" ng-click="add_menu_time()" ng-show="menu_timing.length < 7">
								<i class="icon icon-add mr-3"></i>
								{{ trans('messages.store.add_more') }}
							</a>
						</div> -->
						<div class="mt-3 pt-4 modal-footer px-0 text-right">
							<button data-dismiss="modal" class="btn btn-primary theme-color">{{ trans('messages.store.cancel') }}</button>
							<button type="submit" class="btn btn-theme ml-2" ng-click="update_menu_time()">{{ trans('messages.store.submit') }}</button>
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
					<h3 class="modal-title">{{ trans('messages.store.delete_this') }} <span ng-if="delete_name=='menu'">{{ trans('messages.store.menu') }}</span><span ng-if="delete_name=='category'">{{ trans('messages.store.category') }}</span><span ng-if="delete_name=='item'">{{ trans('messages.store.item') }}</span>{{ trans('messages.store.ques_mark') }}</h3>
				</div>
				<div class="modal-body">
					<p>{{ trans('messages.store.are_you_sure_to_delete_this') }} <span ng-if="delete_name=='menu'">{{ trans('messages.store.menu') }}</span><span ng-if="delete_name=='category'">{{ trans('messages.store.category') }}</span><span ng-if="delete_name=='item'">{{ trans('messages.store.item') }}</span>. {{ trans('messages.store.this_action_cannot_undone') }}</p>
					<p class="text-danger delete_item_msg"> </p>
				</div>
				<div class="modal-footer text-right">
					<button type="reset" data-dismiss="modal" class="btn btn-primary theme-color">{{ trans('messages.store.cancel') }}</button>
					<button type="submit" class="btn btn-theme ml-2" data-dismiss="modal" ng-click="remove_item(remove_id,delete_name)">{{ trans('messages.store.submit') }}</button>
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
					<button data-dismiss="modal" type="reset" class="btn">{{ trans('messages.store.cancel') }}</button>
				</div>
			</div>
		</div>
	</div>
</main>
@stop
@push('scripts')
<script type="text/javascript">
	$('#chooses_file').click(function(){
		$('#myFileField').trigger('click');
		$('#myFileField').change(function(evt) {
			var fileName = $(this).val().split('\\')[$(this).val().split('\\').length - 1];
			$('#chooses_file').css("background-color","#f68202");
			$('#chooses_file').css("color","#fff");        		
			$('#banner_name').text(Lang.get('js_messages.file.file_attached'));
			$('#file_text').text(fileName);
			$('span.upload_text').attr('title',fileName)
		});
	});
</script>
@endpush    