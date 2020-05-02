<?php
/**
 * StoreController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    StoreController
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataTableBase;
use App\Models\Category;
use App\Models\File;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuItemModifier;
use App\Models\MenuItemModifierItem;
use App\Models\MenuTime;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PayoutPreference;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\StoreDocument;
use App\Models\StoreOffer;
use App\Models\StorePreparationTime;
use App\Models\StoreTime;
use App\Models\User;
use App\Models\SiteSettings;
use App\Models\UserAddress;
use App\Models\UserPaymentMethod;
use App\Models\UsersPromoCode;
use App\Models\Wishlist;
use App\Traits\FileProcessing;
use DataTables;
use Hash;
use Illuminate\Http\Request;
use Storage;
use Validator;
use App\Models\MenuTranslations;
use App\Models\MenuCategoryTranslations;
use App\Models\MenuItemTranslations;
class StoreController extends Controller {

	use FileProcessing;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function add_store(Request $request) {
		if ($request->getMethod() == 'GET') {
			
			
			$this->view_data['form_action'] = route('admin.add_store');
			$this->view_data['form_name'] = trans('admin_messages.add_store');
			$this->view_data['category'] = Category::where('status', 1)->pluck('name', 'id');
			$this->view_data['store_category'] = array();

			return view('admin/store/add_store', $this->view_data);
		} else {
			
			$all_variables = request()->all();
			if ($all_variables['date_of_birth']) {
				$all_variables['convert_dob'] = date('Y-m-d', strtotime($all_variables['date_of_birth']));
			}

			$rules = array(
				'name' => 'required',
				'store_name' => 'required',
				'store_description' => 'required',
				// 'min_time' => 'required',
				// 'max_time' => 'required|after:min_time',
				'category' => 'required',
				'password' => 'required|min:6',
				'convert_dob' => 'required|before:18 years ago',
				'phone_country_code' => 'required',
				'store_status' => 'required',
				'user_status' => 'required',
				'price_rating' => 'required',
				'address' => 'required',
				'banner_image' => 'required|image|mimes:jpg,png,jpeg,gif',
				'email' => 'required|email|unique:user,email,NULL,id,type,1',
				'mobile_number' => 'required|regex:/^[0-9]+$/|min:6|unique:user,mobile_number,NULL,id,type,1',
			);

			// Add Admin User Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.full_name'),
				'store_name' => trans('admin_messages.store_name'),
				'store_description' => trans('admin_messages.store_description'),
				// 'min_time' => trans('admin_messages.min_time'),
				// 'max_time' => trans('admin_messages.max_time'),
				'email' => trans('admin_messages.email'),
				'password' => trans('admin_messages.password'),
				'convert_dob' => trans('admin_messages.date_of_birth'),
				'phone_country_code' => trans('admin_messages.country_code'),
				'mobile_number' => trans('admin_messages.mobile_number'),
				'store_status' => trans('admin_messages.store_status'),
				'price_rating' => trans('admin_messages.price_rating'),
				'user_status' => trans('admin_messages.user_status'),
				'banner_image' => trans('admin_messages.banner_image'),
				'address' => trans('admin_messages.address'),
			);

			if ($request->document) {
				foreach ($request->document as $key => $value) {
					$rules['document.' . $key . '.name'] = 'required';
					$rules['document.' . $key . '.document_file'] = 'required|mimes:jpg,png,jpeg,pdf';

					$niceNames['document.' . $key . '.name'] = trans('admin_messages.name');
					$niceNames['document.' . $key . '.document_file'] = 'Please upload the file like jpg,png,jpeg,pdf format';
				}
			}
			$messages = array(
				'convert_dob.before' => 'Age must be 18 or older',
			);
			
			$validator = Validator::make($all_variables, $rules,$messages);
			$validator->setAttributeNames($niceNames);

			$validator->after(function ($validator) use ($request) {
				if ($request->latitude == '' || $request->longitude == '') {
		            $validator->errors()->add('address', 'Invalid address');
		        }
		    });

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$store = new User;
				$store->name = $request->name;
				$store->email = $request->email;
				$store->password = Hash::make($request->password);
				$store->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
				$store->country_code = $request->phone_country_code;
				$store->mobile_number = $request->mobile_number;
				$store->status = $request->user_status;
				$store->type = 1;
				$store->save();

				$new_store = new Store;
				$new_store->user_id = $store->id;
				$new_store->name = $request->store_name;
				$new_store->description = $request->store_description;
				// $new_store->min_time = $request->min_time;
				$new_store->max_time = '00:50:00';
				$new_store->currency_code = DEFAULT_CURRENCY;
				$new_store->price_rating = $request->price_rating;
				$new_store->status = $request->store_status;
				$new_store->save();

				foreach ($request->category as $value) {
					if ($value) {

						$cousine = StoreCategory::where('store_id', $new_store->id)->where('category_id', $value)->first();
						if ($cousine == '') {
							$cousine = new StoreCategory;
						}

						$cousine->store_id = $new_store->id;
						$cousine->category_id = $value;
						$cousine->status = 1;
						$cousine->save();
					}
				}

				$address = new UserAddress;
				$address->user_id = $store->id;
				$address->address = $request->address;
				$address->country_code = $request->country_code;
				$address->postal_code = $request->postal_code;
				$address->city = $request->city;
				$address->state = $request->state;
				$address->street = $request->street;
				$address->latitude = $request->latitude;
				$address->longitude = $request->longitude;
				$address->default = 1;
				$address->save();

				//file

				if ($request->file('banner_image')) {

					$file = $request->file('banner_image');

					$file_path = $this->fileUpload($file, 'public/images/store/' . $new_store->id);

					$this->fileSave('store_banner', $new_store->id, $file_path['file_name'], '1');
					$orginal_path = Storage::url($file_path['path']);
					$size = get_image_size('store_image_sizes');
					foreach ($size as $value) {
						$this->fileCrop($orginal_path, $value['width'], $value['height']);
					}

				}
				//documents
				if ($request->document) {
					foreach ($request->document as $key => $value) {

						$file = $value['document_file'];
						$file_path = $this->fileUpload($file, 'public/images/store/' . $new_store->id . '/documents');
						$file_id = $this->fileSave('store_document', $new_store->id, $file_path['file_name'], '1', 'multiple');
						$store_document = new StoreDocument;
						$store_document->name = $value['name'];
						$store_document->document_id = $file_id;
						$store_document->store_id = $new_store->id;
						$store_document->save();
					}
				}

				flash_message('success', trans('admin_messages.added_successfully'));
				return redirect()->route('admin.view_store');
			}

		}
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view() {
		$this->view_data['form_name'] = trans('admin_messages.store_management');
		return view('admin.store.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function all_stores() {

		$stores = User::where('type', 1);
		$filter_type = request()->filter_type;

		$from = date('Y-m-d' . ' 00:00:00', strtotime(change_date_format(request()->from_dates)));
		if (request()->to_dates != '') {
			$to = date('Y-m-d' . ' 23:59:59', strtotime(change_date_format(request()->to_dates)));

			// $stores = $stores->whereBetween('created_at', array($from, $to));
			$stores = $stores->where('created_at', '>=', $from)->where('created_at', '<=', $to);
		}
		$stores = $stores->get();
		// dd($stores);
		$datatable = DataTables::of($stores)
			->addColumn('id', function ($stores) {
				return @$stores->id;
			})
			->addColumn('name', function ($stores) {
				return @$stores->name;
			})
			->addColumn('store_name', function ($stores) {
				return @$stores->store->name;
			})
			->addColumn('email', function ($stores) {
				return @$stores->email;
			})
			->addColumn('user_status', function ($stores) {
				return @$stores->status_text;
			})
			->addColumn('store_status', function ($stores) {
				return @$stores->store->status_text;
			})
			->addColumn('created_at', function ($stores) {
				return @$stores->created_at;
			})
			->addColumn('recommend', function ($stores) {
				if ($stores->status && $stores->status!=4&& $stores->status!=5 ) {
					$class = @$stores->store->recommend == 1 ? "success" : "danger";
					return '<a class="' . $class . '"  href="' . route('admin.recommend', ['id' => @$stores->store->id]) . '" ><span>' . @$stores->store->recommend_status . '</span></a>';
				}
				return @$stores->store->recommend_status;
			})
			->addColumn('action', function ($stores) {
				return '<a title="' . trans('admin_messages.edit_preparation_time') . '" href="' . route('admin.edit_preparation_time', $stores->id) . '" ><i class="material-icons">alarm_add</i></a>&nbsp;<a title="' . trans('admin_messages.menu_category') . '" href="' . route('admin.menu_category', $stores->id) . '" ><i class="material-icons">category</i></a>&nbsp;<a title="' . trans('admin_messages.edit_open_time') . '" href="' . route('admin.edit_open_time', $stores->id) . '" ><i class="material-icons">alarm_on</i></a>&nbsp;<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.edit_store', $stores->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_store', $stores->id) . '"><i class="material-icons">close</i></a>';
			})
			->escapeColumns('recommend');
		$columns = ['id', 'name', 'store_name', 'email', 'user_status', 'store_status', 'recommend', 'created_at'];
		$base = new DataTableBase($stores, $datatable, $columns, 'Stores');
		return $base->render(null);

	}

	public function recommend() {
		$store = Store::find(request()->id);
		if ($store->recommend == 1) {
			$store->recommend = 0;
		} else {
			$store->recommend = 1;
		}

		$store->save();
		flash_message('success', trans('admin_messages.updated_successfully'));
		return redirect()->route('admin.view_store');
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		$store_id = Store::where('user_id', $request->id)->first();
		$order = Order::where('store_id', $store_id->id)->get();
		if (count($order) > 0) {
			flash_message('danger', 'You can\'t delete this store user. This store has some orders');
			return redirect()->route('admin.view_store');
		}

		if (count($store_id) > 0) {

			$menu_item_modifier_item = [];
			$menu_item_modifier = [];
			$menu_item = [];
			$menu_time = [];
			$menu_category = [];
			//details fetch

			//menu and menu category
			$menu = Menu::where('store_id', $store_id->id)->get();
			if (count($menu) > 0) {
				foreach ($menu as $key => $value) {
					$menu_category[$key] = MenuCategory::where('menu_id', $value->id)->get();
				}
			}

			//menu time
			if (count($menu) > 0) {
				foreach ($menu as $key => $value) {
					$menu_time[$key] = MenuTime::where('menu_id', $value->id)->get();
				}
			}

			//menu item
			if (count($menu_category) > 0) {
				foreach ($menu_category as $key => $value) {

					foreach ($value as $key1 => $value1) {

						$menu_item[$key][$key1] = MenuItem::where('menu_category_id', $value1->id)->get();
					}
				}
			}

			//menu item modifier
			if (count($menu_item) > 0) {
				foreach ($menu_item as $key => $value) {

					foreach ($value as $key1 => $value1) {

						foreach ($value1 as $key2 => $value2) {

							$menu_item_modifier[$key][$key1][$key2] = MenuItemModifier::where('menu_item_id', $value2->id)->get();
						}
					}
				}
			}

			//menu item modifier item
			if (count($menu_item_modifier) > 0) {
				foreach ($menu_item_modifier as $key => $value) {

					foreach ($value as $key1 => $value1) {

						foreach ($value1 as $key2 => $value2) {

							foreach ($value2 as $key3 => $value3) {

								$menu_item_modifier_item[$key][$key1][$key2][$key3] = MenuItemModifierItem::where('menu_item_modifier_id', $value3->id)->get();
							}

						}
					}
				}
			}

			$store_category = StoreCategory::where('store_id', $store_id->id)->get();
			$store_document = StoreDocument::where('store_id', $store_id->id)->get();
			$store_offer = StoreOffer::where('store_id', $store_id->id)->get();
			$store_preparation_time = StorePreparationTime::where('store_id', $store_id->id)->get();
			$store_time = StoreTime::where('store_id', $store_id->id)->get();
			$wishlist = Wishlist::where('store_id', $store_id->id)->get();

			//delete fetched details

			//menu item modifier item
			if (count($menu_item_modifier_item) > 0) {
				foreach ($menu_item_modifier_item as $key => $value) {

					foreach ($value as $key1 => $value1) {

						foreach ($value1 as $key2 => $value2) {

							foreach ($value2 as $key3 => $value3) {

								foreach ($value3 as $key4 => $value4) {

									if (count($value4) > 0) {
										$value4->delete($value4->id);
									}

								}
							}

						}
					}
				}
			}

			//menu item modifier
			if (count($menu_item_modifier) > 0) {
				foreach ($menu_item_modifier as $key => $value) {

					foreach ($value as $key1 => $value1) {

						foreach ($value1 as $key2 => $value2) {

							foreach ($value2 as $key3 => $value3) {

								if (count($value3) > 0) {
									$value3->delete($value3->id);
								}
							}
						}
					}
				}
			}

			//menu item
			if (count($menu_item) > 0) {

				foreach ($menu_item as $key => $value) {

					foreach ($value as $key1 => $value1) {

						foreach ($value1 as $key2 => $value2) {

							if (count($value2) > 0) {
								$value2->delete($value2->id);
							}
						}
					}
				}
			}

			//menu time
			if (count($menu_time) > 0) {
				foreach ($menu_time as $key => $value) {

					foreach ($value as $key1 => $value1) {

						if (count($value1) > 0) {
							$value1->delete($value1->id);
						}
					}
				}
			}

			// menu category
			if (count($menu_category) > 0) {
				foreach ($menu_category as $key => $value) {

					foreach ($value as $key1 => $value1) {

						if (count($value1) > 0) {
							$value1->delete($value1->id);
						}
					}
				}
			}

			// menu
			if (count($menu) > 0) {
				foreach ($menu as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			// store category
			if (count($store_category) > 0) {
				foreach ($store_category as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			// store document
			if (count($store_document) > 0) {
				foreach ($store_document as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			// store offer
			if (count($store_offer) > 0) {
				foreach ($store_offer as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			// store preparation time
			if (count($store_preparation_time) > 0) {
				foreach ($store_preparation_time as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			// store time
			if (count($store_time) > 0) {
				foreach ($store_time as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			//wishlist

			if (count($wishlist) > 0) {
				$wishlist->delete($wishlist->id);
			}

			// store
			if (count($store_id) > 0) {

				$store_id->delete($store_id->id);

			}
		}

		//user details

		$user = User::whereId($request->id)->first();

		if (count($user) > 0) {

			$payout_preference = PayoutPreference::where('user_id', $request->id)->get();
			$user_payment_method = UserPaymentMethod::where('user_id', $request->id)->get();
			$user_promo_code = UsersPromoCode::where('user_id', $request->id)->get();
			$user_address = UserAddress::where('user_id', $request->id)->get();

			//payout preference
			if (count($payout_preference) > 0) {
				foreach ($payout_preference as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			//user payment method
			if (count($user_payment_method) > 0) {
				foreach ($user_payment_method as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			//user promo code
			if (count($user_promo_code) > 0) {
				foreach ($user_promo_code as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			//user address
			if (count($user_address) > 0) {
				foreach ($user_address as $key => $value) {

					if (count($value) > 0) {
						$value->delete($value->id);
					}
				}
			}

			$user->delete($request->id);
			flash_message('success', trans('admin_messages.deleted_successfully'));
			return redirect()->route('admin.view_store');

		}

	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit_store(Request $request) {

		if ($request->getMethod() == 'GET') {
			$this->view_data['form_name'] = trans('admin_messages.edit_store');
			$this->view_data['form_action'] = route('admin.edit_store', $request->id);
			$this->view_data['store'] = User::where('id', $request->id)->firstOrFail();
			$this->view_data['category'] = Category::where('status', 1)->pluck('name', 'id');
			$this->view_data['store']->store()->firstOrFail();
			
			$this->view_data['store_document'] = $this->view_data['store']->store->store_document()->with('file')->get();
			$this->view_data['store_category'] = $this->view_data['store']->store->store_category()->pluck('category_id', 'id')->toArray();
			return view('admin/store/add_store', $this->view_data);
		} else {
			
			$all_variables = request()->all();
			if ($all_variables['date_of_birth']) {
				$all_variables['convert_dob'] = date('Y-m-d', strtotime($all_variables['date_of_birth']));
			}

			$rules = array(
				'name' => 'required',
				'store_name' => 'required',
				'store_description' => 'required',
				// 'min_time' => 'required',
				// 'max_time' => 'required|after:min_time',
				'category' => 'required',
				'email' => 'required|email|unique:user,email,' . $request->id,
				'convert_dob' => 'required|before:18 years ago',
				'store_status' => 'required',
				'price_rating' => 'required',
				'user_status' => 'required',
				'phone_country_code' => 'required',
				'address' => 'required',
				'banner_image' => 'image|mimes:jpg,png,jpeg,gif',
				'email' => 'required|email|unique:user,email,' . $request->id . ',id,type,1',
				'mobile_number' => 'required|regex:/^[0-9]+$/|min:6|unique:user,mobile_number,' . $request->id . ',id,type,1',
			);
			if ($request->password) {
				$rules['password'] = 'min:6';
			}
			// Add Admin User Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.full_name'),
				'store_name' => trans('admin_messages.store_name'),
				'store_description' => trans('admin_messages.store_description'),
				// 'min_time' => trans('admin_messages.min_time'),
				// 'max_time' => trans('admin_messages.max_time'),
				'email' => trans('admin_messages.email'),
				'password' => trans('admin_messages.password'),
				'convert_dob' => trans('admin_messages.date_of_birth'),
				'phone_country_code' => trans('admin_messages.country_code'),
				'mobile_number' => trans('admin_messages.mobile_number'),
				'store_status' => trans('admin_messages.store_status'),
				'price_rating' => trans('admin_messages.price_rating'),
				'user_status' => trans('admin_messages.user_status'),
				'address' => trans('admin_messages.address'),
				'banner_image' => trans('admin_messages.banner_image'),
			);

			if ($request->document) {
				// dd($request->document);
				foreach ($request->document as $key => $value) {

					$rules['document.' . $key . '.name'] = 'required';
					if (@$value['document_file'] && $value['id'] != '') {
						$rules['document.' . $key . '.document_file'] = 'mimes:jpg,png,jpeg,pdf';
					} elseif ($value['id'] == '') {
						$rules['document.' . $key . '.document_file'] = 'required|mimes:jpg,png,jpeg,pdf';
					}

					$niceNames['document.' . $key . '.name'] = trans('admin_messages.document_name');
					$niceNames['document.' . $key . '.document_file'] = 'Please upload the file like jpg,png,jpeg,pdf format';
				}
			}
			$messages = array(
				'convert_dob.before' => 'Age must be 18 or older',
			);
			
			$validator = Validator::make($all_variables, $rules,$messages);
			$validator->setAttributeNames($niceNames);

			$validator->after(function ($validator) use ($request) {
				if ($request->latitude == '' || $request->longitude == '') {
		            $validator->errors()->add('address', 'Invalid address');
		        }
		    });
		    
			if ($validator->fails()) {
				// dd($validator);
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$store = User::find($request->id);
				$store->name = $request->name;
				$store->email = $request->email;
				if ($request->password) {
					$store->password = Hash::make($request->password);
				}

				$store->date_of_birth = $all_variables['convert_dob'];
				$store->country_code = $request->phone_country_code;
				$store->mobile_number = $request->mobile_number;
				$store->status = $request->user_status;
				$store->type = 1;
				$store->save();

				$new_store = Store::where('user_id', $store->id)->first();
				$new_store->name = $request->store_name;
				$new_store->description = $request->store_description;
				// $new_store->min_time = $request->min_time;
				// $new_store->max_time = $request->max_time;
				$new_store->currency_code = DEFAULT_CURRENCY;
				$new_store->price_rating = $request->price_rating;
				$new_store->status = $request->store_status;
				if ($request->user_status == 0) {
					$new_store->recommend = 0;
				}

				$new_store->save();

				foreach ($request->category as $value) {
					if ($value) {

						$cousine = StoreCategory::where('store_id', $new_store->id)->where('category_id', $value)->first();
						if ($cousine == '') {
							$cousine = new StoreCategory;
						}

						$cousine->store_id = $new_store->id;
						$cousine->category_id = $value;
						$cousine->status = 1;
						$cousine->save();
					}
				}
				//delete cousine
				$store_time = StoreCategory::where('store_id', $new_store->id)->whereNotIn('category_id', $request->category)->delete();

				$address = UserAddress::where('user_id', $store->id)->default()->first();
				if ($address == '') {
					$address = new UserAddress;
				}

				$address->user_id = $store->id;
				$address->address = $request->address;
				$address->country_code = $request->country_code;
				$address->postal_code = $request->postal_code;
				$address->city = $request->city;
				$address->state = $request->state;
				$address->street = $request->street;
				$address->latitude = $request->latitude;
				$address->longitude = $request->longitude;
				$address->default = 1;
				$address->save();

				//file

				if ($request->file('banner_image')) {

					$file = $request->file('banner_image');

					$file_path = $this->fileUpload($file, 'public/images/store/' . $new_store->id);

					$this->fileSave('store_banner', $new_store->id, $file_path['file_name'], '1');
					$orginal_path = Storage::url($file_path['path']);
					$size = get_image_size('store_image_sizes');
					foreach ($size as $value) {
						$this->fileCrop($orginal_path, $value['width'], $value['height']);
					}

				}

				//documents
				if ($request->document) {
					$avaiable_id = array_column($request->document, 'id');
				} else {
					$avaiable_id = array();
				}

				$avaiable_id = array_filter($avaiable_id);
				StoreDocument::whereNotIn('id', $avaiable_id)->where('store_id',$new_store->id)->delete();
				//documents
				if ($request->document) {
					foreach ($request->document as $key => $value) {

						if ($value['id']) {
							$store_document = StoreDocument::find($value['id']);
						} else {
							$store_document = new StoreDocument;
						}

						if (@$value['document_file']) {
							$file = $value['document_file'];
							$file_path = $this->fileUpload($file, 'public/images/store/' . $new_store->id . '/documents');
							$file_id = $this->fileSave('store_document', $new_store->id, $file_path['file_name'], '1', 'multiple');
							$store_document->document_id = $file_id;
						}
						$store_document->name = $value['name'];
						$store_document->store_id = $new_store->id;
						$store_document->save();

					}
				}

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.view_store');
			}

		}
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function menu_category() {
		$this->view_data['form_name'] = trans('admin_messages.store_menu');
		$store_id = request()->id;

		$this->view_data['store'] = $store = Store::where('user_id', $store_id)->first();
		$menu = Menu::with(['menu_category' => function ($query) {

			$query->with('all_menu_item');

		}])->where('store_id', @$this->view_data['store']->id)->get();

		$this->view_data['menu'] = $menu->map(function ($item) {

			$menu_category = $item['menu_category']->map(function ($item) {

				$menu_item = $item['all_menu_item']->map(function ($item) {

					return [

						'menu_item_id' => $item['id'],
						'menu_item_name' => $item['name'],
						'menu_item_desc' => $item['description'],
						'menu_item_price' => $item['price'],
						'menu_item_tax' => $item['tax_percentage'],
						'menu_item_type' => $item['type'],
						'menu_item_status' => $item['status'],
						'item_image' => is_object($item['menu_item_thump_image'])?'':$item['menu_item_thump_image'],
						'translations' =>$item->translations,	
					];

				})->toArray();

				return [

					'menu_category_id' => $item['id'],
					'menu_category' => $item['name'],
					'menu_item' => $menu_item,
					'translations'=>$item->translations,

				];

			})->toArray();

			return [
				'menu_id' => $item['id'],
				'menu' => $item['name'],
				'menu_category' => $menu_category,

			];
		});

		return view('admin.menu_category', $this->view_data);
	}

	public function update_category(Request $request) {

		if ($request->action == 'edit') {

			$category = MenuCategory::find($request->id);
			$category->name = $request->name;
			$category->save();
			
			MenuCategoryTranslations::where('menu_category_id',$category->id)->delete();

                foreach($request->translations ?: array() as $translation_data) { 
                	
                    $translation = $category->getTranslationById(@$translation_data['locale'], $category->id);
                    $translation->name = $translation_data['name'];                    
                    $translation->save();
                }
		
			$data['category_name'] = $category->name;
			return $data;
		} else {

			$category = new MenuCategory;
			$category->name = $request->name;
			$category->menu_id = $request->menu_id;
			$category->save();
			$data['category_name'] = $category->name;
			$data['category_id'] = $category->id;

			foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $category->getTranslationById(@$translation_data['locale'], $category->id);
                    $translation->name = $translation_data['name'];                    
                    $translation->save();
                }
            $data['translations']  = $category->translations;  
			return $data;

		}

	}

	public function menu_time(Request $request) {

		$data['menu_time'] = MenuTime::where('menu_id', $request->id)->get();
		$data['translations'] = Menu::where('id', $request->id)->get();
		return $data;

	}

	public function update_menu_time() {
		$request = request();

		$store_id = $request->store_id;
		$store = Store::where('user_id', $store_id)->first();

		$id = $store->id;
		$menu_time = $request->menu_time;
		$menu_id = $request->menu_id;

		if ($menu_id) {

			$store_menu = Menu::where('store_id', $id)->where('id', $menu_id)->first();
			$store_menu->name = $request->menu_name;
			$store_menu->save();
			  	
			  	MenuTranslations::where('menu_id',$menu_id)->delete();

                foreach($request->translations ?: array() as $translation_data) { 
                	
                    $translation = $store_menu->getTranslationById(@$translation_data['locale'], $store_menu->id);
                    $translation->name = $translation_data['name'];                    
                    $translation->save();
                }

		} else {

			$store_menu = new Menu;
			$store_menu->name = $request->menu_name;
			$store_menu->store_id = $id;
			$store_menu->save();

			$menu_id = $store_menu->id;

			//translations
			//
			foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $store_menu->getTranslationById(@$translation_data['locale'], $store_menu->id);
                    $translation->name = $translation_data['name'];                    
                    $translation->save();
                }

		}
		$in_day = array_column($menu_time, 'day');
		foreach ($menu_time as $time) {

			if ($time['id'] != '') {

				$menu_time = MenuTime::find($time['id']);
				$menu_time->day = $time['day'];
				$menu_time->start_time = $time['start_time'];
				$menu_time->end_time = $time['end_time'];
				$menu_time->save();

			} else {

				$menu_time = new MenuTime;
				$menu_time->menu_id = $menu_id;
				$menu_time->store_id = $id;
				$menu_time->day = $time['day'];
				$menu_time->start_time = $time['start_time'];
				$menu_time->end_time = $time['end_time'];
				$menu_time->save();

			}

		}

		MenuTime::where('menu_id', $menu_id)->whereNotIn('day', $in_day)->delete();
		if ($request->menu_id) {
			return ['message' => 'success', 'menu_name' => $store_menu->name];
		} else {
			$menu = Menu::with(['menu_category' => function ($query) {

				$query->with('menu_item');

			}])->where('store_id', $id)->get();
		}

		$data['menu'] = $menu->map(function ($item) {

			$menu_category = $item['menu_category']->map(function ($item) {

				$menu_item = $item['menu_item']->map(function ($item) {

					return [

						'menu_item_id' => $item['id'],
						'menu_item_name' => $item['name'],
						'menu_item_desc' => $item['description'],
						'menu_item_price' => $item['price'],
						'menu_item_tax' => $item['tax_percentage'],

					];

				})->toArray();

				return [

					'menu_category_id' => $item['id'],
					'menu_category' => $item['name'],
					'menu_item' => $menu_item,

				];

			})->toArray();

			return [
				'menu_id' => $item['id'],
				'menu' => $item['name'],
				'menu_category' => $menu_category,

			];
		});

		return $data;

	}

	public function update_menu_item(Request $request) {

		$store_id = $request->store_id;
		$store_id = Store::where('user_id', $store_id)->first()->id;
		if ($request['menu_item_id']) {
			
			$menu_item = MenuItem::find($request['menu_item_id']);
			$menu_item->name = $request['menu_item_name'];
			$menu_item->description = $request['menu_item_desc'];
			$menu_item->price = $request['menu_item_price'];
			$menu_item->tax_percentage = $request['menu_item_tax'];
			$menu_item->type = $request['menu_item_type'];
			$menu_item->status = $request['menu_item_status'];
			$menu_item->save();

			MenuItemTranslations::where('menu_item_id',$menu_item->id)->delete();
			$getTranslation = $request->item_translations;
				\Log::info('reach check'.$getTranslation);
				if($getTranslation != '[]'){
					$item_translations =	json_decode($getTranslation);
				\Log::info('reach menu_item'.$item_translations[0]->name);
                foreach($item_translations ?: array() as $translation_data) { 
                	
                    $translation = $menu_item->getTranslationById($translation_data->locale,$menu_item->id);
                    $translation->name = $translation_data->name;                    
                    $translation->description = $translation_data->description;
                    $translation->save();
                }	
				}
				

		} else {

			$menu_item = new MenuItem;
			$menu_item->name = $request['menu_item_name'];
			$menu_item->description = $request['menu_item_desc'];
			$menu_item->price = $request['menu_item_price'];
			$menu_item->tax_percentage = $request['menu_item_tax'];
			$menu_item->type = $request['menu_item_type'];
			$menu_item->status = $request['menu_item_status'];
			$menu_item->menu_id = $request['menu_id'];
			$menu_item->menu_category_id = $request['category_id'];
			$menu_item->save();

				$getTranslation = $request->item_translations;
				\Log::info('reach check'.$getTranslation);
				if($getTranslation != '[]'){
					$item_translations =	json_decode($getTranslation);
				\Log::info('reach menu_item'.$item_translations[0]->name);
                foreach($item_translations ?: array() as $translation_data) { 
                	
                    $translation = $menu_item->getTranslationById($translation_data->locale,$menu_item->id);
                    $translation->name = $translation_data->name;                    
                    $translation->description = $translation_data->description;
                    $translation->save();
                }	
				}

			$data = ['menu_item_id' => $menu_item->id,
				'menu_item_name' => $menu_item->name,
				'menu_item_desc' => $menu_item->description,
				'menu_item_price' => $menu_item->price,
				'menu_item_tax' => $menu_item->tax_percentage,
				'menu_item_type' => $menu_item->type,
				'menu_item_status' => $menu_item->status,
				'translations'=>$menu_item->translations];


		}

		//file
		if ($request->file('file')) {

			$file = $request->file('file');

			$file_path = $this->fileUpload($file, 'public/images/store/' . $store_id . '/menu_item');

			$this->fileSave('menu_item_image', $menu_item->id, $file_path['file_name'], '1');

			$orginal_path = Storage::url($file_path['path']);
			$size = get_image_size('item_image_sizes');
			foreach ($size as $new_size) {
				$this->fileCrop($orginal_path, $new_size['width'], $new_size['height']);
			}

		}

		$menu_item = MenuItem::find($menu_item->id);
		$menu_image = is_object($menu_item->menu_item_thump_image)?'':$menu_item->menu_item_thump_image;
		if ($request['menu_item_id']) {
			$data['edit_menu_item_image'] = $menu_image;
		} else {
			$data['item_image'] = $menu_image;
		}

		return $data;

	}

	public function remove_menu_time(Request $request) {

		$menu_time = MenuTime::find($request->id);

		if ($menu_time) {
			$menu_time->delete();

		}

	}

	public function delete_menu(Request $request) {

		if ($request->category == 'item') {
			$key = $request->key;

			$menu_item_id = $request->menu['menu_category'][$request->category_index]['menu_item'][$key]['menu_item_id'];

			$find_value_in_order = OrderItem::where('menu_item_id', $menu_item_id)->first();
			if ($find_value_in_order) {
				$data['status'] = 'false';
				$data['msg'] = 'This item use in order so can\'t delete this';
				return $data;
			}

			$delete_menu_item = MenuItem::find($menu_item_id)->delete();

		} else if ($request->category == 'category') {
			$key = $request->key;

			$menu_category_id = $request->menu['menu_category'][$key]['menu_category_id'];
			$delete_menu_item = MenuItem::where('menu_category_id', $menu_category_id)->get();

			$find_value_in_order = OrderItem::whereIn('menu_item_id', $delete_menu_item->pluck('id'))->first();
			if ($find_value_in_order) {
				$data['status'] = 'false';
				$data['msg'] = 'This item use in order so can\'t delete this';
				return $data;
			}

			foreach ($delete_menu_item as $key => $value) {
				MenuItem::find($value->id)->delete();
			}

			$delete_menu_item = MenuCategory::find($menu_category_id)->delete();

		} else {

			$key = $request->key;

			$menu_id = $request->menu['menu_id'];

			$delete_menu_item = MenuItem::where('menu_id', $menu_id)->get();
			//find value
			$find_value_in_order = OrderItem::whereIn('menu_item_id', $delete_menu_item->pluck('id'))->first();
			if ($find_value_in_order) {
				$data['status'] = 'false';
				$data['msg'] = 'This item use in order so can\'t delete this';
				return $data;
			}

			//delete item
			MenuItem::whereIn('id', $delete_menu_item->pluck('id'))->delete();
			//delete category
			MenuCategory::where('menu_id', $menu_id)->delete();
			//delete time
			MenuTime::where('menu_id', $menu_id)->delete();
			//delete menu
			Menu::where('id', $menu_id)->delete();
			MenuTranslations::where('menu_id',$menu_id)->delete();
			$data['status'] = 'true';
			return $data;
		}

	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function open_time() {
		$request = request();
		$this->view_data['store'] = Store::where('user_id', $request->store_id)->firstOrFail();
		if ($request->getMethod() == 'GET') {

			$this->view_data['form_action'] = route('admin.edit_open_time', $request->store_id);
			$this->view_data['form_name'] = trans('admin_messages.edit_open_time');
			$this->view_data['open_time'] = StoreTime::where('store_id', $request->store_id)->first();

			$this->view_data['open_time'] = (count($this->view_data['store']->store_all_time) > 0) ? $this->view_data['store']->store_all_time()->get()->toArray() : [array('day' => '')];
			// dd($this->view_data['open_time'] );
			return view('admin/store/open_time', $this->view_data);
		} else {

			if (@count($request->time_id)) {
				StoreTime::whereNotIn('id', $request->time_id)->where('store_id', $this->view_data['store']->id)->delete();
			}
			foreach ($request->day as $key => $time) {

				if (@$request->time_id[$key]) {
					$store_insert = StoreTime::find($request->time_id[$key]);
				} else {
					$store_insert = new StoreTime;
				}

				$store_insert->start_time = ($request->start_time[$key]);
				$store_insert->end_time = ($request->end_time[$key]);
				$store_insert->day = $request->day[$key];
				$store_insert->status = $request->status[$key];
				$store_insert->store_id = $this->view_data['store']->id;
				$store_insert->save();

			}

		}

		flash_message('success', 'Updated successfully');
		return redirect()->route('admin.view_store');

	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function preparation_time() {
		$request = request();
		$this->view_data['store'] = Store::where('user_id', $request->store_id)->firstOrFail();
		if ($request->getMethod() == 'GET') {

			$this->view_data['preparation'] = StorePreparationTime::where('store_id', $this->view_data['store']->id)->get();

			$this->view_data['max_time'] = convert_minutes(Store::where('id', $this->view_data['store']->id)->first()->max_time);

			$this->view_data['form_action'] = route('admin.edit_preparation_time', $request->store_id);
			$this->view_data['form_name'] = trans('admin_messages.edit_preparation_time');

			// dd($this->view_data['open_time'] );
			return view('admin/store/preparation_time', $this->view_data);
		} else {

			$store = Store::find($this->view_data['store']->id);
			$store->max_time = convert_format($request->overall_max_time);
			$store->save();
			if (isset($request->day)) {
				foreach ($request->day as $key => $time) {

					if (isset($request->id[$key])) {
						$store_update = StorePreparationTime::find($request->id[$key]);
					} else {
						$store_update = new StorePreparationTime;
					}

					$store_update->from_time = $request->from_time[$key];
					$store_update->to_time = $request->to_time[$key];
					$store_update->max_time = convert_format($request->max_time[$key]);
					$store_update->day = $request->day[$key];
					$store_update->status = $request->status[$key];
					$store_update->store_id = $this->view_data['store']->id;
					$store_update->save();
					$available_id[] = $store_update->id;
				}

				if (isset($available_id)) {
					StorePreparationTime::whereNotIn('id', $available_id)->delete();
				}

				flash_message('success', 'Updated successfully');
			}
			return redirect()->route('admin.view_store');

		}

	}

}
