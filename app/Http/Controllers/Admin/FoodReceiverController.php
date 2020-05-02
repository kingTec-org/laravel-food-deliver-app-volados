<?php
/**
 * FoodReceiverController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    FoodReceiver
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\FoodReceiverDataTable;
use App\Http\Controllers\Controller;
use App\Models\FoodReceiver;
use Illuminate\Http\Request;
use Validator;

class FoodReceiverController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function add() {
		$request = request();
		if ($request->getMethod() == 'GET') {
			if ($request->id) {
				$this->view_data['form_name'] = trans('admin_messages.edit_item_receiver');
				$this->view_data['form_action'] = route('admin.edit_food_receiver', $request->id);
				$this->view_data['food_receiver'] = FoodReceiver::findOrFail($request->id);
			} else {
				$this->view_data['form_action'] = route('admin.add_food_receiver');
				$this->view_data['form_name'] = trans('admin_messages.add_item_receiver');
			}

			return view('admin/food_receiver/food_receiver_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($request->id) {
					$food_receiver = FoodReceiver::find($request->id);
				} else {
					$food_receiver = new FoodReceiver;
				}

				$food_receiver->name = $request->name;
				$food_receiver->save();
				if ($request->id) {
					flash_message('success', trans('admin_messages.updated_successfully'));
				} else {
					flash_message('success', trans('admin_messages.added_successfully'));
				}

				return redirect()->route('admin.food_receiver');
			}

		}
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(FoodReceiverDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.item_receiver_management');
		return $dataTable->render('admin.food_receiver.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
	
			FoodReceiver::find($request->id)->delete();
			flash_message('success', trans('admin_messages.deleted_successfully'));
		return redirect()->route('admin.food_receiver');
	}

}
