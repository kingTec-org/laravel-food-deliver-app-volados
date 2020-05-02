<?php
/**
 * UserController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    UserController
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataTableBase;
use App\DataTables\PenalityDataTable;

use App\Models\Order;
use App\Models\User;
use DataTables;
use Hash;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function add_user(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_action'] = route('admin.add_user');
			$this->view_data['form_name'] = trans('admin_messages.add_user');
			return view('admin/user/add_user', $this->view_data);
		} else {
		
			$rules = array(
				'first_name' => 'required',
				'last_name' => 'required',
				'email' => 'required|email|unique:user,email,NULL,user,type,0',
				'password' => 'required|min:6',
				'country_code' => 'required',
				'status' => 'required',
				'country_code' => 'required',
				'mobile_number' => 'required|regex:/^[0-9]+$/|min:6|unique:user,mobile_number,NULL,user,type,0',
			);

			// Add Admin User Validation Custom Names
			$niceNames = array(
				'first_name' => trans('admin_messages.first_name'),
				'last_name' => trans('admin_messages.last_name'),
				'email' => trans('admin_messages.email'),
				'password' => trans('admin_messages.password'),
				'country_code' => trans('admin_messages.country_code'),
				'mobile_number' => trans('admin_messages.mobile_number'),
				'status' => trans('admin_messages.status'),
				'country_code' => trans('admin_messages.country_code'),
			);

			$validator = Validator::make(request()->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$user = new User;
				$user->name = $request->first_name.' '.$request->last_name;
				$user->email = $request->email;
				$user->password = Hash::make($request->password);
				$user->country_code = $request->country_code;
				$user->mobile_number = $request->mobile_number;
				$user->status = $request->status;
				$user->type = 0;
				$user->save();

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.view_user');
			}

		}
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view() {
		$this->view_data['form_name'] = trans('admin_messages.user_management');
		return view('admin.user.view', $this->view_data);
		return $dataTable->render('admin.user.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function all_users() {

		$users = User::where('type', 0);
		$filter_type = request()->filter_type;

		$from = date('Y-m-d' . ' 00:00:00', strtotime(change_date_format(request()->from_dates)));
		if (request()->to_dates != '') {
			$to = date('Y-m-d' . ' 23:59:59', strtotime(change_date_format(request()->to_dates)));

			// $users = $users->whereBetween('created_at', array($from, $to));
			$users = $users->where('created_at', '>=', $from)->where('created_at', '<=', $to);
		}
		$users = $users->get();
		// dd($users);
		$datatable = DataTables::of($users)
			->addColumn('id', function ($users) {
				return @$users->id;
			})
			->addColumn('first_name', function ($users) {
				return @$users->first_name;
			})
			->addColumn('last_name', function ($users) {
				return @$users->last_name;
			})
			->addColumn('email', function ($users) {
				return @$users->email;
			})
			->addColumn('status', function ($users) {
				return @$users->status_text;
			})
			->addColumn('created_at', function ($users) {
				return @$users->created_at;
			})
			->addColumn('action', function ($users) {
				return '<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.edit_user', $users->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_user', $users->id) . '"><i class="material-icons">close</i></a>';
			});
		$columns = ['id', 'first_name','last_name', 'email', 'status', 'created_at'];
		$base = new DataTableBase($users, $datatable, $columns,'Users');
		return $base->render(null);

	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		$user = User::whereId($request->id)->first();
		if($user->wallet_amount>0)
			flash_message('danger', 'This User have some amount an our wallet So can\'t delete this user.' );
		else{
			$is_order = Order::where('user_id', $user->id)->notstatus()->first();
			if ($is_order) {
				flash_message('danger', 'Sorry,This user booked some orders. So, Can\'t delete this user.');
			} else {

				$user->delete_data();
				flash_message('success', trans('admin_messages.deleted_successfully'));
			}
		}
		return redirect()->route('admin.view_user');
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit_user(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_name'] = trans('admin_messages.edit_user');
			$this->view_data['form_action'] = route('admin.edit_user', $request->id);
			$this->view_data['user'] = User::findOrFail($request->id);

			return view('admin/user/add_user', $this->view_data);
		} else {
		

			$rules = array(
				'first_name' => 'required',
				'last_name' => 'required',
				'email' => 'required|email|unique:user,email,' . $request->id . ',id,type,0',
				'status' => 'required',
				'country_code' => 'required',
				'mobile_number' => 'required|regex:/^[0-9]+$/|min:6|unique:user,mobile_number,' . $request->id . ',id,type,0',
			);
			if ($request->password) {
				$rules['password'] = 'min:6';
			}

			// Add Admin User Validation Custom Names
			$niceNames = array(
				'first_name' => trans('admin_messages.first_name'),
				'last_name' => trans('admin_messages.last_name'),
				'email' => trans('admin_messages.email'),
				'password' => trans('admin_messages.password'),
				'mobile_number' => trans('admin_messages.mobile_number'),
				'status' => trans('admin_messages.status'),
				'country_code' => trans('admin_messages.country_code'),
			);

			$validator = Validator::make(request()->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$user = User::find($request->id);
				$user->name = $request->first_name.' '.$request->last_name;
				$user->email = $request->email;
				if ($request->password) {
					$user->password = Hash::make($request->password);
				}

				$user->country_code = $request->country_code;
				$user->mobile_number = $request->mobile_number;
				$user->status = $request->status;
				$user->type = 0;
				$user->save();

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.view_user');
			}

		}
	}


	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function penality(PenalityDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.penalty');
		return $dataTable->render('admin.user.penality', $this->view_data);
	}

}
