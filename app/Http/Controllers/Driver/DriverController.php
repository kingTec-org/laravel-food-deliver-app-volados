<?php

/**
 * DriverController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Driver
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\DriverRequest;
use App\Models\File;
use App\Models\FileType;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Payout;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\VehicleType;
use App\Traits\FileProcessing;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Session;
use Storage;
use Validator;

class DriverController extends Controller {
	use FileProcessing;
	/**
	 * Signup driver
	 *
	 * @param array $request    Get method and Post method
	 * @return signup page redirect to profile page
	 */

	public function signup() {

		if (!$_POST) {

			$this->view_data['country'] = Country::where('status', 1)->get();

			return view('driver/signup', $this->view_data);

		} else {

			$rules = array(
				'first_name' => 'required',
				'last_name' => 'required',
				'phone_number' => 'required|numeric|regex:/[0-9]{6}/',
				'password' => 'required|min:6',
				'address' => 'required',
				'user_type' => 'required',
				'country_code' => 'required',
			);

			// Add Driver Validation Custom Names
			$niceNames = array(
				'first_name' => 'First name',
				'last_name' => 'Last name',
				'password' => 'Password',
				'address' => 'City',
				'user_type' => 'User Type',
				'phone_number' => trans('messages.driver.phone_number'),
			);

			// Edit Rider Validation Custom Fields message
			$messages = array(
				'required' => ':attribute '.trans('messages.driver.is_required'),
				'phone_number.regex' => trans('messages.driver.phone_number_should_be_minimum_characters'),
				'min' => trans('validation.min.numeric', ['attribute' => trans('messages.profile.password'),'min' => 6]),
			);
			$validator = Validator::make(request()->all(), $rules, $messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$user = User::where('mobile_number', request()->phone_number)->where('type', 2)->get();

				$user_email = User::where('email', request()->email)->where('type', 2)->get();

				if (count($user)) {
					return back()->withErrors(['phone_number' => trans('messages.driver.mobile_number_already_exists')])->withInput(); // Form calling with Errors and Input values
				}
				if (count($user_email)) {
					return back()->withErrors(['email' => trans('messages.driver.email_already_exists')])->withInput(); // Form calling with Errors and Input values
				}

				$user = new User;

				$user->name = request()->first_name . ' ' . request()->last_name;
				$user->user_first_name = request()->first_name;
				$user->user_last_name = request()->last_name;
				$user->email = request()->email;
				$user->mobile_number = request()->phone_number;
				$user->country_code = request()->country_code;
				$user->password = bcrypt(request()->password);
				$user->type = 2;

				$user->status = 2;

				$user->save();

				$driver = new Driver;
				$driver->user_id = $user->id;
				$driver->save();

				$country_name = Null;

				if (request()->code) {

					$country_name = Country::where('code', request()->code)->first()->name;
				}

				$user_address = UserAddress::where('user_id', $user->id)->first();
				if (!$user_address) {

					$user_address = new UserAddress;
					$user_address->user_id = $user->id;
					$user_address->default = 1;
					$user_address->type = 0;
				}

				$user_address->address = request()->address;
				$user_address->street = request()->address_line1 ? request()->address_line1 : Null;
				$user_address->city = request()->city ? request()->city : Null;
				$user_address->state = request()->state ? request()->state : Null;
				$user_address->postal_code = request()->postal_code ? request()->postal_code : Null;

				$user_address->country = $country_name ? $country_name : request()->country;
				$user_address->country_code = request()->country ? request()->country : Null;
				$user_address->latitude = request()->latitude ? request()->latitude : Null;
				$user_address->longitude = request()->longitude ? request()->longitude : Null;

				$user_address->save();
				//store info for login
				Session::put('driver_id', $user->id);
				Session::put('driver_password', request()->password);

				if (Auth::guard('driver')->attempt(['mobile_number' => $user->mobile_number, 'password' => Session::get('driver_password'), 'type' => 2])) {

					flash_message('success', trans('messages.driver.registered_successfully')); // Call flash message function
					$data['driver_details'] = auth()->guard('driver')->user();

					return redirect()->route('driver.profile', $data); // Redirect to dashboard page

				} else {

					flash_message('danger', trans('messages.driver.invalid_driver_details'));
					return redirect()->route('driver.signup');
				}
			}

		}

	}

	/**
	 * login for driver
	 *
	 * @param array $request    Get method and post method
	 * @return password page
	 */

	public function login() {

		if (!$_POST) {

			$data['country'] = Country::where('status', 1)->get();

			return view('driver/login', $data);

		} else {

			$rules = array(

				'phone_number' => 'required|numeric|regex:/[0-9]{6}/',
			);

			$messages = array(
        'required'        => ':attribute '.trans('messages.driver.field_is_required'), 
        );

			$niceNames = array(
				'phone_number' => trans('messages.driver.phone_number'),
			);

			$validator = Validator::make(request()->all(), $rules,$messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$user = User::where('mobile_number', request()->phone_number)->where('country_code', request()->country)->where('type', 2)->first();

				//password page redirect
				if ($user != '') {
					Session::put('driver_id', $user->id);
					Session::put('driver_phone', request()->phone_number);

					return redirect()->route('driver.password');
				} else {
					flash_message('danger', trans('messages.profile.invalid_mobile_number'));
					return back();
				}
			}
		}
	}

	/**
	 * password for driver
	 *
	 * @param array $request    Get method and post method
	 * @return profile page
	 */

	public function password() {

		if (!$_POST) {

			return view('driver/password');
		} else {

			$rules = array(
				'password' => 'required',
			);

			$niceNames = array(
				'password' => trans('messages.profile.password'),
			);

			$messages = array(
				'required' => ':attribute '.trans('messages.driver.field_is_required'),
			);
			$validator = Validator::make(request()->all(), $rules, $messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if (Session::get('driver_id')) {

					$user_id = Session::get('driver_id');
					$user_phone = Session::get('driver_phone');

					if (Auth::guard('driver')->attempt(['mobile_number' => $user_phone, 'password' => request()->password, 'type' => 2])) {
						session()->forget('driver_id');
						session()->forget('driver_email_phone');

						$intended_url = session::get('url.intended');
						if ($intended_url) {
							return redirect()->route($intended_url); // Redirect to intended url page
						} else {

							$data['driver_details'] = auth()->guard('driver')->user();
							return redirect()->route('driver.profile', $data); // Redirect to search page
						}
					} else {
						flash_message('danger', trans('messages.profile.incorrect_password'));
						return back();

					}

				}
			}
		}
	}

	/**
	 * logout for driver
	 *
	 * @param array $request    Get method
	 * @return login page
	 */

	public function logout() {

		Auth::guard('driver')->logout();
		session::forget('driver_id');
		session::forget('driver_email_phone');
		session::forget('driver_status');
		session::forget('driver_password');
		session::forget('url.intended');

		return redirect()->route('driver.login');
	}

	/**
	 * password change for driver
	 *
	 * @param array $request    Get method
	 * @return passsword page
	 */

	public function forgot_password() {

		session::forget('password_code');
		return view('driver/forgot_password');
	}

	/**
	 * otp confirm for driver
	 *
	 * @param array $request    Post method
	 * @return code request page
	 */

	public function otp_confirm() {

		if (!$_POST) {

			flash_message('danger', 'Try Again!');
			return redirect()->route('driver.forgot_password');
		} else {

			$rules = array(
				'email' => 'required|email',
			);

			$niceNames = array(
				'email' => trans('messages.driver.email'),
			);

			$messages = array(
				'required' => ':attribute '.trans('messages.driver.is_required'),
				'email' => trans('validation.email', ['attribute' => trans('messages.driver.email')]),
			);
			$validator = Validator::make(request()->all(), $rules, $messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$email = request()->email;

				$user_details = User::where(['type' => 2, 'email' => $email])->first();

				if (count($user_details) == 0) {
					return back()->withErrors(['email' => trans('messages.driver.no_account_exist_for_email')])->withInput();
				}

				if (session::get('password_code') == null) {

					$otp = random_num(4);
					session::put('password_user_id', $user_details->id);
					$user_details->otp = $otp;
					$user_details->save();
					otp_for_forget_eater($email, $otp);
				}
				return redirect()->route('driver.reset_password');

			}
		}
	}

	/**
	 * otp confirm for driver
	 *
	 * @param array $request    post method
	 * @return code confirm page
	 */

	public function reset_password() {

		if (!$_POST) {
			$user_details = User::find(session('password_user_id'));
			if ($user_details == '') {
				flash_message('danger', 'Try Again!');
				return redirect()->route('driver.forgot_password');
			}
			$this->view_data['user_details'] = $user_details;
			return view('driver/forgot_password2', $this->view_data);
		} else {

			$rules = array(
				'code_confirm' => 'required',
			);

			$niceNames = array(
				'code_confirm' => trans('admin_messages.code'),
			);

			$messages = array(
				'required' => ':attribute '.trans('messages.driver.is_required'),
			);
			$validator = Validator::make(request()->all(), $rules, $messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$code = request()->code_confirm;
				$user_id = request()->user_details;
				$user = User::find($user_id);
				if ($user->otp != $code) {
					return back()->withErrors(['code_confirm' => trans('messages.driver.invalid_code_please_try_again')])->withInput();
				}

				return redirect()->route('driver.password_change');
			}
		}
	}

	/**
	 * password reset for driver
	 *
	 * @param array $request    Post method
	 * @return reset password page redirect to profile page
	 */

	public function password_change() {

		if (!$_POST) {
			$this->view_data['user_id'] = session('password_user_id');
			if ($this->view_data['user_id'] == '') {
				flash_message('danger', 'Try Again!');
				return redirect()->route('driver.forgot_password');
			}
			return view('driver/reset_password', $this->view_data);
		} else {

			$rules = array(
				'password' => 'required|min:6|confirmed',
				'password_confirmation' => 'required',
			);

			$niceNames = array(
				'password' => trans('messages.profile.password'),
				'password_confirmation' => trans('messages.store.confirm_password'),
			);

			$messages = array(
				'min' => trans('validation.min.numeric', ['attribute' => trans('messages.profile.password'),'min' => 6]),
				'confirmed' => trans('validation.confirmed', ['attribute' => trans('messages.profile.password')]),
			);

			$validator = Validator::make(request()->all(), $rules,$messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$user_id = request()->user_id;
				$password = request()->password;

				$user = User::find($user_id);
				$user->password = bcrypt($password);

				$user->save();

				$user_details = User::find($user_id);
				if (Auth::guard('driver')->attempt(['email' => $user_details->email, 'password' => $password, 'type' => 2])) {

					flash_message('success', trans('admin_messages.updated_successfully'));
					session()->forget('password_user_id');
					$data['driver_details'] = auth()->guard('driver')->user();
					return redirect()->route('driver.profile', $data);

				} else {
					session()->forget('password_user_id');
					flash_message('danger', trans('messages.driver.something_went_wrong_try_again'));
					return redirect()->route('driver.login');
				}

			}
		}
	}

	/**
	 * profile for driver
	 *
	 * @param array $request    Get method and Post method
	 * @return profile page
	 */

	public function profile() {

		if (!$_POST) {

			$user = auth()->guard('driver')->user();
			$driver = Driver::where('user_id', $user->id)->first();

			$this->view_data['driver_details'] = $driver;
			$this->view_data['country_code'] = Country::where('status', 1)->get();

			$filetype = FileType::where('name', 'driver_image')->first();

			if ($this->view_data['driver_details']) {

				$file_image = File::where(['source_id' => $driver->id, 'type' => $filetype->id])->first();

				if ($file_image != '') {
					$this->view_data['profile_image'] = url('/') . '/storage/images/driver/' . $file_image->name;
				} else {
					$this->view_data['profile_image'] = url('/') . '/images/user.png';
				}
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}

			return view('driver/profile', $this->view_data);

		} else {

			$rules = array(
				'first_name' => 'required',
				'last_name' => 'required',
				'email' => 'required|email',
				'mobile' => 'required',
				'address' => 'required',
			);

			$niceNames = array(
				'email' => trans('messages.driver.email'),
				'mobile' => trans('messages.driver.phone_number'),
				'address' => trans('messages.profile.address'),
				'first_name' => trans('messages.driver.first_name'),
				'last_name' => trans('messages.driver.last_name'),
			);

			$messages = array(
				'required' => ':attribute '.trans('messages.driver.is_required'),
			);
			$validator = Validator::make(request()->all(), $rules, $messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {

				$user_id = auth()->guard('driver')->user()->id;

				$user = User::find($user_id);

				if ($user) {

					$user->email = request()->email;
					$user->mobile_number = request()->mobile;
					$user->name = request()->first_name . ' ' . request()->last_name;
					$user->user_first_name =request()->first_name;
					$user->user_last_name = request()->last_name;
					$user->save();

					$user_address = UserAddress::where('user_id', $user->id)->first();

					$country_name = Null;

					if (request()->country) {

						$country_name = Country::where('code', request()->country)->first()->name;
					}

					if (!$user_address) {

						$user_address = new UserAddress;
						$user_address->user_id = $user->id;
						$user_address->default = 1;
						$user_address->type = 0;
					}

					$user_address->address = request()->address;
					$user_address->street = request()->address_line1 ? request()->address_line1 : Null;
					$user_address->city = request()->city ? request()->city : Null;
					$user_address->state = request()->state ? request()->state : Null;
					$user_address->postal_code = request()->postal_code ? request()->postal_code : Null;

					$user_address->country = $country_name ? $country_name : request()->country;
					$user_address->country_code = request()->country ? request()->country : Null;
					$user_address->latitude = request()->latitude ? request()->latitude : Null;
					$user_address->longitude = request()->longitude ? request()->longitude : Null;

					$user_address->save();
					$this->update_status($user_id);
					flash_message('success', trans('admin_messages.updated_successfully'));
					return redirect()->route('driver.profile');
				} else {

					flash_message('danger', trans('messages.driver.something_went_wrong_try_again'));
					return redirect()->route('driver.profile');
				}
			}

		}
	}

	/**
	 * document for driver
	 *
	 * @param array $request    Get method and Post method
	 * @return document page
	 */

	public function documents() {

		if (!$_POST) {

			$this->view_data['driver_details'] = auth()->guard('driver')->user();

			$this->view_data['driver_image'] = url('/') . '/images/user.png';
			$this->view_data['driver_licence_back'] = url('/') . '/images/icon1.png';
			$this->view_data['driver_licence_front'] = url('/') . '/images/icon2.png';
			$this->view_data['driver_insurance'] = url('/') . '/images/icon3.png';
			$this->view_data['driver_registeration_certificate'] = url('/') . '/images/icon1.png';
			$this->view_data['driver_motor_certiticate'] = url('/') . '/images/icon2.png';

			$type = array('driver_image', 'driver_licence_back', 'driver_licence_front', 'driver_insurance', 'driver_registeration_certificate', 'driver_motor_certiticate');

			$filetype = FileType::whereIn('name', $type)->get();

			if (count($filetype) > 0) {
				for ($i = 0; $i < count($filetype); $i++) {
					$file_image[$filetype[$i]->name] = File::where(['source_id' => $this->view_data['driver_details']->driver->id, 'type' => $filetype[$i]->id])->first();
				}
			}

			if (count($file_image) > 0) {
				foreach ($file_image as $key => $value) {
					if ($value != null) {
						$this->view_data[$key] = url('/') . '/storage/images/driver/' . $value->name;
					}
				}
			}

			$filetype = FileType::where('name', 'driver_image')->first();

			if ($this->view_data['driver_details']->driver) {
				$file_image = File::where(['source_id' => $this->view_data['driver_details']->driver->id, 'type' => $filetype->id])->first();

				if ($file_image != '') {
					$this->view_data['profile_image'] = url('/') . '/storage/images/driver/' . $file_image->name;
				} else {
					$this->view_data['profile_image'] = url('/') . '/images/user.png';
				}
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}

			$this->update_status($this->view_data['driver_details']->id);

			return view('driver/documents', $this->view_data);
		}
	}

	public function update_status($driver_id) {

		$user = User::find($driver_id);
		if ($user->driver->vehicle_type == '') {
			$user->status = 2;
			$user->save();
			return true;
		} else if ($user->driver->documents->count() < 5) {

			$user->status = 3;
			$user->save();
			return true;

		} else if ($user->driver->vehicle_type != '' && $user->driver->documents->count() > 4) {
			if ($user->status != 1) {
				$user->status = 5;
			} else {
				$user->status = 1;
			}

			$user->save();
			return true;

		}
		return true;
	}
	/**
	 * vehicle details for driver
	 *
	 * @param array $request    Get method and Post method
	 * @return vehicle page
	 */

	public function vehicle_details() {
		if (request()->getMethod() == 'GET') {
			$this->view_data['driver_details'] = auth()->guard('driver')->user();
			$this->view_data['vehicle'] = VehicleType::where('status', 1)->get();

			$filetype = FileType::where('name', 'driver_image')->first();

			if ($this->view_data['driver_details']->driver) {
				$file_image = File::where(['source_id' => $this->view_data['driver_details']->driver->id, 'type' => $filetype->id])->first();

				if ($file_image != '') {
					$this->view_data['profile_image'] = url('/') . '/storage/images/driver/' . $file_image->name;
				} else {
					$this->view_data['profile_image'] = url('/') . '/images/user.png';
				}
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}

			return view('driver/vehicle_details', $this->view_data);
		} else {

			$rules = array(
				'vehicle_name' => 'required',
				'vehicle_number' => 'required',
				'vehicle_type' => 'required',
			);

			$niceNames = array(
				'vehicle_name' => trans('messages.driver.vehicle_name'),
				'vehicle_number' => trans('messages.driver.vehicle_number'),
				'vehicle_type' => trans('messages.driver.vehicle_type'),
			);

			$validator = Validator::make(request()->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {

				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {

				$user_id = auth()->guard('driver')->user()->id;
				$user = User::find($user_id);

				if ($user) {

					$driver = Driver::where('user_id', $user_id)->first();

					$driver->vehicle_type = request()->vehicle_type;
					$driver->vehicle_name = request()->vehicle_name;
					$driver->vehicle_number = request()->vehicle_number;
					$driver->save();
					$this->update_status($user_id);
					flash_message('success', trans('admin_messages.updated_successfully'));
					return redirect()->route('driver.vehicle_details');
				} else {

					flash_message('danger', trans('messages.driver.something_went_wrong_try_again'));
					return redirect()->route('driver.vehicle_details');
				}
			}
		}
	}

	/**
	 * payment for driver
	 *
	 * @param array $request    Get method and Post method
	 * @return payment page
	 */

	public function payment() {

		$this->view_data['driver_details'] = auth()->guard('driver')->user();

		$filetype = FileType::where('name', 'driver_image')->first();

		if ($this->view_data['driver_details']->driver) {
			$file_image = File::where(['source_id' => $this->view_data['driver_details']->driver->id, 'type' => $filetype->id])->first();

			if ($file_image != '') {
				$this->view_data['profile_image'] = url('/') . '/storage/images/driver/' . $file_image->name;
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}
		} else {
			$this->view_data['profile_image'] = url('/') . '/images/user.png';
		}

		$request = request();
		$driver = $this->view_data['driver_details'];

		$week = [];

		$weekly_trip = OrderDelivery::driverId([$driver->driver->id])->whereNotNull('confirmed_at')
			->status(['completed'])->orderBy('id', 'desc')
			->get()
			->groupBy(function ($date) {
				return Carbon::parse($date->confirmed_at)->format('W');
			});

		$total_count = DriverRequest::where('driver_id', $driver->driver->id)->count();
		$acceptance_count = DriverRequest::where('driver_id', $driver->driver->id)->status(['accepted'])->count();
		if ($acceptance_count != '0' || $total_count != '0') {
			$this->view_data['acceptance_rate'] = round(($acceptance_count / $total_count) * 100) . '%';
		} else {
			$this->view_data['acceptance_rate'] = '0%';
		}

		$this->view_data['completed_trips'] = OrderDelivery::where('driver_id', auth()->guard('driver')->user()->driver->id)->status(['completed'])->count();
		$this->view_data['cancelled_trips'] = OrderDelivery::where('driver_id', auth()->guard('driver')->user()->driver->id)->status(['cancelled'])->count();

		$this->view_data['currency_symbol'] = Currency::original_symbol($driver->currency_code);

		$sum = 0;

		foreach ($weekly_trip as $key => $value) {

			$total = 0;

			foreach ($value as $fare) {

				$total += $fare->total_fare - $fare->driver_earning;
				$symbol = $fare->order->currency->symbol;
				$year = date('Y', strtotime($fare->confirmed_at));

			}
			$sum += $total;
			$date = getWeekDates($year, $key);

			$format_date = date('d', strtotime($date['week_start'])).' '.trans('messages.driver.'.date('M', strtotime($date['week_start']))). ' - ' . date('d', strtotime($date['week_end'])).' '.trans('messages.driver.'.date('M', strtotime($date['week_start'])));

			$format_date1 = date('Y-m-d', strtotime($date['week_start'])) . ',' . date('Y-m-d', strtotime($date['week_end']));

			$week[] = ['week' => $format_date,
				'week_format' => $format_date1,
				'currency_symbol' => $symbol,
				'total_fare' => numberFormat($total),
				'year' => $year,
				'date' => $date['week_start']];
		}

		$this->view_data['total_earnings'] = numberFormat($sum);
		//dd($week);
		$this->view_data['trip_week_details'] = isset($week) ? $week : [];

		return view('driver/payment', $this->view_data);
	}

	/**
	 * trips for driver
	 *
	 * @param array $request    Get method and Post method
	 * @return trips page
	 */

	public function trips() {

		$request = request();
		$driver = auth::guard('driver')->user();

		$today_date = date("Y-m-d");

		if (request()->month) {
			$month = request()->month;
		} else {
			$month = date('m');

		}

		$today_delivery = OrderDelivery::driverId([$driver->driver->id])->whereMonth('confirmed_at', '=', $month)->orderBy("order_id", "desc")
			->paginate(10);

		$this->view_data['links'] = $today_delivery->links();

		$today_delivery = $today_delivery->map(
			function ($delivery) {

				return [
					'id' => $delivery->order_id,
					'total_fare' => numberFormat($delivery->total_fare - $delivery->driver_earning),
					'date' => date('d/m/Y H:i', strtotime($delivery->updated_at))
					.' '.trans('messages.driver.'.date('a', strtotime($delivery->updated_at))),
					'date1' => trans('messages.profile_orders.'.date('l', strtotime($delivery->updated_at))).' '.trans('messages.driver.'.date('F', strtotime($delivery->updated_at))).' '.date('d, Y H:i ', strtotime($delivery->updated_at))
					.' '.trans('messages.driver.'.date('a', strtotime($delivery->updated_at))),
					'pickup_time' => date('H:i', strtotime($delivery->confirmed_at))
					.' '.trans('messages.driver.'.date('a', strtotime($delivery->confirmed_at))),
					'drop_time' => date('H:i', strtotime($delivery->delivery_at))
					.' '.trans('messages.driver.'.date('a', strtotime($delivery->delivery_at))),
					'driver' => $delivery->order->user->name,
					'drop_address' => $delivery->drop_location,
					'pickup_address' => $delivery->pickup_location,
					'vehicle_name' => $delivery->driver->vehicle_type_name,
					'status' => $delivery->status_text,
					'map_image' => $delivery->trip_path,
					'payment_method' => ($delivery->order->payment_type == 0) ? 'cash on delivery' : 'debit/credit card',
				];
			}
		);

		$this->view_data['history_details'] = [
			'status_message' => 'Order delivery history listed successfully',
			'status_code' => '1',
			'today_delivery' => $today_delivery,
			'currency_code' => $driver->currency_code,
			'currency_symbol' => Currency::original_symbol($driver->currency_code),
		];

		$this->view_data['driver_details'] = auth()->guard('driver')->user();
		$this->view_data['month'] = $month;

		$filetype = FileType::where('name', 'driver_image')->first();

		if ($this->view_data['driver_details']->driver) {
			$file_image = File::where(['source_id' => $this->view_data['driver_details']->driver->id, 'type' => $filetype->id])->first();

			if ($file_image != '') {
				$this->view_data['profile_image'] = url('/') . '/storage/images/driver/' . $file_image->name;
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}
		} else {
			$this->view_data['profile_image'] = url('/') . '/images/user.png';
		}

		return view('driver/trips', $this->view_data);
	}

	/**
	 * image upload for driver
	 *
	 * @param array $request    Post method
	 * @return response uploaded data
	 */

	public function profile_upload() {

		$request = request();
		$auth_user = auth::guard('driver')->user()->id;
		$driver = Driver::where('user_id', $auth_user)->first();

		$errors = array();

		$acceptable = array(
			'image/jpeg',
			'image/jpg',
			'image/gif',
			'image/png',
		);

		if ((!in_array(request()->file('file')->getMimeType(), $acceptable)) && (!empty(request()->file('file')->getMimeType()))) {
			return ['status' => 'false', 'status_message' => trans('messages.driver.invalid_file_type_only_types_are_accepted')];

		}

		$rules = [
			'type' => 'required|in:licence_front,licence_back,registeration_certificate,insurance,motor_certiticate,image',
			'file' => 'required',
		];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return response()->json(
				[
					'status_message' => $validator->messages()->first(),
					'status_code' => '0',
				]
			);
		}

		$file = $request->file('file');
		$file_path = $this->fileUpload($file, 'public/images/driver');

		$this->fileSave('driver_' . $request->type, $driver->id, $file_path['file_name'], '1');
		$original_path = url(Storage::url($file_path['path']));

		if (request()->type == 'image') {
			$message = trans('messages.driver.profile_image_uploaded_successfully');

		} else {

			$message = trans('messages.driver.document_uploaded_successfully');
		}

		return response()->json(
			[
				'status_message' => $message,
				'status_code' => '1',
				'document_url' => $original_path,
			]
		);
	}

	/**
	 * daily trip for driver
	 *
	 * @param array $request    Get method
	 * @return daily trip page
	 */

	public function daily_statement() {

		$this->view_data['driver_details'] = auth()->guard('driver')->user();

		$filetype = FileType::where('name', 'driver_image')->first();

		if ($this->view_data['driver_details']->driver) {
			$file_image = File::where(['source_id' => $this->view_data['driver_details']->driver->id, 'type' => $filetype->id])->first();

			if ($file_image != '') {
				$this->view_data['profile_image'] = url('/') . '/storage/images/driver/' . $file_image->name;
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}
		} else {
			$this->view_data['profile_image'] = url('/') . '/images/user.png';
		}

		$request = request();

		$date_week = explode(',', $request->date);

		$driver = $this->view_data['driver_details'];

		$daily = [];

		$daily_trip = OrderDelivery::driverId([$driver->driver->id])->whereNotNull('confirmed_at')->select()->addSelect(\DB::raw("DATE_FORMAT(confirmed_at, '%d-%m-%Y') as date"))->whereBetween(DB::raw('Date(confirmed_at)'), [$date_week[0], $date_week[1]])->status(['completed'])->get();

		$daily_trip = $daily_trip->groupBy('date');
		$statement1 = $daily_trip;

		$cash1 = Order::whereDriverId($driver->driver->id)->status('completed')->whereBetween(DB::raw('Date(updated_at)'), [$date_week[0], $date_week[1]])->get();

		$common = Order::whereDriverId($driver->driver->id)->status('completed')->whereBetween(DB::raw('Date(updated_at)'), [$date_week[0], $date_week[1]]);

		$cash = (clone $common)->where('payment_type', 0)->where('total_amount', '!=', 0)->get();
		$driver_fee = (clone $common)->where('payment_type', 0)->get();
		$card = (clone $common)->where('payment_type', 1)->get();

		$driver_commision_fee = ($driver_fee->sum('driver_commision_fee')) + ($card->sum('driver_commision_fee'));

		$cash_collected1 = ($cash->sum('owe_amount')) + ($cash->sum('delivery_fee')) - ($cash->sum('driver_commision_fee'));

		$payout1 = Payout::whereUserId($driver->driver->user_id)->whereBetween(DB::raw('Date(updated_at)'), [$date_week[0], $date_week[1]])->get()->sum('amount');

		$total1 = 0;
		$statement1 = $statement1->flatMap(
			function ($date_list, $date) {
				return [

					["total_fare" => $date_list->sum("total_fare"),
						"day" => date('l', strtotime($date)),
						"format" => date('d/m', strtotime($date)),
						"date" => date('Y-m-d', strtotime($date)),

					],

				];
			}

		);

		$total1 = array_column($statement1->toArray(), 'total_fare');
		$total1 = array_sum($total1);

		$total_fare1 = $total1 - $driver_commision_fee;
		// dd($daily_trip);
		foreach ($daily_trip as $key => $value) {

			$total = 0;

			foreach ($value as $fare) {
				$total += (float) $fare->total_fare - $fare->driver_earning;
				$symbol = $fare->order->currency->symbol;
				$year = date('Y', strtotime($fare->confirmed_at));
				$day_val = date('d', strtotime($fare->confirmed_at)).' '.trans('messages.driver.'.date('M', strtotime($fare->confirmed_at)));
				$table_date = date('Y-m-d', strtotime($fare->confirmed_at));

			}

			$daily[] = [
				'date' => $day_val,
				'currency_symbol' => $symbol,
				'date_format' => $table_date,
				'total_fare' => numberFormat($total),
			];
		}
		$day = [
			'statement' => $statement1,
			'currency_symbol' => $symbol,
			'total_fare' => numberFormat($total_fare1),
			'base_fare' => numberFormat($total1),
			'access_fee' => numberFormat($driver_commision_fee),
			'cash_collected' => numberFormat($cash_collected1),
			'completed_trips' => $daily_trip->count(),
			'format_date' => trans('messages.driver.'.date('M', strtotime($date_week[0]))).' '.date('d', strtotime($date_week[0])) . '-' . trans('messages.driver.'.date('M', strtotime($date_week[1]))).' '.date('d', strtotime($date_week[1])),
			'bank_deposits' => numberFormat($payout1),
			'time_online' => '',

		];
		//dd($day);
		$this->view_data['trip_day_details'] = isset($daily) ? $daily : [];

		$this->view_data['trip_day'] = isset($day) ? $day : [];

		return view('driver/payment_daily', $this->view_data);
	}

	/**
	 * detail trip for driver
	 *
	 * @param array $request    Get method
	 * @return response detail trip data
	 */

	public function detail_payment() {

		$request = request();
		$driver = auth::guard('driver')->user();

		$this->view_data['driver_details'] = auth()->guard('driver')->user();

		$filetype = FileType::where('name', 'driver_image')->first();

		if ($this->view_data['driver_details']->driver) {
			$file_image = File::where(['source_id' => $this->view_data['driver_details']->driver->id, 'type' => $filetype->id])->first();

			if ($file_image != '') {
				$this->view_data['profile_image'] = url('/') . '/storage/images/driver/' . $file_image->name;
			} else {
				$this->view_data['profile_image'] = url('/') . '/images/user.png';
			}
		} else {
			$this->view_data['profile_image'] = url('/') . '/images/user.png';
		}

		$from = $request->date;

		$daily_statement = OrderDelivery::driverId([$driver->driver->id])->whereNotNull('confirmed_at')->where(DB::raw('Date(confirmed_at)'), $from)->status(['completed'])->get();

		$total_fare = $daily_statement->sum('total_fare');

		$cash = Order::whereDriverId($driver->driver->id)->status('completed')->where(DB::raw('Date(updated_at)'), $from)->get();

		$common = Order::whereDriverId($driver->driver->id)->status('completed')->where(DB::raw('Date(updated_at)'), $from);
		$cash = (clone $common)->where('payment_type', 0)->where('total_amount', '!=', 0)->get();
		$driver_fee = (clone $common)->where('payment_type', 0)->get();
		$card = (clone $common)->where('payment_type', 1)->get();

		$driver_commision_fee = ($driver_fee->sum('driver_commision_fee')) + ($card->sum('driver_commision_fee'));

		// $cash_collected = $cash->sum('owe_amount') + $cash->sum('delivery_fee') - $driver_commision_fee;
		$cash_collected = ($cash->sum('owe_amount')) + ($cash->sum('delivery_fee')) - ($cash->sum('driver_commision_fee'));
		$payout = Payout::whereUserId($driver->driver->user_id)->where(DB::raw('Date(updated_at)'), $from)->get()->sum('amount');

		$daily_statement = $daily_statement->map(
			function ($daily) {
				return [
					'id' => $daily->order_id,
					'currency_symbol' => $daily->order->currency->symbol,
					'total_fare' => numberFormat($daily->total_fare - $daily->driver_earning),
					'time' => date('h:i a', strtotime($daily->confirmed_at)),

				];
			}
		);
		$earning = $total_fare - $driver_commision_fee;

		

		$time_detail =
			[
			'daily_statement' => $daily_statement,
			'date' => $from,
			'currency_symbol' => $daily_statement[0]['currency_symbol'],
			'format_date' => date('d/m', strtotime($from)),
			"day" => trans('messages.profile_orders.'.date('l', strtotime($from))),
			'total_fare' => numberFormat($earning),
			'base_fare' => numberFormat($total_fare),
			'access_fee' => numberFormat($driver_commision_fee),
			'cash_collected' => numberFormat($cash_collected),
			'completed_trips' => $daily_statement->count(),
			'bank_deposits' => numberFormat($payout),
			'time_online' => '',

		];
		//dd($time_detail);
		$this->view_data['time_detail'] = $time_detail;

		return view('driver/payment_detail', $this->view_data);

	}

	/**
	 * individual order for driver
	 *
	 * @param array $request    Post method
	 * @return response order details data
	 */

	public function particular_order() {

		$request = request();
		$driver = auth::guard('driver')->user();
		$trip_details = OrderDelivery::with(['order' => function ($query) {

			$query->with(['payout_table', 'currency']);

		}])->OrderId([$request->order_id])->first();

		$vehicle_type = $trip_details->driver ? $trip_details->driver->vehicle_type_name : '';

		$trip_details = replace_null_value($trip_details->toArray());

		$driver_commision_fee = numberFormat($trip_details['order']['driver_commision_fee']);

		$driver_payout = numberFormat($trip_details['total_fare'] - $driver_commision_fee);

		if ($trip_details['order']['payment_type'] == 0 && $trip_details['order']['total_amount'] != 0) {
			if ($trip_details['order']['owe_amount'] != 0) {
				$cash_collected = numberFormat($trip_details['order']['owe_amount'] + $driver_payout);
			} else {
				$cash_collected = numberFormat($trip_details['order']['total_amount']);
			}

		} else {
			$cash_collected = '0.00';
		}

		if ($trip_details['confirmed_at']) {
			$date = $trip_details['confirmed_at'];
		} else {
			$date = $trip_details['updated_at'];

		}

		$can_payout = Payout::where('order_id', $trip_details['order_id'])->
			where('user_id', $driver->user_id)->first();

		$cancel_payout = 0;

		if ($can_payout) {

			$cancel_payout = $can_payout->amount;

		}

		$owe_amount = '0';
		$distance_fare = '0';
		$pickup_fare = '0';
		$drop_fare = '0';

		if ($trip_details['status'] != '6') {

			$owe_amount = $trip_details['order']['owe_amount'];
			$distance_fare = $trip_details['est_distance'] ? (string) numberFormat($trip_details['distance_fare'] * $trip_details['est_distance']) : '0';
			$pickup_fare = $trip_details['pickup_fare'] ? (string) $trip_details['pickup_fare'] : '0.00';
			$drop_fare = $trip_details['drop_fare'] ? $trip_details['drop_fare'] : '0.00';
			$total_fare = $trip_details['total_fare'];
		} else {
			$cash_collected = '0.00';
			$total_fare = '0';
			$driver_payout = '0';
		}

		$trip = [

			'order_id' => $trip_details['order_id'],
			'total_fare' => $total_fare,
			'status' => $trip_details['status'],
			'vehicle_name' => $vehicle_type,
			'map_image' => $trip_details['trip_path'],
			'trip_date' => trans('messages.profile_orders.'.date('l', strtotime($date))).' '.date('d/m/Y h:i', strtotime($date)).' '.trans('messages.driver.'.date('a', strtotime($date))),
			'pickup_latitude' => $trip_details['pickup_latitude'],
			'pickup_longitude' => $trip_details['pickup_longitude'],
			'pickup_location' => $trip_details['pickup_location'],
			'drop_location' => $trip_details['drop_location'],
			'drop_latitude' => $trip_details['drop_latitude'],
			'drop_longitude' => $trip_details['drop_longitude'],
			'duration' => (string) $trip_details['duration'],
			'distance' => number_format($trip_details['drop_distance'], 1),
			'pickup_fare' => $pickup_fare,
			'drop_fare' => $drop_fare,
			'driver_payout' => (string) $driver_payout,
			'trip_amount' => $trip_details['order']['total_amount'],
			'delivery_fee' => $trip_details['order']['delivery_fee'],
			'owe_amount' => $owe_amount,
			'admin_payout' => (string) numberFormat($driver_commision_fee),
			'distance_fare' => $distance_fare,
			'cash_collected' => (string) $cash_collected,
			'driver_penality' => (string) $trip_details['order']['driver_penality'],
			'applied_penality' => (string) $trip_details['order']['app_driver_penality'],
			'cancel_payout' => (string) numberFormat($cancel_payout),
			'applied_owe' => (string) $trip_details['order']['applied_owe'],
			'notes' => (string) $trip_details['order']['driver_notes'],
			'currency_symbol' => (string) $trip_details['order']['currency']['symbol'],
		];

		return response()->json(
			[
				'status_code' => '1',
				'status_message' => 'successfully',
				'trip_details' => $trip,
			]
		);

	}

	/**
	 * static map for driver
	 *
	 * @param array $request    Post method
	 * @return response static map data
	 */

	public function static_map($order_id = '') {

		$order = Order::find($order_id);

		$user_id = get_store_user_id($order->store_id);

		$res_address = get_store_address($user_id);

		$user_address = get_user_address($order->user_id);

		// $origin = "45.291002,-0.868131";
		// $destination = "44.683159,-0.405704";

		$origin = $res_address->latitude . ',' . $res_address->longitude;
		$destination = $user_address->latitude . ',' . $user_address->longitude;

		$map_url = getStaticGmapURLForDirection($origin, $destination);

		// Trip Map upload //

		if ($map_url) {

			$directory = storage_path('app/public/images/map_image');

			if (!is_dir($directory = storage_path('app/public/images/map_image'))) {
				mkdir($directory, 0755, true);
			}

			$time = time();
			$imageName = 'map_' . $time . '.PNG';
			$imagePath = $directory . '/' . $imageName;
			file_put_contents($imagePath, file_get_contents($map_url));

			$this->fileSave('map_image', $order_id, $imageName, '1');

		}

	}
}
