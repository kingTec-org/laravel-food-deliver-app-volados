<?php

/**
 * SearchController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Search
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Category;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Session;
use View;

class SearchController extends Controller {
	//

	public function __construct() {
		parent::__construct();
	}

	// index data

	public function index() {

		$this->view_data['user_details'] = auth()->guard('web')->user();

		$this->view_data['top_category_data'] = Category::Active()->where('is_top', 1)->get();
		$this->view_data['category_data'] = Category::Active()
			->where(function($q){
				$q->where('is_top', 0)->orWhereNull('is_top');
			})
			->get();

		$this->view_data['request_cat'] = request()->q;

		session::forget('password_code');
		if (session::get('schedule_data') == null) {

			$schedule_data = array('status' => 'ASAP', 'date' => '', 'time' => '');

			session::put('schedule_data', $schedule_data);
		}
		$address = $this->address_details();
		if (session('location')) {
			return view('search/search', $this->view_data);
		} else {
			return redirect()->route('home');
		}

	}

	// session store data function

	public function store_location_data() {

		$this->view_data['postal_code'] = request()->postal_code;
		$this->view_data['locality'] = request()->locality;
		$this->view_data['city'] = request()->city ? request()->city : request()->location;
		$this->view_data['latitude'] = request()->latitude;
		$this->view_data['longitude'] = request()->longitude;
		$this->view_data['location'] = request()->location ? request()->location : session('location');
		$this->view_data['show_location'] = str_replace('+', ' ', request()->location);

		session::put('city', $this->view_data['city']);

		$user_id = auth()->guard('web')->user();

		if ($user_id) {
			$user_address = UserAddress::where('user_id', $user_id->id)->first();
			if (!$user_address) {
				$user_address = new UserAddress;
				$user_address->user_id = $user_id->id;
				$user_address->type = 0;
			}
			$country_name = '';
			if (request()->country) {
				$country_name = Country::where('code', request()->country)->first()->name;
			}
			$user_address->address = $this->view_data['location'];
			$user_address->street = request()->address1;
			$user_address->first_address = $this->view_data['location'];
			$user_address->second_address = request()->address1;
			$user_address->city = $this->view_data['city'];
			$user_address->state = request()->state;
			$user_address->country = ($country_name) ? $country_name : request()->country;
			$user_address->country_code = request()->country;
			$user_address->postal_code = $this->view_data['postal_code'];
			$user_address->latitude = $this->view_data['latitude'];
			$user_address->longitude = $this->view_data['longitude'];
			$user_address->default = 1;
			$user_address->save();

		}

		session()->put('locality', $this->view_data['locality']);
		session()->put('city', $this->view_data['city']);
		session()->put('address1', request()->address1);
		session()->put('state', request()->state);
		session()->put('country', request()->country);
		session()->put('location', $this->view_data['show_location']);
		session()->put('postal_code', $this->view_data['postal_code']);
		session()->put('latitude', $this->view_data['latitude']);
		session()->put('longitude', $this->view_data['longitude']);
		return json_encode(['success' => 'true', 'data' => $this->view_data]);
	}

	//search based on top category

	public function search_result(Request $request) {
		$user_details = auth()->guard('web')->user();

		$address_details = $this->address_details();
		return store_search($user_details, $address_details, $request->keyword);
	}
	//search based on top category

	public function search_data(Request $request) {

		$address_details = $this->address_details();
		$user_details = auth()->guard('web')->user();

		$address_details = $this->address_details();
		return store_search($user_details, $address_details, $request->keyword);
	}

	public function schedule_store() {

		$status = request()->status;
		$date = request()->date;
		$time = request()->time;

		if ($status == "ASAP") {
			$schedule_data = array('status' => $status, 'date' => '', 'time' => '');
			$schedule_data1 = array('status' => $status, 'date' => '', 'time' => '');
			$order_type = 0; //ASAP
		} else {
			$schedule_data = array('status' => $status, 'date' => $date, 'time' => $time);
			$schedule_data1 = array('status' => $status, 'date' => date('d M', strtotime($date)), 'time' => date('h:i A', strtotime($time)));
			$order_type = 1; //schedule
		}

		//update delivery option
		if (get_current_login_user_id()) {
			$user_address = UserAddress::where('user_id', get_current_login_user_id())->where('default', 1)->first();
			if ($user_address) {
				$user_address->order_type = $order_type;
				$user_address->delivery_time = $schedule_data['date'] . ' ' . $schedule_data['time'];
				$user_address->save();
			}
		}

		session::put('schedule_data', $schedule_data);

		return json_encode(['schedule_data' => $schedule_data1]);
	}

	/**
	 * Default user address
	 */

	public function address_details() {

		return user_address_details();
	}

}
