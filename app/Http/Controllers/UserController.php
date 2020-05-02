<?php

/**
 * UserController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    User
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */


namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileType;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserPaymentMethod;
use App\Models\UsersPromoCode;
use App\Traits\FileProcessing;
use Auth;
use Illuminate\Http\Request;
use Session;
use Storage;
use Validator;

class UserController extends Controller {
	use FileProcessing;
	/**
	 * Email users Login authentication
	 *
	 * @param array $request    Post method inputs
	 * @return redirect     to dashboard page
	 */
	public function authenticate() {

		$rules = array(
			'phone_number' => 'required|numeric',
			'password' => 'required',
		);

		$niceNames = array(
			'phone_number' => trans('messages.driver.phone_number'),
			'password' => trans('messages.profile.password'),
		);

		$validator = Validator::make(request()->all(), $rules);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {

			return back()->withErrors($validator)->withInput()->with('error_code', 5);
			// Form calling with Errors and Input values
		} else {

			if (Auth::guard('web')->attempt(['mobile_number' => request()->phone_number, 'password' => request()->password, 'type' => 0, 'country_code' => request()->country])) {

				$intended_url = session::get('url.intended');
				if ($intended_url) {
					//create new order use session values
					add_order_data();
					return redirect()->route($intended_url); // Redirect to intended url page

				} else {

					return redirect()->route('search'); // Redirect to search page

				}
			} else {

				flash_message('danger', trans('messages.store_dashboard.invalid_credentials'));
				return back();

			}
		}

	}

	//logout current user

	public function logout() {
		Auth::logout();
		session::forget('order_data');
		session::forget('order_detail');
		session::forget('schedule_data');
		session::forget('url.intended');
		return redirect()->route('login');
	}

	//user profile details

	public function user_profile() {

		$this->view_data['user_details'] = auth()->guard('web')->user();

		$filetype = FileType::where('name', 'eater_image')->first();

		if ($this->view_data['user_details']) {
			$file_image = File::where(['source_id' => $this->view_data['user_details']->id, 'type' => $filetype->id])->first();

			if ($file_image != '') {
				$this->view_data['profile_image'] = url('/') . '/storage/images/eater/' . $file_image->name;
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}
		} else {
			$this->view_data['profile_image'] = url('/') . '/images/user.png';
		}

		//dd($this->view_data['user_details']);
		return view('user_profile', $this->view_data);
	}

	//user details changes

	public function user_details_store() {

		//dd(request()->all());
		//dd(request()->all(), request()->file('profile_photo'));
		$rules = array(
			'user_name' => 'required',
			'user_address' => 'required',
			'user_city' => 'required',
			'user_state' => 'required',
			'user_country' => 'required',
		);

		if (request()->profile_photo != null) {
			$rules['profile_photo'] = 'mimes:png,jpeg,jpg';
		}

		// Email login validation custom messages
		$messages = array(
			'user_name' => 'User Name',
			'user_address' => 'User Address',
			'user_city' => 'City',
			'user_state' => 'State',
			'user_country' => 'Country',
			'profile_photo' => 'Profile Photo',
		);

		// Email login validation custom Fields name
		$niceNames = array(
			'user_name' => trans('admin_messages.user_name'),
			'user_address' => trans('messages.profile.user_address'),
			'user_city' => 'City',
			'user_state' => 'State',
			'user_country' => 'Country',
			'profile_photo' => 'Profile Photo',
		);

		$validator = Validator::make(request()->all(), $rules, $messages);
		$validator->setAttributeNames($niceNames);

		if ($validator->fails()) {
			return back()->withErrors($validator)->withInput()->with('error_code', 5); // Form calling with Errors and Input values
		} else {

			$user_id = auth()->guard('web')->user()->id;

			if (request()->profile_photo != null) {

				$file = request()->file('profile_photo');
				$file_path = $this->fileUpload($file, 'public/images/eater');

				$this->fileSave('eater_image', $user_id, $file_path['file_name'], '1');
				$original_path = url(Storage::url($file_path['path']));
			}

			$user_detail = User::find($user_id);
			$user_address = UserAddress::where('user_id', $user_id)->first();
			if (!$user_address) {
				$user_address = new UserAddress;
				$user_address->user_id = $user_id;
				$user_address->default = 1;
				$user_address->type = 0;
			}

			$user_detail->name = request()->user_name;
			$user_detail->save();

			$user_address->address = request()->user_address;

			$user_address->address = request()->user_address;
			$user_address->street = request()->user_street;
			$user_address->city = request()->user_city;
			$user_address->state = request()->user_state;
			$user_address->postal_code = request()->user_postal_code;
			$user_address->country = request()->user_country;
			$user_address->latitude = request()->user_latitude;
			$user_address->longitude = request()->user_longitude;

			$user_address->save();

			flash_message('success', trans('admin_messages.updated_successfully'));
			return back();

		}
	}

	//user payment details

	public function user_payment() {

		$this->view_data['user_details'] = auth()->guard('web')->user();

		$this->view_data['payment_details'] = UserPaymentMethod::where('user_id', $this->view_data['user_details']->id)->first();

		$filetype = FileType::where('name', 'eater_image')->first();

		if ($this->view_data['user_details']) {
			$file_image = File::where(['source_id' => $this->view_data['user_details']->id, 'type' => $filetype->id])->first();

			if ($file_image != '') {
				$this->view_data['profile_image'] = url('/') . '/storage/images/eater/' . $file_image->name;
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}
		} else {
			$this->view_data['profile_image'] = url('/') . '/images/user.png';
		}
		$this->view_data['promo'] = UsersPromoCode::where('order_id','')->where('user_id', $this->view_data['user_details']->id)->get();

		return view('user_payment', $this->view_data);
	}
}
