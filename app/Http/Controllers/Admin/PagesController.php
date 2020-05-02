<?php
/**
 * PagesController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Pages
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\PagesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Http\Request;
use Validator;

class PagesController extends Controller {

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
			$this->view_data['form_action'] = route('admin.add_static_page');
			$this->view_data['form_name'] = trans('admin_messages.add_static_page');
			$page = new Pages;
			$this->view_data['user_pages'] = array_flip($page->userArray);
			return view('admin/static_page/static_page_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'url' => 'required|unique:static_page,url',
				'footer' => 'required',
				'content' => 'required',
				'status' => 'required',
				'user_page' => 'required',
			);

// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'url' => trans('admin_messages.url'),
				'footer' => trans('admin_messages.footer'),
				'content' => trans('admin_messages.content'),
				'status' => trans('admin_messages.status'),
				'user_page' => trans('admin_messages.user_page'),
			);

			$except = array('content');
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
                $rules['translations.'.$k.'.content'] = 'required';

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
                $niceNames['translations.'.$k.'.content'] = 'Content';
                $except[] = 'translations.'.$k.'.content';
            }

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$static_page = new Pages;
				$static_page->name = $request->name;
				$static_page->url = $request->url;
				$static_page->footer = $request->footer;
				$static_page->content = $request->content;
				$static_page->status = $request->status;
				$static_page->user_page = $request->user_page;
				$static_page->save();

				 foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $static_page->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];
                    $translation->content = $translation_data['content'];

                    $translation->save();
                }

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.static_page');
			}

		}
	}

/**
 * Manage
 *
 * @return \Illuminate\Http\Response
 */
	public function view(PagesDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.static_page_management');
		return $dataTable->render('admin.static_page.view', $this->view_data);
	}

/**
 * Manage
 *
 * @return \Illuminate\Http\Response
 */
	public function delete(Request $request) {
		Pages::find($request->id)->delete();
		flash_message('success', trans('admin_messages.delete_successfully'));
		return redirect()->route('admin.static_page');
	}
/**
 * Manage
 *
 * @return \Illuminate\Http\Response
 */
	public function edit(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_name'] = trans('admin_messages.edit_static_page');
			$this->view_data['form_action'] = route('admin.edit_static_page', $request->id);
			$this->view_data['static_page'] = Pages::findOrFail($request->id);
			$this->view_data['user_pages'] = array_flip($this->view_data['static_page']->userArray);
			return view('admin/static_page/static_page_edit', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'url' => 'required|unique:static_page,url,' . $request->id,
				'footer' => 'required',
				'content' => 'required',
				'status' => 'required',
				'user_page' => 'required',
			);

// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'url' => trans('admin_messages.url'),
				'footer' => trans('admin_messages.footer'),
				'content' => trans('admin_messages.content'),
				'status' => trans('admin_messages.status'),
				'user_page' => trans('admin_messages.user_page'),
			);

			            $except = array('content');
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
                $rules['translations.'.$k.'.content'] = 'required';

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
                $niceNames['translations.'.$k.'.content'] = 'Content';
                $except[] = 'translations.'.$k.'.content';
            }
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$static_page = Pages::find($request->id);
				$static_page->name = $request->name;
				$static_page->url = $request->url;
				$static_page->footer = $request->footer;
				$static_page->content = $request->content;
				$static_page->status = $request->status;
				$static_page->user_page = $request->user_page;
				$static_page->save();

				 $removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $static_page->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $static_page->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];
                    $translation->content = $translation_data['content'];

                    $translation->save();
                }
                
				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.static_page');
			}

		}
	}

}
