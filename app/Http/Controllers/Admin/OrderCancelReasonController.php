<?php
/**
 * OrderCancelReasonController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    OrderCancelReason
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\OrderCancelReasonDataTable;
use App\Http\Controllers\Controller;
use App\Models\OrderCancelReason;
use Illuminate\Http\Request;
use Validator;

class OrderCancelReasonController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public $typeArray = [
		0 => 'user',
		1 => 'store',
		2 => 'driver',
		3 => 'admin',
	];
	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function add() {
		$request = request();
		if ($request->getMethod() == 'GET') {
			if ($request->id) {
				$this->view_data['form_name'] = trans('admin_messages.edit_cancel_reason');
				$this->view_data['form_action'] = route('admin.edit_cancel_reason', $request->id);
				$this->view_data['order_cancel_reason'] = OrderCancelReason::findOrFail($request->id);
			} else {
				$this->view_data['form_action'] = route('admin.add_cancel_reason');
				$this->view_data['form_name'] = trans('admin_messages.add_cancel_reason');
			}

			$this->view_data['type'] = $this->typeArray;
			return view('admin/order_cancel_reason/add_cancel_reason', $this->view_data);
		} else {
			$rules = array(
				'reason' => 'required',
				'type' => 'required',
				'status' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'reason' => trans('admin_messages.reason'),
				'type' => trans('admin_messages.user_type'),
				'status' => trans('admin_messages.status'),
			);

			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.reason'] = 'required';
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.reason'] = 'Reason';
               
            }

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($request->id) {
					$order_cancel_reason = OrderCancelReason::find($request->id);
				} else {
					$order_cancel_reason = new OrderCancelReason;
				}

				$order_cancel_reason->name = $request->reason;
				$order_cancel_reason->type = $request->type;
				$order_cancel_reason->status = $request->status;
				$order_cancel_reason->save();

				foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $order_cancel_reason->getTranslationById(@$translation_data['locale'], $order_cancel_reason->id);
                    $translation->name = $translation_data['reason'];
                    

                    $translation->save();
                }


				if ($request->id) {
					flash_message('success', trans('admin_messages.updated_successfully'));
				} else {
					flash_message('success', trans('admin_messages.added_successfully'));
				}

				return redirect()->route('admin.order_cancel_reason');
			}

		}
	}
	public function edit() {
		$request = request();
		if ($request->getMethod() == 'GET') {
			if ($request->id) {
				$this->view_data['form_name'] = trans('admin_messages.edit_cancel_reason');
				$this->view_data['form_action'] = route('admin.edit_cancel_reason', $request->id);
				$this->view_data['order_cancel_reason'] = OrderCancelReason::findOrFail($request->id);
			} else {
				$this->view_data['form_action'] = route('admin.add_cancel_reason');
				$this->view_data['form_name'] = trans('admin_messages.add_cancel_reason');
			}

			$this->view_data['type'] = $this->typeArray;
			return view('admin/order_cancel_reason/edit_cancel_reason', $this->view_data);
		} else {
			$rules = array(
				'reason' => 'required',
				'type' => 'required',
				'status' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'reason' => trans('admin_messages.reason'),
				'type' => trans('admin_messages.user_type'),
				'status' => trans('admin_messages.status'),
			);

			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.reason'] = 'required';
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.reason'] = 'Reason';
               
            }

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($request->id) {
					$order_cancel_reason = OrderCancelReason::find($request->id);
				} else {
					$order_cancel_reason = new OrderCancelReason;
				}

				$order_cancel_reason->name = $request->reason;
				$order_cancel_reason->type = $request->type;
				$order_cancel_reason->status = $request->status;
				$order_cancel_reason->save();

				$removed_translations = explode(',', $request->removed_translations);

                foreach(array_values($removed_translations) as $id) {
                    $order_cancel_reason->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $order_cancel_reason->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['reason'];                    
                    $translation->save();
                }

				if ($request->id) {
					flash_message('success', trans('admin_messages.updated_successfully'));
				} else {
					flash_message('success', trans('admin_messages.added_successfully'));
				}

				return redirect()->route('admin.order_cancel_reason');
			}

		}
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(OrderCancelReasonDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.order_cancel_reason_management');
		return $dataTable->render('admin.order_cancel_reason.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		OrderCancelReason::find($request->id)->forcedelete();
		flash_message('success', trans('admin_messages.deleted_successfully'));
		return redirect()->route('admin.order_cancel_reason');
	}

}
