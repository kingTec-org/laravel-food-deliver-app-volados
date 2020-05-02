<?php
/**
 * MetasController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Metas
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\MetasDataTable;
use App\Http\Controllers\Controller;
use App\Models\Metas;
use Illuminate\Http\Request;
use Validator;

class MetasController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(MetasDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.metas');
		return $dataTable->render('admin.metas.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_name'] = trans('admin_messages.edit_metas');
			$this->view_data['form_action'] = route('admin.meta_edit', $request->id);
			$this->view_data['meta'] = Metas::findOrFail($request->id);

			return view('admin/metas/metas_form', $this->view_data);
		} else {
			$rules = array(
				'title' => 'required',
				'description' => 'required',
				'keywords' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'title' => trans('admin_messages.title'),
				'description' => trans('admin_messages.description'),
				'keywords' => trans('admin_messages.keywords'),
			);

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$category = Metas::find($request->id);
				$category->title = $request->title;
				$category->description = $request->description;
				$category->keywords = $request->keywords;
				$category->save();

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.metas');
			}

		}
	}

}
