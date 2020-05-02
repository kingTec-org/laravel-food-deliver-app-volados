<?php
/**
 * CurrencyController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\CurrencyDataTable;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Payout;
use App\Models\PromoCode;
use App\Models\Store;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Validator;

class CurrencyController extends Controller {

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
			$this->view_data['form_action'] = route('admin.add_currency');
			$this->view_data['form_name'] = trans('admin_messages.add_currency');
			return view('admin/currency/currency_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'code' => 'required|unique:currency,code',
				'symbol' => 'required',
				'rate' => 'required|numeric',
				'status' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'code' => trans('admin_messages.code'),
				'symbol' => trans('admin_messages.symbol'),
				'rate' => trans('admin_messages.rate'),
				'status' => trans('admin_messages.status'),
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$currency = new Currency;
				$currency->name = $request->name;
				$currency->code = $request->code;
				$currency->symbol = $request->symbol;
				$currency->rate = $request->rate;
				$currency->status = $request->status;
				$currency->save();

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.currency');
			}

		}
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(CurrencyDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.currency_management');
		return $dataTable->render('admin.currency.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		$currency = Currency::find($request->id);

		$data = $this->canDestroy($currency->code);
		if ($data['status'] != 1) {
			flash_message('danger', $data['message']);
		} else {
			$currency->forcedelete();
			flash_message('success', trans('admin_messages.delete_successfully'));
		}
		return redirect()->route('admin.currency');
	}

	public function canDestroy($code) {
		$is_default_currency = SiteSettings::where('name', 'default_currency')->first()->value;
		$paypal_currency = SiteSettings::where('name', 'paypal_currency')->first()->value;

		$store_currency = Store::where('currency_code', $code)->count();
		$payout_currency = Payout::where('currency_code', $code)->count();
		$order_currency = Order::where('currency_code', $code)->count();
		$promo_currency = PromoCode::where('currency_code', $code)->count();
		$active_currency_count = Currency::where('status', 1)->count();

		$return = ['status' => '1', 'message' => ''];
		if ($active_currency_count < 1) {
			$return = ['status' => 0, 'message' => 'Sorry, Minimum one Active currency is required.'];
		} else if ($is_default_currency == $code) {
			$return = ['status' => 0, 'message' => 'Sorry, This currency is default currency. So, change the default currency.'];
		} else if ($paypal_currency == $code) {
			$return = ['status' => 0, 'message' => 'Sorry, This currency is Paypal currency. So, change the Paypal currency.'];
		} else if ($store_currency > 0) {
			$return = ['status' => 0, 'message' => 'Sorry, Store have this currency. So, Delete that Store or Change that Store currency.'];
		} else if ($order_currency > 0) {
			$return = ['status' => 0, 'message' => 'Sorry, Order have this currency. So, Delete that Order or Change that Order currency.'];
		} else if ($payout_currency) {
			$return = ['status' => 0, 'message' => 'Sorry, Payout have this currency. So, Delete that Payout  or Change that Payout currency.'];
		} else if ($promo_currency) {
			$return = ['status' => 0, 'message' => 'Sorry, promo code have this currency. So, Delete that promo code or change that promo code currency.'];
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
			$this->view_data['form_name'] = trans('admin_messages.edit_currency');
			$this->view_data['form_action'] = route('admin.edit_currency', $request->id);
			$this->view_data['currency_select'] = Currency::findOrFail($request->id);

			return view('admin/currency/currency_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'code' => 'required|unique:currency,code,' . $request->id,
				'symbol' => 'required',
				'rate' => 'required|numeric',
				'status' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'code' => trans('admin_messages.code'),
				'symbol' => trans('admin_messages.symbol'),
				'rate' => trans('admin_messages.rate'),
				'status' => trans('admin_messages.status'),
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$currency = Currency::find($request->id);
				$currency->name = $request->name;
				$currency->code = $request->code;
				$currency->symbol = $request->symbol;
				$currency->rate = $request->rate;
				$currency->status = $request->status;
				$currency->save();

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.currency');
			}

		}
	}

}
