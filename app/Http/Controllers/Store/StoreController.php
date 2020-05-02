<?php

/**
 * StoreController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Store
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Mail\ForgotEmail;
use App\Models\Country;
use App\Models\Category;
use App\Models\Currency;
use App\Models\HomeSlider;
use App\Models\IssueType;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\MenuTime;
use App\Models\OrderItem;
use App\Models\Payout;
use App\Models\PayoutPreference;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\StoreDocument;
use App\Models\SiteSettings;
use App\Models\StorePreparationTime;
use App\Models\StoreTime;
use App\Models\User;
use App\Models\UserAddress;
use App\Traits\FileProcessing;
use Auth;
use Charts;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Mail;
use Session;
use Storage;
use Stripe;
use Validator;
use App\Models\MenuTranslations;
use App\Models\MenuCategoryTranslations;
use App\Models\MenuItemTranslations;
class StoreController extends Controller {
//
	use FileProcessing;
//signup page function

	public function signup(Request $request) {

		if (request()->getMethod() == 'GET') {

			$data['category'] = Category::Active()->get()->pluck('name', 'id');
			$data['country'] = Country::Active()->get();
			$data['slider'] = HomeSlider::where('status', 1)->type('store')->get();

			return view('store.signup', $data);

		} else {
			
			$rules = [

				'city' => 'required',
				'email' => 'required|email|unique:user,email,NULL,id,type,1',
			];

			$niceNames = array(
				'email' => trans('messages.driver.email'),
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {

				return back()->withErrors($validator)->withInput();

			}
			$user = new User;
			$user->name = $request->first_name . ' ' . $request->last_name;
			$user->email = $request->email;
			$user->mobile_number = $request->mobile_number;
			$user->type = 1;
			$user->country_code = $request->country;
			$user->status = 4; //pending status
			$user->password = bcrypt($request->password);
			$user->save();

			$user_address = new UserAddress;
			$user_address->street = $request->street;
			$user_address->city = $request->city;
			$user_address->state = $request->state;
			$user_address->country = Country::where('code', $request->country_code)->first()->name;
			$user_address->country_code = $request->country_code;
			$user_address->latitude = $request->latitude;
			$user_address->longitude = $request->longitude;
			$user_address->user_id = $user->id;
			$user_address->address = $request->address;
			$user_address->type = 1;
			$user_address->default = 1;
			$user_address->save();

			$store = new Store;
			$store->name = $request->name;
			$store->user_id = $user->id;
			$store->currency_code = DEFAULT_CURRENCY;
			$store->price_rating = 0;
			$store->recommend = 0;
			$store->status = 0;
			$store->max_time = '00:50:00';

			$store->save();

			$store_category = new StoreCategory;
			$store_category->store_id = $store->id;
			$store_category->category_id = $request->category;
			$store_category->status = 1;
			$store_category->save();
			if (Auth::guard('store')->attempt(['email' => $request->email, 'password' => $request->password, 'type' => 1])) {
				return redirect()->route('store.dashboard');
			}

			flash_message('danger', 'Try Again');
			return redirect()->route('store.login');

		}
	}

	public function thanks(Request $request) {

		return view('store.thanks');

	}

	public function login(Request $request) {

		if (request()->getMethod() == 'GET') {

			return view('store.login');

		}

		$value = $request->textInputValue;

		if (is_numeric($value)) {

			$column = 'mobile_number';
			$text = trans('messages.store_dashboard.number');

			$rules = array('textInputValue' => 'required');
			$nice_names = array('textInputValue' => trans('admin_messages.mobile_number'));

		} else {

			$column = 'email';
			$text = trans('messages.driver.email');
			$rules = array('textInputValue' => 'required|email');
			$nice_names = array('textInputValue' => trans('messages.driver.email'));
		}

		$validator = Validator::make(request()->all(), $rules);
		$validator->setAttributeNames($nice_names);

		if ($validator->fails()) {

			return back()->withErrors($validator)->withInput();

		} else {

			$user_check = User::where($column, $value)->get();

			if (count($user_check)) {

				Session::put('text', $column);
				Session::put('value', $value);

				return redirect()->route('store.password');
			} else {

				flash_message('danger', trans('messages.store_dashboard.not_found_your') . $text);
				return redirect()->route('store.login'); // Redirect to login page
			}

		}

	}

	public function password(Request $request) {

		if (request()->getMethod() == 'GET') {

			return view('store.password');
		}

		$text = Session::get('text');
		$value = Session::get('value');
		$password = $request->textInputPassword;

		if (Auth::guard('store')->attempt([$text => $value, 'password' => $password, 'type' => 1])) {

			$data['user_details'] = auth()->guard('store')->user();
			if ($data['user_details']->status === 0) {
				Auth::guard('store')->logout();
				flash_message('danger', trans('messages.store_dashboard.youre_disabled_by_admin_please_contact_admin'));
				return redirect()->route('store.login'); // Redirect to login page
			}
			return redirect()->route('store.dashboard');

		} else {
			flash_message('danger', trans('messages.store_dashboard.invalid_credentials'));
			return redirect()->route('store.login'); // Redirect to login page

		}

	}

	public function dashboard(Request $request) {

		session::forget('otp_confirm');

		$store_id = get_current_store_id();

		//chart start

		$last_seven_payouts = Payout::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(amount) as amount'))->where('user_id', get_current_login_user_id())->whereRaw('DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)')->groupBy(DB::raw('DATE(created_at)'))
			->get();
		$data['last_seven_total_payouts'] = 0;
		if (count($last_seven_payouts) > 0) {
			$data['last_seven_total_payouts'] = $last_seven_payouts->sum('amount');
		}

		$payout_array = $last_seven_payouts->toArray();
		$amount = array_column($payout_array, 'amount');
		$date = array_column($payout_array, 'date');

		if (count($payout_array)) {
			$data['seven_chart'] = Charts::multi('bar', 'material')
				->title("", false)
				->dimensions(0, 250)
				->template("material")
				->dataset('sales', $amount)
				->labels($date)
				->colors(['#43A422']);
		}

		$last_thirty_payouts = Payout::select(DB::raw('DATE(created_at) as date'), DB::raw('sum(amount) as amount'))->where('user_id', get_current_login_user_id())->whereRaw('DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)')->groupBy(DB::raw('DATE(created_at)'))
			->get();
		$data['last_thirty_total_payouts'] = 0;
		if (count($last_thirty_payouts) > 0) {
			$data['last_thirty_total_payouts'] = $last_thirty_payouts->sum('amount');
		}

		$last_thirty_payouts = $last_thirty_payouts->toArray();
		$amount1 = array_column($last_thirty_payouts, 'amount');
		$date1 = array_column($last_thirty_payouts, 'date');

		if (count($last_thirty_payouts)) {
			$data['thirty_chart'] = Charts::multi('bar', 'material')
				->title("", false)
				->dimensions(0, 250)
				->template("material")
				->dataset('sales', $amount1)
				->labels($date1)
				->colors(['#43A422']);
		}
//chart end

//store steps verification start
		$store = Store::find($store_id);
		$data['document'] = StoreDocument::where('store_id', $store_id)->get()->count();
		$data['open_time'] = StoreTime::where('store_id', $store_id)->where('status', 1)->get()->count();
		$data['profile_step'] = $store->profile_step;
		$data['payout_preference'] = PayoutPreference::where('user_id', get_current_login_user_id())->where('default', 'yes')->get()->count();
		$data['all_menu'] = Menu::with(['menu_category' => function ($query) {
			$query->with('menu_item');
		}])
			->whereHas('menu_category', function ($query) {
				$query->whereHas('menu_item', function ($query) {
					$query->where('status', '1');
				});
			})
			->where('store_id', $store_id)->get();

		$menu_id = $this->get_menu_item($data['all_menu']->toArray());

		$user = User::find(get_current_login_user_id());
		$data['menu'] = $data['all_menu']->count();
		if ($user->status === 4 || $user->status === 5) {
			if ($data['document'] && $data['open_time'] && $data['profile_step'] && $data['payout_preference'] && $data['menu']) {
				$user->status = 5; //waiting for approve
			} else {
				$user->status = 4;
			}
		}
		$user->save();
		$data['user'] = $user;
//store steps verification end

//store top sale  items start
		$data['top_sale_saven_days'] = OrderItem::selectRaw('*,count(menu_item_id) as total_times')->with(['order', 'menu_item'])
			->whereHas('order', function ($query) {
				$query->whereRaw('DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)')->where('status', 6);
			})
			->whereIn('menu_item_id', $menu_id['menu_item_id'])->groupBy('menu_item_id')->get();

		$data['top_sale_thirty_days'] = OrderItem::selectRaw('*,count(menu_item_id) as total_times')->with(['order', 'menu_item'])
			->whereHas('order', function ($query) {
				$query->whereRaw('DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)')->where('status', 6);
			})
			->whereIn('menu_item_id', $menu_id['menu_item_id'])->groupBy('menu_item_id')->get();
//store top sale  items end

//store item review start
		$review = $this->review_details($store_id);
		$json = json_encode($review);
		$review_array = json_decode($json, true);

		$data['review_column'] = $this->issue_column($review_array);
//store item review endd

//store store review start

		$retaurant_review = $store->all_review()->whereRaw("date(created_at) <= date_sub(now(), interval -3 month)")->get();
		$total_reviewe = $retaurant_review->sum('rating');
		$total_count_reviewe = $retaurant_review->count('id');
		$data['retauarnt_rating'] = 0;
		if ($total_count_reviewe > 0) {
			$data['retauarnt_rating'] = round(($total_reviewe / ($total_count_reviewe * 5)) * 100);
		}

//store store review endd

//store store order  start
		$retaurant_order = $store->order();
		$total_order = (clone $retaurant_order)->whereNotIn('status', ['1', '0'])->count('id');
		$data['accepted_rating'] = 0;
		$data['canceled_rating'] = 0;
		$accepted_order = (clone $retaurant_order)->where('accepted_at', '!=', '')->count('id');
		if ($total_order > 0) {
			$data['accepted_rating'] = round(($accepted_order / $total_order) * 100);
		}

		$canceled_order = (clone $retaurant_order)->where('cancelled_at', '!=', '')->count('id');
		if ($total_order > 0) {
			$data['canceled_rating'] = round(($canceled_order / $total_order) * 100);
		}

//store store order  end
		return view('store.dashboard', $data);

	}

	public function get_menu_item($menu) {
		$data['menu_item_array'] = [];
		$data['menu_item_id'] = [];
		foreach ($menu as $menu_category) {
			foreach ($menu_category['menu_category'] as $menu_item) {
				foreach ($menu_item['menu_item'] as $menu_item_val) {
					$data['menu_item_array'][] = $menu_item_val;
					$data['menu_item_id'][] = $menu_item_val['id'];
				}
			}
		}
		return $data;
	}

	public function issue_column($review) {
		$return_column[] = '';
		foreach ($review as $value) {

			if ($value['issues_id'] == '') {
				$value['prasantage'] = $prasantage = round(($value['thumbs'] / $value['count_thumbs']) * 100);
				$return_column[] = $value;
			} else {
				$column = explode(',', $value['issues']);
				$issue_id = explode(',', $value['issues_id']);
				foreach ($column as $issues) {
					$find = IssueType::where('name', $issues)->first()->id;
					$issue_id_filter = array_count_values($issue_id);
					$count = $issue_id_filter[$find];
					$value['issues_column'][$issues] = $count;
				}
				$value['prasantage'] = $prasantage = round(($value['thumbs'] / $value['count_thumbs']) * 100);
				$return_column[] = $value;
			}
		}
		return array_filter($return_column);

	}

	public function array_flatten($array) {
		if (!is_array($array)) {
			return FALSE;
		}
		$result = array();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$result = array_merge($result, array_flatten($value));
			} else {
				$result[$key] = $value;
			}
		}
		return $result;
	}

	public function preparation(Request $request) {

		$id = get_current_store_id();

		$data['preparation'] = StorePreparationTime::where('store_id', $id)->orderBy('day', 'ASC')->get();

		$data['max_time'] = convert_minutes(Store::where('id', $id)->first()->max_time);

		return view('store.preparation_time', $data);

	}

	public function update_preparation_time(Request $request) {

		$id = get_current_store_id();
		$store = Store::find($id);
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
				$store_update->store_id = $id;
				$store_update->save();
				$available_id[] = $store_update->id;
			}

			if (isset($available_id)) {
				StorePreparationTime::whereNotIn('id', $available_id)->delete();
			}
			flash_message('success', trans('admin_messages.updated_successfully'));
		}

		return back();

	}

	/*
		  *
			* remove preparation time
			*
	*/

	public function remove_time() {

		$id = request()->id;

		$store_time = StorePreparationTime::find($id);

		if ($store_time) {
			$store_time->delete($id);
		}

		return json_encode(['success' => true]);

	}

	public function menu(Request $request) {

		$store_id = auth()->guard('store')->user()->id;

		$data['store'] = $store = Store::where('user_id', $store_id)->first();

		$menu = Menu::with(['menu_category' => function ($query) {

			$query->with('all_menu_item');

		}])->where('store_id', $store->id)->get();

		$data['menu'] = $menu->map(function ($item) {

			$menu_category = $item['menu_category']->map(function ($item) {

				$menu_item = $item['all_menu_item']->map(function ($item) {
					return [

						'menu_item_id' => $item['id'],
						'menu_item_name' => $item['name'],
						'menu_item_desc' => $item['description'],
						'menu_item_org_name' => $item['org_name'],
						'menu_item_org_desc' => $item['org_description'],
						'menu_item_price' => $item['price'],
						'menu_item_tax' => $item['tax_percentage'],
						'menu_item_type' => $item['type'],
						'menu_item_status' => $item['status'],
						'item_image' => is_object($item['menu_item_thump_image']) ? '' : $item['menu_item_thump_image'],
						'translations' => $item['translations']

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

		return view('store.menu_editor', $data);

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

		$store_id = auth()->guard('store')->user()->id;
		$store = Store::where('user_id', $store_id)->first();
		$data['menu_time'] = MenuTime::where('store_id', $store->id)->where('menu_id', $request->id)->orderBy('day', 'ASC')->get();
		$data['translations'] = Menu::where('id', $request->id)->get();
		return $data;

	}

	public function update_menu_time(Request $request) {

		$store_id = auth()->guard('store')->user()->id;
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
		if ($request->menu_id) {
			return ['message' => 'success', 'menu_name' => $store_menu->name];
		} else {
			$menu = Menu::with(['menu_category' => function ($query) {

				$query->with('all_menu_item');

			}])->where('store_id', $id)->get();
		}

		$data['menu'] = $menu->map(function ($item) {

			$menu_category = $item['menu_category']->map(function ($item) {

				$menu_item = $item['all_menu_item']->map(function ($item) {

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

		$store_id = get_current_store_id();

		if ($request->menu_item_id) {

			$menu_item = MenuItem::where('id',$request->menu_item_id)
			->update([
				'price' => $request->menu_item_price,
				'tax_percentage' => $request->menu_item_tax,
				'type' => $request->menu_item_type,
				'status' => $request->menu_item_status,
				'name' => $request->menu_item_org_name,
				'description' => $request->menu_item_org_desc,
			]);

			$menu_item = MenuItem::find($request->menu_item_id);

			MenuItemTranslations::where('menu_item_id',$menu_item->id)->delete();
			$getTranslation = $request->item_translations;
				\Log::info('reach check'.$getTranslation);
				if($getTranslation != '[]'){
					$item_translations =	json_decode($getTranslation);
				\Log::info('reach menu_item'.$item_translations[0]->name);
                foreach($item_translations ?: array() as $translation_data) { 
                	$menu_item = MenuItem::find($request->menu_item_id);
                    $translation = $menu_item->getTranslationById($translation_data->locale,$menu_item->id);
                    $translation->name = $translation_data->name;                    
                    $translation->description = $translation_data->description;
                    $translation->save();
                }
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

			$data['edit_menu_item_image'] = is_object($menu_item->menu_item_thump_image) ? '' : $menu_item->menu_item_thump_image;
			$data['edit_menu_item_name'] = $menu_item->name;

		} else {

			$menu_item_id = MenuItem::insertGetId([
					'price' => $request->menu_item_price,
					'tax_percentage' => $request->menu_item_tax,
					'type' => $request->menu_item_type,
					'status' => $request->menu_item_status,
					'name' => $request->menu_item_org_name,
					'description' => $request->menu_item_org_desc,
					'menu_id' => $request->menu_id,
					'menu_category_id' => $request->category_id,
				]);
			
			$menu_item = MenuItem::find($menu_item_id);

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

			$data = [

				'menu_item_id' => $menu_item->id,
				'menu_item_name' => $menu_item->name,
				'menu_item_desc' => $menu_item->description,
				'menu_item_org_name' => $menu_item->org_name,
				'menu_item_org_desc' => $menu_item->org_description,
				'menu_item_price' => $menu_item->price,
				'menu_item_status' => $menu_item->status,
				'menu_item_type' => $menu_item->type,
				'menu_item_tax' => $menu_item->tax_percentage,
				'item_image' => is_object($menu_item->menu_item_thump_image) ? '' : $menu_item->menu_item_thump_image,
				'translations'=>$menu_item->translations
			];

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
				$data['msg'] = trans('messages.store.this_item_use_in_order_so_cant_delete');
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
				$data['msg'] = trans('messages.store.this_item_use_in_order_so_cant_delete');
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

			foreach ($delete_menu_item as $key => $value) {
				MenuItem::find($value->id)->delete();
			}

			$delete_category = MenuCategory::where('menu_id', $menu_id)->get();

			foreach ($delete_category as $key => $value) {
				MenuCategory::find($value->id)->delete();
			}
			MenuTime::where('menu_id', $menu_id)->delete();
			Menu::find($menu_id)->delete();
			MenuTranslations::where('menu_id',$menu_id)->delete();
			$data['status'] = 'true';
			return $data;
		}

	}

	/**
	forget password page

	 */

	public function forget_password() {

		session::forget('otp_confirm');
		session::forget('password_code');
		return view('store/forgot_password');
	}

	/**
	otp confirm from mail

	 */

	public function mail_confirm() {

		$email = request()->email;

		if ($email == '' && session::has('email') == '') {

			$rules = [

				'email' => 'required|email',
			];

			$messages =
				[

				'email.required' => trans('messages.store_dashboard.please_enter_your_email_address'),
			];

			$validator = Validator::make(request()->all(), $rules, $messages);

			if ($validator->fails()) {

				return back()->withErrors($validator)->withInput();

			}

		}

		$user_email = User::where(['type' => 1, 'email' => $email])->count();

		if ($user_email == 0) {

			flash_message('warning', trans('messages.driver.no_account_exist_for_email'));
			return redirect()->route('store.forget_password');

		}

		if (session::has('email')) {
			$email = session::get('email');
		}

		$user_details = User::where(['type' => 1, 'email' => $email])->first();

		if (count($user_details) == 0) {

			flash_message('warning', trans('messages.driver.no_account_exist_for_email'));
			return view('store/forgot_password');

		}
		
			$otp = random_num(4);

			$user_details->otp = $otp;
			$user_details->save();
			otp_for_forget_eater($email,$otp);
			session::put('email', $email);

		if ($user_details) {
			session::put('user_id', $user_details->id);
		}

		$this->view_data['user_id'] = session::get('user_id');

		return view('store/forgot_password2', $this->view_data);
	}

	/**

	set new password page

	 */

	public function set_password() {

		//dd(session::all());

		if (request()->code_confirm == '' && session::has('code_confirm') == '') {

			$rules = [

				'code_confirm' => 'required',
			];

			$messages =
				[

				'code_confirm.required' => trans('messages.store_dashboard.please_enter_your_code'),
			];

			$validator = Validator::make(request()->all(), $rules, $messages);

			if ($validator->fails()) {

				//return back()->withErrors($validator)->withInput();
            flash_message('warning', trans('messages.store_dashboard.please_enter_your_code'));
			return view('store/forgot_password2');
			}
		}

		if (session::has('code_confirm')) {

			$code = session::get('code_confirm');

		} else {

			$code = request()->code_confirm;

		}

		$user_id = session::get('user_id');
		$session_code = User::find($user_id)->otp;

		if ($session_code != $code) {
			flash_message('warning', trans('messages.store_dashboard.code_is_incorrect'));
			$this->view_data['user_id'] = $user_id;

			return view('store/forgot_password2', $this->view_data);
		}
		$this->view_data['user_id'] = $user_id;

		if(Session::has('message'))
		Session::forget('message');

		return view('store/reset_password', $this->view_data);
	}

	/**
	set new password response

	 */

	public function change_password() {

		$user_id = request()->id;
		$password = request()->password;

		$user = User::find($user_id);
		$user->password = bcrypt($password);

		$user->save();

		if (Auth::guard('store')->attempt(['email' => $user->email, 'password' => $password, 'type' => 1])) {

			$data = auth()->guard('store')->user();

			return json_encode(['success' => 'true', 'data' => $data]);

		} else {

			return json_encode(['success' => 'none', 'data' => '']);
		}
	}

	/**
	Payout details page

	 */

	public function payout_preference() {

		$this->view_data['user_id'] = auth()->guard('store')->user()->id;
		$this->view_data['country'] = Country::all()->pluck('name', 'code');

		$this->view_data['country_list'] = Country::getPayoutCoutries();

		$this->view_data['stripe_data'] = site_setting('stripe_publish_key');
		$this->view_data['currency'] = Currency::where('status', '=', '1')->pluck('code', 'code');

		$this->view_data['iban_supported_countries'] = Country::getIbanRequiredCountries();
		$this->view_data['country_currency'] = Country::getCurrency();
		$this->view_data['mandatory'] = PayoutPreference::getAllMandatory();
		$this->view_data['mandatory_field'] = PayoutPreference::getMandatoryField();
		//dd($this->view_data['mandatory'], $this->view_data['country_list']);
		$this->view_data['branch_code_required'] = Country::getBranchCodeRequiredCountries();

		$weekly_payout = Payout::userId([$this->view_data['user_id']])
			->get()
			->groupBy(function ($date) {
				return Carbon::parse($date->created_at)->format('W');
			});

		$count = 0;
		$week = [];

		foreach ($weekly_payout as $key => $value) {

			$total = 0;
			$tax = 0;
			$subtotal = 0;
			$order_total = 0;
			$gofer_fee = 0;
			$count = count($value);
			$status_text = 'Pending';
			$i = 0;
			$order_data = [];
			$penalty = 0;
			$paid_penalty = 0;
			foreach ($value as $payout) {
				$total += (float) $payout->amount;
				$currency_code = $payout->order->currency->symbol;

				if ($payout->status == 0) {
					$status_text = $payout->status_text;
				}

				$tax += $payout->order->tax;
				$paid_penalty += $payout->order->res_applied_penality;
				$penalty += ($payout->order->penality_details) ? ($payout->order->penality_details->store_penality) : 0;
				$subtotal += (float) $payout->order->subtotal;
				$order_total += (float) $payout->order->store_total;
				$gofer_fee += (float) $payout->order->store_commision_fee;
				$year = date('Y', strtotime($payout->created_at));

				$order_data[$i] = $payout;
				$i++;
			}
			$total_penalty = $penalty - $paid_penalty > 0 ? ($penalty - $paid_penalty) : 0;
			$date = getWeekDates($year, $key);

			$format_date = date('d', strtotime($date['week_start'])).' '.trans('messages.driver.'.date('M', strtotime($date['week_start']))) . ' - ' . date('d', strtotime($date['week_end'])).' '.trans('messages.driver.'.date('M', strtotime($date['week_end'])));

			$table_date = date('Y-m-d', strtotime($date['week_start'])) . ' , ' . date('Y-m-d', strtotime($date['week_end']));

			$week[] = ['week' => $format_date,
				'table_week' => $table_date,
				'total_payout' => numberFormat($total),
				'year' => $year,
				'tax' => numberFormat($tax),
				'currency_symbol' => $currency_code,
				'subtotal' => numberFormat($subtotal),
				'status' =>  trans('messages.store.'.$status_text),
				'total_amount' => numberFormat($order_total),
				'payout_status' => trans('messages.store.'.$status_text),
				'gofer_fee' => numberFormat($gofer_fee),
				'penalty' => numberFormat($total_penalty),
				'paid_penalty' => numberFormat($paid_penalty),
				'count' => $count,
				'order_detail' => $order_data,
				'date' => $date['week_start']];

		}

		$current_week = date('d M', strtotime('last monday')) . ' - ' . date('d M', strtotime('next sunday'));

		$this->view_data['current_week_orders'] = array_sum(array_column($week, 'count'));
		$this->view_data['current_week_symbol'] = @$currency_code;
		$this->view_data['current_week_profit'] = numberFormat(array_sum(array_column($week, 'total_payout')));
		$this->view_data['current_week'] = $current_week;
		$this->view_data['paginate'] = $weekly_payout;
		$this->view_data['weekly_payouts'] = $week;

		$this->view_data['payout_preference'] = PayoutPreference::where('user_id', $this->view_data['user_id'])->first();

		return view('store/payout_preference', $this->view_data);
	}

	/**
	Payout daywise details page

	 */

	public function payout_daywise_details() {

		$week_data = request()->week;

		$start_end = explode(',', $week_data);

		$this->view_data['user_id'] = auth()->guard('store')->user()->id;
		$this->view_data['country'] = Country::all()->pluck('name', 'code');
		$this->view_data['country_list'] = Country::getPayoutCoutries();
		$this->view_data['stripe_data'] = site_setting('stripe_publish_key');
		$this->view_data['currency1'] = Currency::where('status', '1')->pluck('code', 'code');

		$this->view_data['iban_supported_countries'] = Country::getIbanRequiredCountries();
		$this->view_data['country_currency'] = Country::getCurrency();
		$this->view_data['mandatory'] = PayoutPreference::getAllMandatory();
		$this->view_data['branch_code_required'] = Country::getBranchCodeRequiredCountries();

		$daily_payout = Payout::userId([$this->view_data['user_id']])
			->whereBetween('created_at', [$start_end[0], $start_end[1]])
			->get()
			->groupBy(function ($date) {
				return Carbon::parse($date->created_at)->format('D');
			});

		$count = 0;
		$week = [];
		//dd($weekly_payout);
		foreach ($daily_payout as $key => $value) {

			$total = 0;
			$tax = 0;
			$subtotal = 0;
			$order_total = 0;
			$gofer_fee = 0;
			$count = count($value);
			$status_text = 'Pending';
			$i = 0;
			$paid_penalty = 0;
			$penalty = 0;
			$order_data = [];
			foreach ($value as $payout) {
				$total += (float) $payout->amount;
				$amount[$i] = $payout->amount;
				$currency_code = $payout->order->currency->symbol;
				if ($payout->status == 0) {
					$status_text = $payout->status_text;
				}

				$tax += (float) $payout->order->tax;
				$subtotal += (float) $payout->order->subtotal;
				$order_total += (float) $payout->order->store_total;
				$gofer_fee += (float) $payout->order->store_commision_fee;
				$day_val = date('d M', strtotime($payout->created_at));
				$table_date = date('Y-m-d', strtotime($payout->created_at));
				$order_data[$i] = $payout;
				$paid_penalty += @$payout->order->res_applied_penality;
				$penalty += ($payout->order->penality_details) ? ($payout->order->penality_details->store_penality) : 0;
				$i++;
			}
			$total_penalty = $penalty - $paid_penalty > 0 ? ($penalty - $paid_penalty) : 0;
			$format_date = $day_val;
		
			$week[] = ['week' =>	date('d', strtotime($format_date)).' '.trans('messages.driver.'.date('M', strtotime($format_date))),
				'table_date' => $table_date,
				'total_payout' => numberFormat($total),
				'day' => $day_val,
				'tax' => numberFormat($tax),
				'currency_symbol' => $currency_code,
				'subtotal' => numberFormat($subtotal),
				'status' => trans('messages.store.'.$status_text),
				'total_amount' => numberFormat($order_total),
				'payout_status' =>  trans('messages.store.'.$status_text),
				'gofer_fee' => numberFormat($gofer_fee),
				'paid_penalty' => numberFormat($paid_penalty),
				'penalty' => numberFormat($total_penalty),
				'count' => $count,
				'order_detail' => $order_data];

		}

		$current_week = date('d M', strtotime('last monday')) . ' - ' . date('d M', strtotime('next sunday'));

		$this->view_data['current_week_orders'] = array_sum(array_column($week, 'count'));
		$this->view_data['current_week_symbol'] = @$currency_code;
		$this->view_data['current_week_profit'] = numberFormat(array_sum(array_column($week, 'total_payout')));
		$this->view_data['current_week'] = $current_week;
		$this->view_data['paginate'] = $daily_payout;
		$this->view_data['weekly_payouts'] = $week;

		$this->view_data['payout_preference'] = PayoutPreference::where('user_id', $this->view_data['user_id'])->first();

		return view('store/payout_preference1', $this->view_data);
	}

	/**
	export table data for payout as weekly
	 */

	public function get_export() {

		$user_id = auth()->guard('store')->user()->id;

		$week_data = request()->week;

		$start_end = explode(',', $week_data);

		$daily_payout = Payout::userId([$user_id])
			->whereBetween('created_at', [$start_end[0], $start_end[1]])
			->get()
			->groupBy(function ($date) {
				return Carbon::parse($date->created_at)->format('D');
			});

		$count = 0;
		$week = [];
		foreach ($daily_payout as $key => $value) {

			$total = 0;
			$tax = 0;
			$subtotal = 0;
			$order_total = 0;
			$gofer_fee = 0;
			$count = count($value);
			$status = 0;
			$paid_penalty = 0;
			$penalty = 0;
			$i = 0;
			$order_data = [];
			foreach ($value as $payout) {
				$total += (float) $payout->amount;
				$amount[$i] = $payout->amount;
				$currency_code = $payout->order->currency->symbol;
				$status_text = $payout->status_text;
				$tax += (float) $payout->order->tax;
				$subtotal += (float) $payout->order->subtotal;
				$order_total += (float) $payout->order->store_total;
				$gofer_fee += (float) $payout->order->store_commision_fee;
				$day_val = date('d M', strtotime($payout->created_at));
				$paid_penalty += $payout->order->res_applied_penality;
				$penalty += ($payout->order->penality_details) ? ($payout->order->penality_details->store_penality) : 0;
				$order_data[$i] = $payout;
				$i++;
			}
			$total_penalty = $penalty - $paid_penalty > 0 ? ($penalty - $paid_penalty) : 0;
			$format_date = $day_val;

			$week[] = ['Date' => $format_date,
				'Orders' => $count,
				'Sale' => $subtotal,
				'Tax' => $tax,
				'Total' => $order_total,
				'GoferGrocery Fee' => $gofer_fee,
				'Net Payout' => $total,
				'Payout Status' => $status_text,
				'Penalty' => $total_penalty,
				'Paid penalty' => $paid_penalty];

		}
		//dd($week);
		$width = array(
			'A' => '2',
			'B' => '2',
			'C' => '2',
			'D' => '2',
			'E' => '2',
			'F' => '2',
			'G' => '2',
			'H' => '2',
			'i' => '2',

		);

		//return $data->download('download.pdf');
		$data = $week;
		$filename = 'Payout_' . time();
		$download_format = 'csv';

		if ($download_format == 'csv') {
			return buildExcelFile($filename, $week, $width)->download($download_format);
		}
		return back();
	}

	/**
	export table data for payout as daywise
	 */

	public function get_order_export() {

		$user_id = auth()->guard('store')->user()->id;

		$week_data = request()->date;

		$daily_payout = Payout::userId([$user_id])

			->whereDate('created_at', $week_data)
			->get()
			->groupBy('order_id');

		$count = 0;
		$week = [];
		//dd($daily_payout, $user_id);
		foreach ($daily_payout as $key => $value) {

			$total = 0;
			$tax = 0;
			$subtotal = 0;
			$order_total = 0;
			$gofer_fee = 0;
			$i = 0;
			$order_data = [];
			foreach ($value as $payout) {
				$total = (float) $payout->amount;
				$amount[$i] = $payout->amount;
				$currency_code = $payout->order->currency->symbol;
				$notes = $payout->order->store_notes;
				$status_text = $payout->status_text;

				$tax = (float) $payout->order->tax;
				$count = $payout->order->id;
				$subtotal = (float) $payout->order->subtotal;
				$order_total = (float) $payout->order->store_total;
				$gofer_fee = (float) $payout->order->store_commision_fee;
				$day_val = date('d M', strtotime($payout->created_at));
				$paid_penalty = @$payout->order->res_applied_penality;
				$penalty = ($payout->order->penality_details) ? ($payout->order->penality_details->store_penality) : 0;
				$order_data[$i] = $payout;
				$i++;
			}
			$total_penalty = $penalty - $paid_penalty > 0 ? ($penalty - $paid_penalty) : 0;
			$format_date = $day_val;

			$week[] = ['Date' => $format_date,
				'OrderId' => $count,
				'Sale' => $subtotal,
				'Tax' => $tax,
				'Total' => $order_total,
				'Gofer Fee' => $gofer_fee,
				'Net Payout' => $total,
				'Payout Status' => $status_text,
				'Penalty' => $total_penalty,
				'Paid penalty' => $paid_penalty,
				'Notes' => $notes];

		}
		//dd($week);
		$width = array(
			'A' => '2',
			'B' => '2',
			'C' => '2',
			'D' => '2',
			'E' => '2',
			'F' => '2',
			'G' => '2',
			'H' => '2',
			'i' => '2',
			'j' => '6',

		);

		//return $data->download('download.pdf');
		$data = $week;
		$filename = 'Payout_' . time();
		$download_format = 'csv';

		if ($download_format == 'csv') {
			return buildExcelFile($filename, @$week, $width)->download($download_format);
		}
		return back();
	}

	/**
	stripe account creation and payout details store

	 */

	public function update_payout_preferences(Request $request) {

		if ($request->payout_country != 'OT') {
			$country_data = Country::where('code', $request->payout_country)->first();

			if (!$country_data) {
				flash_message('error', trans('messages.store.service_not_available')); // Call flash message function
				return back();
			}
		}

		/*** required field validation --start-- ***/
		$country = ($request->payout_country) ? $request->payout_country : '';

		$rules = array(
			'payout_country' => 'required',
			'account_number' => 'required',
			'address1' => 'required',
			'city' => 'required',
			'postal_code' => 'required',

		);

		$user_id = auth()->guard('store')->user()->id;

		$user = User::find($user_id);
		$payout_preference = PayoutPreference::where('user_id', $user_id)->first();
		// custom required validation for Japan country
		if ($country == 'JP') {

			$rules['phone_number'] = 'required';
			$rules['bank_name'] = 'required';
			$rules['branch_name'] = 'required';
			$rules['address1'] = 'required';
			$rules['kanji_address1'] = 'required';
			$rules['kanji_address2'] = 'required';
			$rules['kanji_city'] = 'required';
			$rules['kanji_state'] = 'required';
			$rules['kanji_postal_code'] = 'required';

			if (!$user->gender) {
				$rules['gender'] = 'required|in:male,female';
			}

		}
		$rules['document'] = 'mimes:png,jpeg,jpg';
		if ($country != 'OT' && $payout_preference == '') {
			$rules['document'] = 'required|mimes:png,jpeg,jpg';
		}
		if ($payout_preference && $country != 'OT') {
			if ($payout_preference->document_image == '') {
				$rules['document'] = 'required|mimes:png,jpeg,jpg';
			}

		}

		if ($country != 'OT') {
			$rules['stripe_token'] = 'required';
		}

		$nice_names = array(
			'payout_country' => trans('messages.profile.country'),
			'currency' => trans('messages.store.currency'),
			'routing_number' => trans('messages.store_dashboard.routing_number'),
			'account_number' => trans('messages.store.account_number'),
			'holder_name' => trans('messages.store_dashboard.holder_name'),
			'additional_owners' => trans('messages.store_dashboard.additional_owners'),
			'business_name' => trans('messages.store_dashboard.business_name'),
			'business_tax_id' => trans('messages.store_dashboard.business_tax_id'),
			'holder_type' => trans('messages.store_dashboard.holder_type'),
			'stripe_token' => trans('messages.store_dashboard.stripe_token'),
			'address1' => trans('messages.driver.address'),
			'city' => trans('messages.driver.city'),
			'state' => trans('admin_messages.state'),
			'postal_code' => trans('messages.profile.postal_code'),
			'document' => trans('admin_messages.document'),
			//'ssn_last_4' => 'SSN Last 4',
		);

		if ($country == 'OT') {
			$nice_names['routing_number'] = trans('messages.store.holder_name');
		}

		$messages = array(
			'required' => ':attribute '.trans('messages.driver.is_required'),
			'mimes' => trans('validation.mimes', ['attribute' => trans('admin_messages.document'),'values' => "png,jpeg,jpg"]),
		);

		$validator = Validator::make($request->all(), $rules, $messages);
		$validator->setAttributeNames($nice_names);

		if ($validator->fails()) {
			// dd($validator);
			return back()->withErrors($validator)->withInput();

		}
// dd($validator);
		/*** required field validation --end-- ***/
		if ($request->payout_country != 'OT') {
			$payout_method = 'Stripe';
			$stripe_data['client'] = site_setting('stripe_publish_key');
			$stripe_data['secret'] = site_setting('stripe_secret_key');

			\Stripe\Stripe::setApiKey($stripe_data['secret']);

			$account_holder_type = 'individual';

			/*** create stripe account ***/
			try
			{
				$recipient = \Stripe\Account::create(array(
					"country" => strtolower($request->payout_country),
					"payout_schedule" => array(
						"interval" => "manual",
					),
					"tos_acceptance" => array(
						"date" => time(),
						"ip" => $_SERVER['REMOTE_ADDR'],
					),
					"type" => "custom",
				));

			} catch (\Exception $e) {
				flash_message('danger', $e->getMessage());
				return back();
			}

			$recipient->email = auth()->guard('store')->user()->email;

			// create external account using stripe token --start-- //
			try {
				$recipient->external_accounts->create(array(
					"external_account" => $request->stripe_token,
				));
			} catch (\Exception $e) {
				flash_message('danger', $e->getMessage());
				return back();
			}

			if (!isset($user->dob_array[2])) {
				flash_message('danger', trans('messages.store_dashboard.please_complete_profile_step_then_add_payout'));
				return back();
			}
			// create external account using stripe token --end-- //
			try
			{
				// insert stripe external account datas --start-- //
				if ($request->country != 'JP') {
					// for other countries //
					//dd($request->all(), $account_holder_type);
					$recipient->legal_entity->type = $account_holder_type;
					$recipient->legal_entity->first_name = $user->first_name;
					$recipient->legal_entity->last_name = $user->last_name;
					$recipient->legal_entity->dob->day = $user->dob_array[2];
					$recipient->legal_entity->dob->month = $user->dob_array[1];
					$recipient->legal_entity->dob->year = $user->dob_array[0];
					$recipient->legal_entity->address->line1 = $request->address1;
					$recipient->legal_entity->address->line2 = $request->address2 ? $request->address2 : null;
					$recipient->legal_entity->address->city = $request->city;
					$recipient->legal_entity->address->country = $request->payout_country;
					$recipient->legal_entity->address->state = $request->state ? $request->state : null;
					$recipient->legal_entity->address->postal_code = $request->postal_code;
					if ($request->country == 'US') {
						$recipient->legal_entity->ssn_last_4 = $request->ssn_last_4;
					}

				} else {
					// for Japan country //
					$address = array(
						'line1' => $request->address1,
						'line2' => $request->address2,
						'city' => $request->city,
						'state' => $request->state,
						'postal_code' => $request->postal_code,
					);
					$address_kana = array(
						'line1' => $request->address1,
						'town' => $request->address2,
						'city' => $request->city,
						'state' => $request->state,
						'postal_code' => $request->postal_code,
						'country' => $request->payout_country,
					);
					$address_kanji = array(
						'line1' => $request->kanji_address1,
						'town' => $request->kanji_address2,
						'city' => $request->kanji_city,
						'state' => $request->kanji_state,
						'postal_code' => $request->kanji_postal_code,
						'country' => $request->payout_country,
					);

					$recipient->legal_entity->type = $account_holder_type;
					$recipient->legal_entity->first_name_kana = $user->first_name;
					$recipient->legal_entity->last_name_kana = $user->last_name;
					$recipient->legal_entity->first_name_kanji = $user->first_name;
					$recipient->legal_entity->last_name_kanji = $user->last_name;
					$recipient->legal_entity->dob->day = $user->dob_array[2];
					$recipient->legal_entity->dob->month = $user->dob_array[1];
					$recipient->legal_entity->dob->year = $user->dob_array[0];
					$recipient->legal_entity->address_kana = $address_kana;
					$recipient->legal_entity->address_kanji = $address_kanji;
					$recipient->legal_entity->gender = $request->gender ? $request->gender : strtolower(Auth::user()->gender);

					$recipient->legal_entity->phone_number = $request->phone_number ? $request->phone_number : null;

				}

				$recipient->save();
				// insert stripe external account datas --end-- //
			} catch (\Exception $e) {
				try
				{
					$recipient->delete();
				} catch (\Exception $e) {
				}

				flash_message('danger', $e->getMessage());
				return back();
			}
		} else {
			$payout_method = 'Manual';
			$account_holder_type = 'company';
		}

		// verification document upload for stripe account --start-- //
		$document = $request->file('document');
		//dd($document);
		if ($request->document) {
			//dd('hi', $request->document);
			$extension = $document->getClientOriginalExtension();
			$filename = $user_id . '_user_document_' . time() . '.' . $extension;
			$filenamepath = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user_id . '/uploads';

			if (!file_exists($filenamepath)) {
				mkdir(dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user_id . '/uploads', 0777, true);
			}
			$success = $document->move('images/users/' . $user_id . '/uploads/', $filename);
			if ($success) {
				$document_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $user_id . '/uploads/' . $filename;

				if ($request->payout_country != 'OT') {

					try
					{
						$stripe_file_details = \Stripe\FileUpload::create(
							array(
								"purpose" => "identity_document",
								"file" => fopen($document_path, 'r'),
							),
							array("stripe_account" => @$recipient->id)
						);

						$recipient->legal_entity->verification->document = $stripe_file_details->id;
						$recipient->save();

						$stripe_document = $stripe_file_details->id;
						//dd($recipient);
					} catch (\Exception $e) {
						//dd($recipient,'sssssssss');
						flash_message('danger', $e->getMessage());
						return back();
					}
				} else {
					$stripe_document = '';
				}

			}

		}
		// verification document upload for stripe account --end-- //

		// store payout preference data to payout_preference table --start-- //

		if (!$payout_preference) {
			$payout_preference = new PayoutPreference;
		}
		$payout_preference->user_id = $user_id;
		$payout_preference->country = $request->payout_country;
		$payout_preference->currency_code = ($request->payout_country != 'OT') ? $request->currency : '';
		$payout_preference->routing_number = isset($request->routing_number) ? $request->routing_number : '';
		$payout_preference->account_number = $request->account_number;
		$payout_preference->holder_name = $request->account_holder_name;
		$payout_preference->holder_type = $account_holder_type;
		$payout_preference->paypal_email = isset($recipient->id) ? $recipient->id : $user->email;

		$payout_preference->address1 = $request->address1;
		$payout_preference->address2 = $request->address2;
		$payout_preference->city = $request->city;

		$payout_preference->state = $request->state;
		$payout_preference->postal_code = $request->postal_code;
		if (isset($filename)) {
			$payout_preference->document_id = ($request->document) ? $stripe_document : '';
			$payout_preference->document_image = $filename;
		}
		$payout_preference->phone_number = $request->phone_number ? $request->phone_number : '';
		$payout_preference->branch_code = $request->branch_code ? $request->branch_code : '';
		$payout_preference->bank_name = $request->bank_name ? $request->bank_name : '';
		$payout_preference->branch_name = $request->branch_name ? $request->branch_name : '';

		$payout_preference->ssn_last_4 = $request->payout_country == 'US' ? $request->ssn_last_4 : '';
		$payout_preference->payout_method = $payout_method;

		$payout_preference->address_kanji = isset($address_kanji) ? json_encode($address_kanji) : json_encode([]);

		$payout_preference->save();

		if ($request->gender) {
			$user->gender = $request->gender;
			$user->save();
		}

		$payout_check = PayoutPreference::where('user_id', auth()->guard('store')->user()->id)->where('default', 'yes')->get();

		if ($payout_check->count() == 0) {
			$payout_preference->default = 'yes'; // set default payout preference when no default
			$payout_preference->save();
		}
		// store payout preference data to payout_preference table --end-- //

		flash_message('success', trans('admin_messages.updated_successfully'));
		return back();

	}

	/**
	 * get payout preference data
	 *
	 * @return data
	 */

	public function get_payout_preference(Request $request) {

		$data = PayoutPreference::where('user_id', $request->id)->first();
		return $data;
	}

	/**
	 * Logout store and redirect to login page
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function logout() {
		Auth::guard('store')->logout();
		return redirect()->route('store.login'); // Redirect to login page
	}

	public function review_details($store_id = 1) {

		$review = DB::table('menu_item')
			->join('menu', 'menu_item.menu_id', '=', 'menu.id')
			->join('review', 'review.reviewee_id', '=', 'menu_item.id')
			->leftjoin('review_issue', 'review_issue.review_id', '=', 'review.id')
			->leftjoin('issue_type', 'review_issue.issue_id', '=', 'issue_type.id')
			->select('review.reviewee_id as reviewee_id_', 'menu_item.name', 'menu_item.id', DB::raw('sum(review.is_thumbs) as thumbs'), DB::raw('(SELECT count(reviewee_id) as count  from review where reviewee_id_ = review.reviewee_id) as count_thumbs'), DB::raw('GROUP_CONCAT(issue_type.name )as issues'), DB::raw('GROUP_CONCAT(issue_type.id ) as issues_id'), DB::raw('GROUP_CONCAT(review.comments ) as review_comments'))
			->where('menu.store_id', $store_id)
			->whereRaw("date(review.created_at) <= date_sub(now(), interval -3 month)")
			->where('review.type', 0)
			->groupBy('menu_item.id')
			->get();

		//dd($review);
		return $review;

	}

}
