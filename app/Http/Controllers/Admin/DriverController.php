<?php
/**
 * DriverController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    DriverController
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DataTableBase;
use App\DataTables\DriverOweAmountDataTable;
use App\DataTables\DriverRequestDataTable;
use App\DataTables\DriverHomeSliderDataTable;

use App\Models\Driver;
use App\Models\User;
use App\Models\VehicleType;
use App\Models\OrderDelivery;
use App\Models\DriverHomeSlider;
use App\Traits\FileProcessing;
use DataTables;
use Hash;
use Illuminate\Http\Request;
use Validator;
use Storage;

class DriverController extends Controller {

	use FileProcessing;
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function add_driver() {
		if (request()->getMethod() == 'GET') {
			$this->view_data['form_action'] = route('admin.add_driver');
			$this->view_data['form_name'] = trans('admin_messages.add_driver');
			$this->view_data['vehicle_type'] = VehicleType::status()->get()->pluck('name', 'id');
			$this->view_data['driver_document'] = driver_default_documents();
			$user = new User;
			$this->view_data['driver_status'] = $user->statusTextArray;

			return view('admin/driver/add_driver', $this->view_data);
		} else {
			$request = request();
			$all_variables = request()->all();
			if ($all_variables['date_of_birth']) {
				$all_variables['convert_dob'] = date('Y-m-d', strtotime($all_variables['date_of_birth']));
			}

			$rules = array(
				'name' => 'required',
				'password' => 'required|min:6',
				'convert_dob' => 'required|before:18 years ago',
				'country_code' => 'required',
				'status' => 'required',
				'country_code' => 'required',
				'vehicle_type' => 'required',
				'vehicle_name' => 'required',
				'vehicle_number' => 'required',
				'email' => 'required|email|unique:user,email,NULL,id,type,2',
				'mobile_number' => 'required|regex:/^[0-9]+$/|min:6|unique:user,mobile_number,NULL,id,type,2',
			);

			foreach (driver_default_documents() as $value) {

				$rules[$value] = 'required|mimes:jpg,png,jpeg,gif,pdf';
				$niceNames[$value] = trans('admin_messages.' . $value);
			}
			if ($request->document) {
				foreach ($request->document as $key => $value) {
					$all_variables[$key] = $value;
				}
			}

			// Add Admin User Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.full_name'),
				'email' => trans('admin_messages.email'),
				'password' => trans('admin_messages.password'),
				'convert_dob' => trans('admin_messages.date_of_birth'),
				'country_code' => trans('admin_messages.country_code'),
				'mobile_number' => trans('admin_messages.mobile_number'),
				'status' => trans('admin_messages.status'),
				'country_code' => trans('admin_messages.country_code'),
				'vehicle_type' => trans('admin_messages.vehicle_type'),
				'vehicle_name' => trans('admin_messages.vehicle_name'),
				'vehicle_number' => trans('admin_messages.vehicle_number'),
			);

			$messages = array(
				'convert_dob.before' => 'Age must be 18 or older',
			);
			
			$validator = Validator::make($all_variables, $rules,$messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {

				$request = request();
				$driver = new User;
				$driver->name = $request->name;
				$driver->email = $request->email;
				$driver->password = Hash::make($request->password);
				$driver->date_of_birth = $all_variables['convert_dob'];
				$driver->country_code = $request->country_code;
				$driver->mobile_number = $request->mobile_number;
				$driver->status = $request->status;
				$driver->type = 2;
				$driver->save();

				$driver_vehicle = new Driver;
				$driver_vehicle->user_id = $driver->id;
				$driver_vehicle->vehicle_type = $request->vehicle_type;
				$driver_vehicle->vehicle_name = $request->vehicle_name;
				$driver_vehicle->vehicle_number = $request->vehicle_number;
				$driver_vehicle->save();

				if ($request->document) {
					foreach ($request->document as $key => $value) {

						$file = $request->file('document')[$key];

						$file_path = $this->fileUpload($file, 'public/images/driver');
						$this->fileSave('driver_' . $key, $driver_vehicle->id, $file_path['file_name'], '1');
					}
				}

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.view_driver');
			}

		}
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view() {
		$this->view_data['form_name'] = trans('admin_messages.driver_management');
		return view('admin.driver.view', $this->view_data);
		return $dataTable->render('admin.driver.view', $this->view_data);
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function driver_request(DriverRequestDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.driver_request');
		return $dataTable->render('admin.driver.driver_request', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function all_drivers() {

		$driver = User::where('type', 2);
		$filter_type = request()->filter_type;

		$from = date('Y-m-d' . ' 00:00:00', strtotime(change_date_format(request()->from_dates)));
		if (request()->to_dates != '') {
			$to = date('Y-m-d' . ' 23:59:59', strtotime(change_date_format(request()->to_dates)));
			$driver = $driver->where('created_at', '>=', $from)->where('created_at', '<=', $to);
		}
		$driver = $driver->get();
		// dd($driver);
		$datatable = DataTables::of($driver)
			->addColumn('id', function ($driver) {
				return @$driver->driver->id;
			})
			->addColumn('name', function ($driver) {
				return @$driver->name;
			})
			->addColumn('email', function ($driver) {
				return @$driver->email;
			})
			->addColumn('status', function ($driver) {
				return @$driver->status_text;
			})
			->addColumn('created_at', function ($driver) {
				return @$driver->created_at;
			})
			->addColumn('action', function ($driver) {
				return '<a title="' . trans('admin_messages.driver_request') . '" href="' . route('admin.driver_request', $driver->id) . '" ><i class="material-icons"><i className="material-icons">phonelink_ring</i></i></a>&nbsp;<a title="' . trans('admin_messages.edit') . '" href="' . route('admin.edit_driver', $driver->id) . '" ><i class="material-icons">edit</i></a>&nbsp;<a title="' . trans('admin_messages.delete') . '" href="javascript:void(0)" class="confirm-delete" data-href="' . route('admin.delete_driver', $driver->id) . '"><i class="material-icons">close</i></a>';
			});
		$columns = ['id', 'name', 'email', 'status', 'created_at'];
		$base = new DataTableBase($driver, $datatable, $columns,'Drivers');
		return $base->render(null);

	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		$user = User::whereId($request->id)->first();
		if($user->driver){
	 		$is_order = OrderDelivery::where('driver_id',$user->driver->id)->first();
			if ($is_order) {
				flash_message('danger', 'Sorry, Driver having some Orders. So, Can\'t Delete this Driver.');
			} else {
				$user->delete_driver_data();
				flash_message('success', trans('admin_messages.deleted_successfully'));
			}
		}
		return redirect()->route('admin.view_driver');
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit_driver(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_name'] = trans('admin_messages.edit_driver');
			$this->view_data['form_action'] = route('admin.edit_driver', $request->id);
			$this->view_data['driver'] = User::where('id', $request->id)->firstOrFail();
			$this->view_data['vehicle_type'] = VehicleType::status()->get()->pluck('name', 'id');
			$this->view_data['driver_document'] = driver_default_documents();
			$this->view_data['driver_status'] = $this->view_data['driver']->statusTextArray;
			return view('admin/driver/add_driver', $this->view_data);
		} else {

			$all_variables = request()->all();
			if ($all_variables['date_of_birth']) {
				$all_variables['convert_dob'] = date('Y-m-d', strtotime($all_variables['date_of_birth']));
			}

			$rules = array(
				'name' => 'required',
				'convert_dob' => 'required|before:18 years ago',
				'status' => 'required',
				'country_code' => 'required',
				'vehicle_type' => 'required',
				'vehicle_name' => 'required',
				'vehicle_number' => 'required',
				'email' => 'required|email|unique:user,email,' . $request->id . ',id,type,2',
				'mobile_number' => 'required|regex:/^[0-9]+$/|min:6|unique:user,mobile_number,' . $request->id . ',id,type,2',
			);
			if ($request->password) {
				$rules['password'] = 'min:6';
			}

			foreach (driver_default_documents() as $value) {

				$rules[$value] = 'mimes:jpg,png,jpeg,gif,pdf';
				$niceNames[$value] = trans('admin_messages.' . $value);
			}
			if ($request->document) {
				foreach ($request->document as $key => $value) {
					$all_variables[$key] = $value;
				}
			}

			// Add Admin User Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.full_name'),
				'email' => trans('admin_messages.email'),
				'password' => trans('admin_messages.password'),
				'convert_dob' => trans('admin_messages.date_of_birth'),
				'mobile_number' => trans('admin_messages.mobile_number'),
				'status' => trans('admin_messages.status'),
				'country_code' => trans('admin_messages.country_code'),
			);
			$messages = array(
				'convert_dob.before' => 'Age must be 18 or older',
			);
			
			$validator = Validator::make($all_variables, $rules,$messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$driver = User::find($request->id);
				$driver->name = $request->name;
				$driver->email = $request->email;
				if ($request->password) {
					$driver->password = Hash::make($request->password);
				}

				$driver->date_of_birth = $all_variables['convert_dob'];
				$driver->country_code = $request->country_code;
				$driver->mobile_number = $request->mobile_number;
				$driver->status = $request->status;
				$driver->type = 2;
				$driver->save();

				$driver_vehicle = Driver::where('user_id', $driver->id)->first();
				$driver_vehicle->vehicle_type = $request->vehicle_type;
				$driver_vehicle->vehicle_name = $request->vehicle_name;
				$driver_vehicle->vehicle_number = $request->vehicle_number;
				$driver_vehicle->save();

				if ($request->document) {
					foreach ($request->document as $key => $value) {

						$file = $request->file('document')[$key];

						$file_path = $this->fileUpload($file, 'public/images/driver');
						$this->fileSave('driver_' . $key, $driver_vehicle->id, $file_path['file_name'], '1');
					}
				}

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.view_driver');
			}

		}
	}


		/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function oweAmount(DriverOweAmountDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.owe_amount_management');
		return $dataTable->render('admin.driver.owe_amount', $this->view_data);
	}

}