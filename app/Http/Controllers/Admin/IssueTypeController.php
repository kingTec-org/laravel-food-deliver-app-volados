<?php
/**
 * IssueTypeController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    IssueType
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\IssueTypeDataTable;
use App\Http\Controllers\Controller;
use App\Models\IssueType;
use App\Models\ReviewIssue;
use Illuminate\Http\Request;
use Validator;

class IssueTypeController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public $typeArray = [
		0 => 'User item',
		1 => 'User driver',
		2 => 'Store delivery',
		3 => 'Driver delivery',
		4 => 'Driver store',
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
				$this->view_data['form_name'] = trans('admin_messages.edit_issue_type');
				$this->view_data['form_action'] = route('admin.edit_issue_type', $request->id);
				$this->view_data['issue_type'] = IssueType::findOrFail($request->id);
			} else {
				$this->view_data['form_action'] = route('admin.add_issue_type');
				$this->view_data['form_name'] = trans('admin_messages.add_issue_type');
			}

			$this->view_data['type'] = $this->typeArray;
			return view('admin/issue_type/issue_type_form', $this->view_data);
		} else {
			$rules = array(
				'issue' => 'required',
				'type' => 'required',
				'status' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'issue' => trans('admin_messages.issue'),
				'type' => 'Type',
				'status' => trans('admin_messages.status'),
			);
			
			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Issue';
               
            }
            // dd($niceNames);
			$validator = Validator::make($request->all(), $rules);

			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($request->id) {
					$issue_type = IssueType::find($request->id);
				} else {
					$issue_type = new IssueType;
				}

				$issue_type->name = $request->issue;
				$issue_type->type_id = $request->type;
				$issue_type->status = $request->status;
				$issue_type->save();

				foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $issue_type->getTranslationById(@$translation_data['locale'], $issue_type->id);
                    $translation->name = $translation_data['name'];                    
                    $translation->save();
                }
				if ($request->id) {
					flash_message('success', trans('admin_messages.updated_successfully'));
				} else {
					flash_message('success', trans('admin_messages.added_successfully'));
				}

				return redirect()->route('admin.issue_type');
			}

		}
	}

	public function update() {
		$request = request();
		if ($request->getMethod() == 'GET') {
			if ($request->id) {
				$this->view_data['form_name'] = trans('admin_messages.edit_issue_type');
				$this->view_data['form_action'] = route('admin.edit_issue_type', $request->id);
				$this->view_data['issue_type'] = IssueType::findOrFail($request->id);
			}

			$this->view_data['type'] = $this->typeArray;
			return view('admin/issue_type/issue_type_form_edit', $this->view_data);
		} else {
			$rules = array(
				'issue' => 'required',
				'type' => 'required',
				'status' => 'required',
			);

			// Validation Custom Names
			$niceNames = array(
				'issue' => trans('admin_messages.issue'),
				'type' => 'Type',
				'status' => trans('admin_messages.status'),
			);

			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Issue';
               
            }
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($request->id) {
					$issue_type = IssueType::find($request->id);
				} else {
					$issue_type = new IssueType;
				}

				$issue_type->name = $request->issue;
				$issue_type->type_id = $request->type;
				$issue_type->status = $request->status;
				$issue_type->save();

				$removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $issue_type->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $issue_type->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];                    
                    $translation->save();
                }
				if ($request->id) {
					flash_message('success', trans('admin_messages.updated_successfully'));
				} else {
					flash_message('success', trans('admin_messages.added_successfully'));
				}

				return redirect()->route('admin.issue_type');
			}

		}
	}
	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(IssueTypeDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.issue_type_management');
		return $dataTable->render('admin.issue_type.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {

		$issue = ReviewIssue::where('issue_id',$request->id)->first();
		if($issue)
			flash_message('danger',trans('admin_messages.issue_delete'));
		else{	
			IssueType::find($request->id)->delete();
			flash_message('success', trans('admin_messages.deleted_successfully'));
			}
		return redirect()->route('admin.issue_type');
	}

}
