<?php

/**
 * EaterController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Eater
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\OrderCancelReason;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\Store;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UsersPromoCode;
use Auth;
use Illuminate\Http\Request;
use Session;

class EaterController extends Controller {

	/**
	 * Store detail page
	 *
	 *
	 */
	public function detail() {

		$this->view_data['store_id'] = request()->store_id;

		$this->view_data['user_details'] = auth()->guard('web')->user();
		$this->view_data['order_detail_data'] = '';

		$this->view_data['store'] = Store::findOrFail($this->view_data['store_id']);

		if (isset($this->view_data['store']->store_all_time[0])) {
			$this->view_data['store_time_data'] = $this->view_data['store']->store_all_time[0]->is_available;
		} else {
			$this->view_data['store_time_data'] = 0;
		}

		$this->view_data['store_category'] = $this->view_data['store']->store_category;

		$this->view_data['store_menu'] = Menu::menuRelations()
			->where('store_id', $this->view_data['store_id'])->get();

		if (count($this->view_data['store_menu'])) {
			$this->view_data['menu_category'] = $this->view_data['store_menu'][0]->menu_category;
		} else {
			$this->view_data['menu_category'] = '';
		}

		$this->view_data['order_detail_data'] = get_user_order_details($this->view_data['store_id'], @$this->view_data['user_details']->id);
		$cart_store_id = $this->view_data['order_detail_data'] ? $this->view_data['order_detail_data']['store_id'] : '';
		if ($cart_store_id) {
			$this->view_data['other_store_detail'] = Store::findOrFail($cart_store_id);
		}

		$this->view_data['other_store'] = ($cart_store_id != '' && $cart_store_id != $this->view_data['store_id']) ? 'yes' : 'no';

		if ($this->view_data['other_store'] == 'yes') {
			$this->view_data['order_detail_data'] = '';
		}

		return view('detail', $this->view_data);
	}

	//session clear for menu's

	public function session_clear_data() {

		return session_clear_all_data();

	}

	//menu item detail

	public function menu_item_detail() {

		$item_id = request()->item_id;
		$menu_item = MenuItem::find($item_id);
		$menu_detail = $menu_item->toArray();
		$menu_detail['menu_item_status'] = $menu_item->menu->menu_closed;
		$menu_detail['menu_closed_status'] = $menu_item->menu->menu_closed_status;
		return json_encode(['menu_item' => $menu_detail]);
	}

	//menu category detail

	public function menu_category_details() {

		//dd(request()->all());
		$id = request()->id;
		$menu_category = MenuCategory::with('menu_item')->where('menu_id', $id)
		//	->whereHas('menu_item')
			->get();
		//dd($menu_category);
		return json_encode(['menu_category' => $menu_category]);
	}

	//orders store in session

	public function orders_store() {

		//dd(request()->all());

		$menu_data = request()->menu_data;
		$item_count = request()->item_count;
		$item_notes = request()->item_notes;
		$item_price = request()->item_price;
		$individual_price = request()->individual_price;
		$store_id = request()->store_id;

		$order_array = [];
		//session::forget('order_data');
		// $price = $item_price;
		$count = $item_count;
		//  if(isset($order_array))
		//   $order_array = [];
		// else
		//  // session::forget('order_data');
		//  // dd(Session::get('order_data'));

		if (Session::get('order_data') != null) {
			$order_array = Session::get('order_data');

			// for($i=0;$i<count($order_array);$i++){
			//   $price = $order_array[$i]['item_price'] + $item_price;
			//   $count = $order_array[$i]['item_count'] + $item_count;
			// }

		}

		//dd($order_array,$price);

		$order_data = array('menu_data' => $menu_data, 'store_id' => $store_id, 'item_notes' => $item_notes, 'item_count' => $item_count, 'item_price' => $item_price, 'individual_price' => $individual_price);

		// dd($order_data,$order_array);

		array_push($order_array, $order_data);

		session(['order_data' => $order_array]);

		return json_encode(['last_pushed' => $order_data, 'all_order' => Session::get('order_data')]);

	}

	//orders remove from session

	public function orders_remove() {

		$order_data = request()->order_data;
		$user_details = auth()->guard('web')->user();
		if ($user_details) {
			$order_item_id = array_column($order_data['items'], 'order_item_id');
			OrderItem::where('order_id', $order_data['order_id'])->whereNotIn('id', $order_item_id)->delete();
			$order_data = get_user_order_details($order_data['store_id'], $user_details->id);
		} else {
			Session::forget('order_data');
			Session::put('order_data', $order_data);
			$order_data = get_user_order_details();

		}

		return json_encode(['order_data' => $order_data]);

	}

	public function orders_change() {
		$order_item_id = request()->order_item_id;
		$order_data = request()->order_data;
		$user_details = auth()->guard('web')->user();
		if ($user_details) {
			foreach ($order_data['items'] as $order_item) {
				if ($order_item['order_item_id'] == $order_item_id) {
					$update_item = OrderItem::find($order_item_id);
					$update_item->quantity = $order_item['item_count'];
					$update_item->total_amount = $order_item['item_count'] * $update_item->price;
					$update_item->tax = calculate_tax(($order_item['item_count'] * $update_item->price), $update_item->menu_item->tax_percentage);
					$update_item->save();
				}
			}
			$order_data = get_user_order_details($order_data['store_id'], $user_details->id);
		} else {
			Session::forget('order_data');
			Session::put('order_data', $order_data);
			$order_data = get_user_order_details();
		}

		return json_encode(['order_data' => $order_data]);

	}

	//order history

	public function order_history() {
		$this->view_data['user_details'] = auth()->guard('web')->user();

		$this->view_data['order_details'] = Order::getAllRelation()->where('user_id', $this->view_data['user_details']->id)->history()->orderBy('id', 'DESC')->get();
		$this->view_data['cancel_reason'] = OrderCancelReason::where('status', 1)->get();
		$this->view_data['upcoming_order_details'] = Order::getAllRelation()->where('user_id', $this->view_data['user_details']->id)->upcoming()->orderBy('id', 'DESC')->get();

		//dd($this->view_data['order_details'][0]->order_item[0]->menu_item->name);

		return view('orders', $this->view_data);
	}

	//order invoice

	public function order_invoice() {

		//dd(request()->all());
		$order_id = request()->order_id;

		$order = Order::with(['order_item' => function ($query) {
			$query->with('menu_item');
		}])->find($order_id);

		$currency_symbol = Order::find($order_id)->currency->symbol;

		//dd($order->order_item);
		return json_encode(['order_detail' => $order, 'currency_symbol' => $currency_symbol]);
	}

	//promo code changes

	public function add_promo_code(Request $request) {
		$code=$request->code;
		$user_details = auth()->guard('web')->user();
		$promo_code_date_check = PromoCode::with('promotranslation')->where(function($query)use ($code){

			$query->whereHas('promotranslation',function($query1) use($code)
			{
				$query1->where('code',$code);

			})->orWhere('code',$code);


		})->where('end_date', '>=', date('Y-m-d'))->first();
		$data['status'] = 1;
		$data['message'] = trans('api_messages.add_promo_code.promo_applied_successfully');
		if ($promo_code_date_check) {

			$user_promocode = UsersPromoCode::where('promo_code_id', $promo_code_date_check->id)->where('user_id', $user_details->id)->first();

			if ($user_promocode) {
				$data['status'] = 0;
				$data['message'] = trans('messages.profile_orders.already_applied');
			} else {
				$users_promo_code = new UsersPromoCode;
				$users_promo_code->user_id = $user_details->id;
				$users_promo_code->promo_code_id = $promo_code_date_check->id;
				$users_promo_code->order_id = 0;
				$users_promo_code->save();
			}
			$amount = promo_calculation();
			$data['order_detail_data'] = get_user_order_details($request->store_id, $user_details->id);
		} else {

			$promo_code = PromoCode::with('promotranslation')->where(function($query) use($code){

			$query->whereHas('promotranslation',function($query1) use($code)
			{
				$query1->where('code',$code);

			})->orWhere('code',$code);


			})->where('end_date', '<', date('Y-m-d'))->first();

			if ($promo_code) {
				$data['status'] = 0;
				$data['message'] = trans('api_messages.add_promo_code.promo_code_expired');
			} else {
				$data['status'] = 0;
				$data['message'] = trans('api_messages.add_promo_code.invalid_code');
			}

		}
		if (isset($request->page)) {
			$class = ($data['status'] == 1) ? 'success' : 'danger';
			flash_message($class, $data['message']);
			return back();
		}
		return $data;
	}

	//confirm address check with store address

	public function location_check() {

		$order_id = request()->order_id;
		$restuarant_id = request()->restuarant_id;
		$city = request()->city;
		$address1 = request()->address1;
		$state = request()->state;
		$country = request()->country;
		$location = request()->location;
		$postal_code = request()->postal_code;
		$latitude = request()->latitude;
		$longitude = request()->longitude;

		$user_id = get_current_login_user_id();
		$user_address = UserAddress::where('user_id', $user_id)->first();
		if ($user_address == '') {
			$user_address = new UserAddress;
		}

		$user_address->user_id = $user_id;
		$user_address->address = $location;
		$user_address->street = $address1;
		$user_address->first_address = $location;
		$user_address->second_address = $address1;
		$user_address->city = $city;
		$user_address->state = $state;
		$user_address->country = $country;
		$user_address->postal_code = $postal_code;
		$user_address->latitude = $latitude;
		$user_address->longitude = $longitude;
		$user_address->default = 1;
		$user_address->delivery_options = 0;
		$user_address->save();

		session()->put('city', $city);
		session()->put('address1', $address1);
		session()->put('state', $state);
		session()->put('country', $country);
		session()->put('location', $location);
		session()->put('postal_code', $postal_code);
		session()->put('latitude', $latitude);
		session()->put('longitude', $longitude);

		$result = check_location($order_id);

		if ($result == 1) {
			return json_encode(['success' => 'true']);
		} else {
			if(!request()->checkout_page){
				
				$OrderDelivery = OrderDelivery::where('order_id', $order_id)->first();
				$OrderDelivery->delete($OrderDelivery->id);

				$OrderItem = OrderItem::where('order_id', $order_id)->get();
				foreach ($OrderItem as $key => $value) {
					$value->delete($OrderItem[$key]->id);
				}

				$order = Order::find($order_id);
				$order->delete($order_id);

				session::forget('order_data');
				session::forget('order_detail');
			}

			return json_encode(['success' => 'none','message'=>trans('admin_messages.sorry_this_place_not_delivery')]);
		}

	}

	//location not found

	public function location_not_found() {

		return view('location_not_found');
	}

}
