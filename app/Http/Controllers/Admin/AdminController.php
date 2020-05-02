<?php
/**
 * AdminController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Admin
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use App\Models\User;
use Auth;
use Charts;
use DB;
use Illuminate\Support\Carbon;

class AdminController extends Controller {
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Show the admin login page
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function login() {
		return view('admin/login');
	}

	/**
	 * Show the admin logout page
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function logout() {
		Auth::guard('admin')->logout();
		return redirect()->route('admin.login');
	}

	/**
	 * admin authenticate
	 *
	 * @return
	 */
	public function authenticate() {
		$request = request();
		$admin = Admin::where('username', $request->user_name)->first();
		if (@$admin->status != 'Inactive') {
			if (Auth::guard('admin')->attempt(['username' => $request->user_name, 'password' => $request->password])) {

				return redirect()->route('admin.dashboard');
			}
			// Redirect to dashboard page
			else {
				return back()->withErrors(['user_name' => trans('admin_messages.invalid_user_name_or_password')])->withInput();
			}

		} else {

			return back()->withErrors(['user_name' => trans('admin_messages.invalid_user_name_or_password')])->withInput();

		}
	}

	/**
	 * Show the admin dashboard page
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function dashboard() {

		$admin_earnings = Order::selectRaw('store_id,user_id,id,sum(booking_fee+store_commision_fee+driver_commision_fee) as amount,Month(created_at) as month')->where('status', 6)->where(DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))
			->get()->groupBy(function ($date) {
			return Carbon::parse($date->created_at)->format('m');
		});

		$admin_earnings = $admin_earnings->toArray();

		foreach ($admin_earnings as $key => $value) {
			foreach ($value as $val) {
				$month[] = array('amount' => $val['amount'], 'month' => $val['month']);

			}
		}

		$months = array_column($month, 'month');
		$amount = array_column($month, 'amount');

		$data = [];
		for ($i = 1; $i <= 12; $i++) {

			$monthName = date('F', mktime(0, 0, 0, $i, 10));

			if (false !== $key = array_search($i, $months)) {

				$data[] = array('month' => $monthName, 'amount' => $amount[$key]);

			} else {

				$data[] = array('month' => $monthName, 'amount' => 0);
			}

		}

		$months = array_column($data, 'month');
		$amount = array_column($data, 'amount');

		$this->view_data['total_drivers'] = User::where('type', 2)->get()->count();
		$this->view_data['total_stores'] = User::where('type', 1)->get()->count();
		$this->view_data['total_users'] = User::where('type', 0)->get()->count();
		$this->view_data['total_booking'] = Order::get()->count();
		$this->view_data['form_name'] = 'Dashboard';

		$month = array_column($data, 'month');
		$amount = array_column($data, 'amount');

		$this->view_data['earning_chart'] = Charts::multi('line', 'highcharts')
			->title("Earnings for " . date('Y'))
			->dimensions(0, 500)
			->elementLabel(" ")
			->dataset('Earnings', $amount)
			->labels($month)
			->colors(['#43A422']);

		return view('admin/dashboard', $this->view_data);
	}

	
	/**
	 * Show the admin Details for Update
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		$this->view_data['result'] = Admin::find($id);
		return view('admin.admin_users.edit', $this->view_data);
	}

	/**
	 * Update Admin Details
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update($id)
	{
		$request = request();
		$rules = array(
            'username'   => 'required|unique:admin,username,'.$id,
            'email'      => 'required|email|unique:admin,email,'.$id,
            'status'     => 'required'
        );

        $messages = array();

        $attributes = array(
            'username'   => 'Username',
            'email'      => 'Email',
            'role'       => 'Role',
            'status'     => 'Status'
        );

        $request->validate($rules,$messages,$attributes);

        if($request->status == 'Inactive') {
            $activeAdminUsers = Admin::where('status' , 'Active')->where('id' , '!=', $id)->get();
            if($activeAdminUsers->count() < 1){
                flash_message('danger', 'Status Cannot be Updated. Because it is the only one admin account');
                return redirect()->route('edit_admin',['id' => $id]);
            }
        }
        $admin = Admin::find($id);

        $admin->username = $request->username;
        $admin->email    = $request->email;
        $admin->status   = $request->status;

        if($request->password != '')
            $admin->password = bcrypt($request->password);

        $admin->save();

        // Admin::update_role($id, $request->role);

        flash_message('success', 'Updated Successfully.');

		$this->view_data['result'] = Admin::find($id);
		return redirect()->route('admin.edit_admin',['id'=>$id]);
		// return view('admin.admin_users.edit', $this->view_data);
	}
}
