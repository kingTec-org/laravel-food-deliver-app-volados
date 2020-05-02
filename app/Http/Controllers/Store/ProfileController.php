<?php

/**
 * ProfileController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Profile
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Category;
use App\Models\File;
use App\Models\Store;
use App\Models\StoreCategory;
use App\Models\StoreDocument;
use App\Models\StoreOffer;
use App\Models\StoreTime;
use App\Models\User;
use App\Models\UserAddress;
use App\Traits\FileProcessing;
use Auth;
use Illuminate\Http\Request;
use Storage;
use Validator;

class ProfileController extends Controller {

	use FileProcessing;

	public function index(Request $request) {

		$store_id = get_current_store_id();
		$data['store'] = $store = Store::where('id', $store_id)->first();
		$data['category'] = Category::Active()->pluck('name', 'id');
		$data['store_category'] = $store->store_category()->pluck('category_id', 'id')->toArray();
		$data['address'] = UserAddress::where('user_id', $store->user_id)->where('default', 1)->first();
		$data['basic'] = User::where('id', $store->user_id)->first();
		$data['open_time'] = (count($store->store_all_time) > 0) ? $store->store_all_time()->orderBy('day', 'ASC')->get() : [array('day' => '')];

		$data['country'] = Country::Active()->get();
		$data['documents'] = StoreDocument::with('file')->where('store_id', $store_id)->get();
		if (count($data['documents']) < 1) {
			$data['documents'] = array(array('name' => ''));
		}
		$data['banner_image'] = File::where('type', 3)->where('source_id', $store_id)->first();
		$data['map_key'] = site_setting('google_api_key');
   		$data['time_options'] = array();
        for($i=0; $i < 24;){
            if ((int) $i == $i)
                $a=$i.":00";
            else
                $a=($i-0.5).":30";
            $data['time_options'][date("H:i:s", strtotime($a))] = date("g:i", strtotime($a)).' '.trans('messages.driver.'.date("a", strtotime($a)));
            $i= $i+0.5;
        }

		if ($request->getMethod() == 'GET') {

			return view('store.profile', $data);

		}

		$all_request = $request->all();
		if ($request->dob) {
			$all_request['dob'] = date('Y-m-d', strtotime($request->dob));
		}

		$rules = array(

			'store_name' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
			'address' => 'required',
			'email' => 'required|email',
			'dob' => 'required|before:18 years ago',
			'price_rating' => 'required',
			'mobile_number' => 'required|regex:/[0-9]{6}/',
			'banner_image' => 'image|max:10240',

		);

		$niceNames = array(
			'name' => 'Store name',
			'dob' => 'Date of birth',
			'email' => 'Email',

		);
		$messages = array(
			'banner_image.max' => trans('messages.store.the_banner_image_may_not_greater_than'),
			'dob.before' => trans('messages.store.age_must_be_or_older'),
		);

		$validator = Validator::make($request->all(), $rules, $messages);

		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			// dd($validator);
			return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
		}

//Store

		$store->name = $request->store_name;
		$store->description = $request->description;
		$store->price_rating = $request->price_rating;
		$store->save();

// User Address

		$user_address = UserAddress::where('user_id', $store->user_id)->where('default', 1)->first();
		if ($user_address == '') {
			$user_address = new UserAddress;
		}
		$country = Country::where('code', $request->country_code)->first();
		$user_address->address = $request->address;
		$user_address->country = $country->name;
		$user_address->country_code = $country->country_code;
		$user_address->postal_code = $request->postal_code;
		$user_address->city = $request->city;
		$user_address->state = $request->state;
		$user_address->street = $request->street;
		$user_address->latitude = $request->latitude;
		$user_address->longitude = $request->longitude;
		$user_address->user_id = $store->user_id;
		$user_address->default = 1;
		$user_address->save();
//Store Category
		foreach ($request->category as $value) {
			if ($value) {

				$cousine = StoreCategory::where('store_id', $store_id)->where('category_id', $value)->first();
				if ($cousine == '') {
					$cousine = new StoreCategory;
				}

				$cousine->store_id = $store_id;
				$cousine->category_id = $value;
				$cousine->status = 1;
				$cousine->save();
			}
		}
		//delete cousine
		StoreCategory::where('store_id', $store_id)->whereNotIn('category_id', $request->category)->delete();

//User

		$user = User::find($store->user_id);
		$user->name = $request->first_name . ' ' . $request->last_name;
		$user->email = $request->email;
		$user->date_of_birth = date('Y-m-d', strtotime($request->dob));
		if ($user->mobile_number != $request->mobile_number || $user->country_code != $request->phone_code) {
			$user->mobile_no_verify = 0;
			$user->status = 4;
		}
		$user->mobile_number = $request->mobile_number;
		$user->country_code = $request->phone_code;

		$user->save();

//file

		if ($request->file('banner_image')) {

			$file = $request->file('banner_image');

			$file_path = $this->fileUpload($file, 'public/images/store/' . $store_id);

			$this->fileSave('store_banner', $store_id, $file_path['file_name'], '1');
			$orginal_path = Storage::url($file_path['path']);
			$size = get_image_size('store_image_sizes');
			foreach ($size as $value) {
				$this->fileCrop($orginal_path, $value['width'], $value['height']);
			}

		}

		flash_message('success', trans('admin_messages.updated_successfully'));
		return redirect()->route('store.profile');

	}

	public function update_open_time(Request $request) {

		$id = get_current_store_id();
		$store = StoreTime::find($id);

		if (@count($request->time_id)) {
			StoreTime::whereNotIn('id', $request->time_id)->where('store_id', $id)->delete();
		}
		foreach ($request->day as $key => $time) {

			if (@$request->time_id[$key]) {
				$store_update = StoreTime::find($request->time_id[$key]);
				$store_update->start_time = $request->start_time[$key];
				$store_update->end_time = $request->end_time[$key];
				$store_update->day = $request->day[$key];
				$store_update->status = $request->status[$key];
				$store_update->save();
			} else {
				$store_insert = new StoreTime;
				$store_insert->start_time = ($request->start_time[$key]);
				$store_insert->end_time = ($request->end_time[$key]);
				$store_insert->day = $request->day[$key];
				$store_insert->status = $request->status[$key];
				$store_insert->store_id = $id;
				$store_insert->save();

			}

		}

		flash_message('success', trans('admin_messages.updated_successfully'));
		return redirect()->route('store.profile', '#open_time');

	}

	public function update_documents(Request $request) {
		$store_id = get_current_store_id();
		StoreDocument::whereNotIn('document_id', $request->document_id)->delete();

		foreach ($request->document_name as $key => $value) {

			if ($request->document_id[$key] == '') {

				$multiple = 'multiple';
				$store_document = new StoreDocument;

			} else {

				$multiple = '';
				$store_document = StoreDocument::where('document_id', $request->document_id[$key])->first();

			}
			if (isset($request->document_file[$key])) {
				$file = $request->document_file[$key];
				$file_path = $this->fileUpload($file, 'public/images/store/' . $store_id . '/documents');
				$file_id = $this->fileSave('store_document', $store_id, $file_path['file_name'], '1', $multiple, $request->document_id[$key]);
				$store_document->document_id = $file_id;

			}
			$store_document->name = $request->document_name[$key];
			$store_document->store_id = $store_id;
			$store_document->save();

		}
		flash_message('success', trans('admin_messages.updated_successfully'));
		return redirect()->route('store.profile', '#document');
	}

	public function offers(Request $request) {

		$store_id = get_current_store_id();

		if ($request->getMethod() == 'GET') {

			$data['offer'] = StoreOffer::where('store_id', $store_id)->orderBy('id', 'desc')->get();

			return view('store.offers', $data);

		} else {

			if (isset($request->new_offers['id'])) {

				$offer = StoreOffer::find($request->new_offers['id']);
				$offer->offer_title = $request->new_offers['offer_title'];
				$offer->offer_description = $request->new_offers['offer_description'];
				$offer->start_date = $request->start_date;
				$offer->end_date = $request->end_date;
				$offer->percentage = $request->new_offers['percentage'];
				$offer->save();

			} else {

				$offer = new StoreOffer;
				$offer->offer_title = $request->new_offers['offer_title'];
				$offer->offer_description = $request->new_offers['offer_description'];
				$offer->start_date = $request->start_date;
				$offer->end_date = $request->end_date;
				$offer->percentage = $request->new_offers['percentage'];
				$offer->store_id = $store_id;
				$offer->status = 1;
				$offer->save();

			}

			$data['offer'] = StoreOffer::where('store_id', $store_id)->get();

			return $data;

		}

	}

	public function remove_offer(Request $request) {

		$offer = StoreOffer::find($request->id);

		if ($offer) {
			$offer->delete();

		}

		return "success";

	}

	public function status_update() {

		$store = Store::find(get_current_store_id());
		$store->status = request()->status;
		$store->save();
		return $store->status;
	}
	public function show_comments() {

		$values = explode(',', request()->comments);

		$comment_array = array_filter($values);
		$comments = '';

		foreach ($comment_array as $value) {
			$comments .= '<li>' . ucfirst($value) . '</li>';
		}
		return $comments != '' ? $comments : '<li>'. trans('messages.store_dashboard.no_comments').'</li>';
	}

	/*
		* status update for offer
		*
	*/

	public function offers_status() {

		$id = request()->id;
		$status = request()->status;

		$offer = StoreOffer::find($id);

		$offer->status = $status;

		$offer->save();

		return json_encode(['success' => true, 'offer' => $offer]);

	}

	public function send_message() {
		$code = rand(1000, 9999);
		$rules = [

			'mobile_no' => 'required|regex:/^[0-9]+$/|min:6|unique:user,mobile_number,' . get_current_login_user_id() . ',id,type,1',
		];

		$validator = Validator::make(request()->all(), $rules);

		if ($validator->fails()) {

			return ['status' => 'Failed', 'message' => trans('messages.driver.this_number_already_exists')];

		}

		$to = request()->code . request()->mobile_no;
		$message = trans('api_messages.register.verification_code') . $code;
		$status = send_nexmo_message($to, $message);
		$status['code'] = $code;
		if ($status['status'] != 'Failed') {
			$user = User::find(auth()->guard('store')->user()->id);
			$user->country_code = request()->code;
			$user->mobile_number = request()->mobile_no;
			$user->save();
		}
		return $status;
	}

	public function confirm_phone_no() {
		$user = User::find(auth()->guard('store')->user()->id);
		$user->mobile_no_verify = 1;
		$user->save();

		return '';
	}

}
