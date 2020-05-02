<?php
/**
 * PromoCodeController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Coupon
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\PromoCodeDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Validator;

class PromoCodeController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function add(Request $request) {
		if ($request->getMethod() == 'GET') {

			if ($request->id) {
				$this->view_data['form_name'] = trans('admin_messages.edit_promo');
				$this->view_data['form_action'] = route('admin.edit_promo', $request->id);
				$this->view_data['promo'] = PromoCode::findOrFail($request->id);

			} else {
				$this->view_data['form_action'] = route('admin.add_promo');
				$this->view_data['form_name'] = trans('admin_messages.add_promo');
			}
			
			return view('admin/promo/add_promo', $this->view_data);
		} else {

			$all_variables = request()->all();
			if ($all_variables['start_date']) {
				$all_variables['convert_start_date'] = date('Y-m-d', strtotime(change_date_format($all_variables['start_date'])));
			}

			if ($all_variables['end_date']) {
				$all_variables['convert_end_date'] = date('Y-m-d', strtotime(change_date_format($all_variables['end_date'])));
			}

			$rules = array(
				'code' => 'required|unique:promo_code,code|min:4',
				'promo_type' => 'required',
				'status' => 'required',
				'currency_code' => 'required',
				'convert_start_date' => 'required|date|after:' . date('Y-m-d', strtotime("-1 day")),
				'convert_end_date' => 'required|date',
			);
			if ($request->promo_type == 0) {
				$rules['price'] = 'required|numeric';
			} else if ($request->promo_type == 1) {
				$rules['percentage'] = 'required|numeric|max:100';
				// $rules['min_price'] = 'required|numeric';
				// $rules['promo_max_price'] = 'required|numeric';
			}

			
			// Add Admin User Validation Custom Names
			$niceNames = array(
				'code' => trans('admin_messages.code'),
				'price' => trans('admin_messages.price'),
				'currency_code' => trans('admin_messages.currency_code'),
				'convert_start_date' => trans('admin_messages.start_date'),
				'convert_end_date' => trans('admin_messages.end_date'),
				'percentage' => trans('admin_messages.percentage'),
				'promo_type' => trans('admin_messages.promo_type'),
				'min_price' => trans('admin_messages.min_price'),
				'promo_max_price' => trans('admin_messages.promo_max_price'),
				'status' => trans('admin_messages.status'),
			);

			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.lang_code'] = 'required|unique:promo_code,code|unique:promo_code_lang,code|min:4';
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.lang_code'] = 'Code';
               
            }	
            
			$messages = array(
				'convert_start_date.after' => trans('admin_messages.start_date_after_today'),
			);
			$validator = Validator::make($all_variables, $rules, $messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($all_variables['convert_start_date'] > $all_variables['convert_end_date']) {
					return back()->withErrors(['convert_end_date' => trans('admin_messages.end_date_error')])->withInput();
				}

				$promo = new PromoCode;
				$promo->code = $request->code;
				$promo->price = $request->price;
				$promo->currency_code = $request->currency_code;
				$promo->start_date = $all_variables['convert_start_date'];
				$promo->end_date = $all_variables['convert_end_date'];
				$promo->percentage = $request->percentage;
				$promo->promo_type = $request->promo_type;
				// $promo->min_price = $request->min_price;
				// $promo->promo_max_price = $request->promo_max_price;
				$promo->status = $request->status;
				$promo->save();
				foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $promo->getTranslationById(@$translation_data['locale'], $promo->id);
                    $translation->code = $translation_data['lang_code'];
                    

                    $translation->save();
                }

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.promo');
			}

		}
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(PromoCodeDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.promo_management');
		return $dataTable->render('admin.promo.view', $this->view_data);
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		$promo = PromoCode::find($request->id);
		if ($promo) {
			$is_order = Order::where('promo_id', $promo->id)->get()->count();
			if ($is_order > 0) {
				flash_message('danger', trans('admin_messages.promo_delete_error'));
			} else {
				$promo->forcedelete();
				flash_message('success', trans('admin_messages.delete_successfully'));
			}
		}
		return redirect()->route('admin.promo');
	}
	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_name'] = trans('admin_messages.edit_promo');
			$this->view_data['form_action'] = route('admin.edit_promo', $request->id);
			$this->view_data['promo'] = PromoCode::findOrFail($request->id);

			return view('admin/promo/edit_promo', $this->view_data);
		} else {
			$all_variables = request()->all();
			if ($all_variables['start_date']) {
				$all_variables['convert_start_date'] = date('Y-m-d', strtotime(change_date_format($all_variables['start_date'])));
			}

			if ($all_variables['end_date']) {
				$all_variables['convert_end_date'] = date('Y-m-d', strtotime(change_date_format($all_variables['end_date'])));
			}

			$rules = array(
				'code' => 'required|min:4|unique:promo_code,code,' . $request->id,
				'promo_type' => 'required',
				'status' => 'required',
				'currency_code' => 'required',
				'convert_start_date' => 'required|date|after:' . date('Y-m-d', strtotime("-1 day")),
				'convert_end_date' => 'required|date',
			);
			if ($request->promo_type == 0) {
				$rules['price'] = 'required|numeric';
			} else if ($request->promo_type == 1) {
				$rules['percentage'] = 'required|numeric|max:100';
				// $rules['min_price'] = 'required|numeric';
				// $rules['promo_max_price'] = 'required|numeric';
			}

			// Add Admin User Validation Custom Names
			$niceNames = array(
				'code' => trans('admin_messages.code'),
				'price' => trans('admin_messages.price'),
				'currency_code' => trans('admin_messages.currency_code'),
				'convert_start_date' => trans('admin_messages.start_date'),
				'convert_end_date' => trans('admin_messages.end_date'),
				'percentage' => trans('admin_messages.percentage'),
				'promo_type' => trans('admin_messages.promo_type'),
				// 'min_price' => trans('admin_messages.min_price'),
				// 'promo_max_price' => trans('admin_messages.promo_max_price'),
				'status' => trans('admin_messages.status'),
			);
			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.lang_code'] = 'required|min:4|unique:promo_code,code,' . $request->id;
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.lang_code'] = 'Code';
               
            }

			$messages = array(
				'convert_start_date.after' => trans('admin_messages.start_date_after_today'),
			);
			$validator = Validator::make($all_variables, $rules, $messages);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($all_variables['convert_start_date'] > $all_variables['convert_end_date']) {
					return back()->withErrors(['convert_end_date' => trans('admin_messages.end_date_error')])->withInput();
				}

				$promo = PromoCode::find($request->id);
				$promo->code = $request->code;
				$promo->price = 0;
				$promo->percentage = 0;
				if ($request->promo_type == 0)
					$promo->price = $request->price;
				else
					$promo->percentage = $request->percentage;

				$promo->currency_code = $request->currency_code;
				$promo->start_date = $all_variables['convert_start_date'];
				$promo->end_date = $all_variables['convert_end_date'];
				$promo->promo_type = $request->promo_type;
				// $promo->min_price = $request->min_price;
				// $promo->promo_max_price = $request->promo_max_price;
				$promo->status = $request->status;
				$promo->save();
				$removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $promo->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $promo->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->code = $translation_data['lang_code'];                    
                    $translation->save();
                }

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.promo');
			}

		}
	}

}
