<?php
/**
 * CountryController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Country
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\CountryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Store;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Validator;

class CountryController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function add(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_action'] = route('admin.add_country');
			$this->view_data['form_name'] = trans('admin_messages.add_country');
			return view('admin/country/country_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'code' => 'required|unique:country,code',
				'phone_code' => 'required',
				'status' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'code' => trans('admin_messages.code'),
				'iso3' => trans('admin_messages.iso3'),
				'phone_code' => trans('admin_messages.phone_code'),
				'status' => trans('admin_messages.status'),
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$country = new Country;
				$country->name = $request->name;
				$country->code = $request->code;
				$country->iso3 = $request->iso3;
				$country->phone_code = $request->phone_code;
				$country->status = $request->status;
				$country->save();

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.country');
			}

		}
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(CountryDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.country_management');
		return $dataTable->render('admin.country.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		$country = Country::find($request->id);

		$data = $this->canDestroy($country->code, $country->phone_code);
		if ($data['status'] != 1) {
			flash_message('danger', $data['message']);
		} else {
			$country->forcedelete();
			flash_message('success', trans('admin_messages.delete_successfully'));
		}
		return redirect()->route('admin.country');
	}

	public function canDestroy($code, $phone_code) {
		$find_user = UserAddress::where('country_code', $code)->first();
		$find_store = Store::where('address_country_code', $code)->first();
		$find_phone_store = Store::where('phone_country_code', $phone_code)->first();
		$find_phone_user = User::where('country_code', $phone_code)->first();

		$return = ['status' => '1', 'message' => ''];
		if ($find_user) {
			$return = ['status' => 0, 'message' => 'Sorry, User have this Country. So, Delete that User or Change that User Country.'];
		} else if ($find_store) {
			$return = ['status' => 0, 'message' => 'Sorry, Store have this Country. So, Delete that Store or Change that Store Country.'];
		} else if ($find_phone_store) {
			$return = ['status' => 0, 'message' => 'Sorry, Store have this country phone code. So, Delete that Store or Change that Store country phone code.'];
		} else if ($find_phone_user) {
			$return = ['status' => 0, 'message' => 'Sorry, User have this country phone code. So, Delete that User or Change that User country phone code.'];
		}

		return $return;
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_name'] = trans('admin_messages.edit_country');
			$this->view_data['form_action'] = route('admin.edit_country', $request->id);
			$this->view_data['country_select'] = Country::find($request->id);

			return view('admin/country/country_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'code' => 'required|unique:country,code,' . $request->id,
				'phone_code' => 'required',
				'status' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'code' => trans('admin_messages.code'),
				'iso3' => trans('admin_messages.iso3'),
				'phone_code' => trans('admin_messages.phone_code'),
				'status' => trans('admin_messages.status'),
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$country = Country::find($request->id);
				$country->name = $request->name;
				$country->code = $request->code;
				$country->iso3 = $request->iso3;
				$country->phone_code = $request->phone_code;
				$country->status = $request->status;
				$country->save();

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.country');
			}

		}
	}

}
