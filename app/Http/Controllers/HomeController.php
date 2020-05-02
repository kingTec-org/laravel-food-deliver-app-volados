<?php

/**
 * HomeController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Country
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;
use App;
use App\Models\Country;
use App\Models\HomeSlider;
use App\Models\Pages;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Session;
use Validator;

class HomeController extends Controller {

	//home page function

	public function home() {

		if (session::get('schedule_data') == null) {
			//dd('ss');
			$schedule_data = array('status' => 'ASAP', 'date' => '', 'time' => '');

			session::put('schedule_data', $schedule_data);
		}
		$data['slider'] = HomeSlider::where('status', 1)->type('user')->get();

		if (session('location')) {
			return redirect()->route('search');
		} else {
			return view('home/home', $data);
		}

	}

	//login page

	public function login() {

		$user = auth()->guard('web')->user();
		$data['country'] = Country::Active()->get();

		if ($user) {
			return redirect()->route('search');
		}
		return view('home/login', $data);
	}

	//signup form

	public function signup() {

		$user = auth()->guard('web')->user();

		if ($user) {
			return redirect()->route('search');
		}

		$this->view_data['country'] = Country::Active()->get();
		return view('home/signup', $this->view_data);
	}
	//static page

	public function static_page() {

		$this->view_data['page'] = Pages::where('url', request()->page)->where('status', 1)->firstOrFail();
		return view('home/static_page', $this->view_data);
	}

	public function store_signup_data() {
		$this->view_data['phone_number'] = request()->phone_number;
		$this->view_data['country_code'] = request()->country_code;
		$this->view_data['first_name'] = request()->first_name;
		$this->view_data['last_name'] = request()->last_name;
		$this->view_data['email_address'] = request()->email_address;
		$this->view_data['password'] = request()->password;
		$this->view_data['verification_code'] = random_num(4);

		$user = User::where('mobile_number', $this->view_data['phone_number'])->where('type', 0)->get();

		if (count($user)) {
			$data['message'] = trans('messages.driver.this_number_already_exists');
			return json_encode(['success' => 'no', 'data' => $data]);
		}
		session()->put('user_name', $this->view_data['first_name'] . ' ' . $this->view_data['last_name']);
		session()->put('phone_number', $this->view_data['phone_number']);
		session()->put('country_code', $this->view_data['country_code']);
		session()->put('email_address', $this->view_data['email_address']);
		session()->put('password', $this->view_data['password']);
		session()->put('verification_code', $this->view_data['verification_code']);

		$message = 'Your verification code is ' . $this->view_data['verification_code'];

		$phone_number = $this->view_data['country_code'] . $this->view_data['phone_number'];
		//$message_send = send_nexmo_message($phone_number, $message);
		return json_encode(['success' => 'true', 'data' => 'message send']);
		if ($message_send['status'] == 'Success') {
			return json_encode(['success' => 'true', 'data' => $message_send]);
		} else {
			return json_encode(['success' => 'no', 'data' => $message_send]);
		}
	}

	//otp conformation

	public function signup_confirm() {

		$user = User::where('mobile_number', session('phone_number'))->where('type', 0)->get();

		if (count($user)) {
			flash_message('danger', trans('messages.driver.this_number_already_have_account_please_login'));
			return redirect()->route('login');
		} else {
			$this->view_data['first_name'] = session('first_name');
			$this->view_data['last_name'] = session('last_name');
			$this->view_data['phone_number'] = session('phone_number');
			$this->view_data['country_code'] = session('country_code');
			$this->view_data['email_address'] = session('email_address');
			$this->view_data['password'] = session('password');
			return view('home/signup2', $this->view_data);
		}
	}

	//store data in database

	public function store_user_data() {

		$user = User::where('mobile_number', session('phone_number'))->where('type', 0)->get();

		if (count($user)) {
			flash_message('danger', trans('messages.driver.this_number_already_have_account_please_login'));
			return redirect()->route('login');
		} else {
			$user = new User;
			$user->mobile_number = session('phone_number');
			$user->name = session('user_name');
			$user->type = 0;
			$user->password = bcrypt(session('password'));
			$user->country_code = session('country_code');
			$user->email = session('email_address');
			$user->status = "1";
			$user->save();

			if (Auth::guard()->attempt(['mobile_number' => session('phone_number'), 'password' => session('password')])) {
				$intended_url = session('url.intended');
				if ($intended_url) {
					//create new order use session values
					add_order_data();
					return redirect()->route($intended_url); // Redirect to intended url page
				} else {
					return redirect()->route('search'); // Redirect to search page
				}
			} else {

				return redirect()->route('login'); // Redirect to login page
			}
		}
	}

	//forgot password

	public function forgot_password() {
		if (request()->method() != 'POST') {
			Session::forget('password_code');
			Session::forget('reset_user_id');
			return view('home/forgot_password');
		} else {

			$rules = array(
				'email' => 'required|email',
			);

			$niceNames = array(
				'email' => trans('messages.driver.email'),
			);

			$validator = Validator::make(request()->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {

				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {

				$email = request()->email;

				$user_details = User::where(['type' => 0, 'email' => $email])->first();

				if (count($user_details) == 0) {
					return back()->withErrors(['email' => trans('messages.profile_orders.no_account_for_this_email_you_need').' <a href="' . route('signup') . '">'. trans('messages.profile.register').'</a>'])->withInput();
				}

				if (session::get('password_code') == null) {

					session::put('reset_user_id', $user_details->id);
					$user = User::find($user_details->id);
					$user->otp = random_num(4);
					$user->save();

					otp_for_forget_eater($email, $user->otp);
				}
				return redirect()->route('otp_confirm');

			}
		}
	}

	//otp confirm from mail

	public function otp_confirm() {
		if (request()->method() == 'POST') {

			$rules = array(
				'code_confirm' => 'required',
			);
			// validation custom messages
			$niceNames = array(
				'code_confirm' => trans('admin_messages.code'),
			);

			$validator = Validator::make(request()->all(), $rules);
			$validator->setAttributeNames($niceNames);
			if ($validator->fails()) {

				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {

				$code = request()->code_confirm;
				$user_id = request()->user_details;

				$user = User::find($user_id);

				// dd($session_code);
				if ($user->otp != $code) {
					return back()->withErrors(['code_confirm' => trans('messages.store_dashboard.code_is_incorrect')])->withInput(); // Form calling with Errors and Input values
				}
				return redirect()->route('reset_password');
			}
		} else {
			$user_id = session::get('reset_user_id');
			$user_details = User::findOrFail($user_id);
			$this->view_data['user_details'] = $user_details;

			return view('home/forgot_password2', $this->view_data);

		}
	}

	//reset password

	public function reset_password() {

		if (request()->method() == 'POST') {

			$rules = array(
				'password' => 'required|min:6|same:confirmed',
				'confirmed' => 'required',

			);

			$niceNames = array(
				'password' => trans('messages.profile.password'),
				'confirmed' => trans('messages.store.confirm_password'),
			);

			$messages = array(
				'min' => trans('validation.min.numeric', ['attribute' => trans('messages.profile.password'),'min' => 6]),
				'same' => trans('validation.confirmed', ['attribute' => trans('messages.profile.password')]),
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
				Session::forget('reset_user_id');
				if (Auth::guard()->attempt(['mobile_number' => $user_details->mobile_number, 'password' => $password])) {
					return redirect()->route('home');
				} else {

					return redirect()->route('login');
				}
			}
		} else {
			$this->view_data['user_id'] = session::get('reset_user_id');
			User::findOrFail($this->view_data['user_id']);
			return view('home/reset_password', $this->view_data);
		}

	}

	//password change

	public function password_change() {

		$user_id = request()->id;
		$password = request()->password;

		$user = User::find($user_id);
		$user->password = bcrypt($password);

		$user->save();

		$user_details = User::find($user_id);

		if (Auth::guard()->attempt(['mobile_number' => $user_details->mobile_number, 'password' => $password])) {

			return json_encode(['success' => 'true']); // Response for success

		} else {

			return json_encode(['success' => 'none']); // Response for failure
		}

	}

	/**
	 * Set session for Currency & Language while choosing footer dropdowns
	 *
	 */
	public function set_session(Request $request) {
		 if ($request->language) {
			Session::put('language', $request->language);
			App::setLocale($request->language);
		}
	}

}
