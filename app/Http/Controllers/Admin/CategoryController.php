<?php
/**
 * CategoryController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Category
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\StoreCategory;
use Illuminate\Http\Request;
use Validator;
use App\Traits\FileProcessing;
use Storage;
class CategoryController extends Controller {
	use FileProcessing;
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
			$this->view_data['form_action'] = route('admin.add_category');
			$this->view_data['form_name'] = trans('admin_messages.add_category');
			return view('admin/category/category_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',				
				'status' => 'required',
				'description' => 'required',
				'image' => 'required|mimes:jpg,png,jpeg,gif',
				
			);
			if($request->is_dietary=='yes')
				$rules['dietary_icon'] = 'mimes:jpg,png,jpeg,gif';
			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'description' => trans('admin_messages.description'),
				'status' => trans('admin_messages.status'),
			);
				foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required | nullable ';
                $rules['translations.'.$k.'.name'] = 'required';
                $rules['translations.'.$k.'.description'] = 'required';

                
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
                $niceNames['translations.'.$k.'.description'] = 'Description';

                
               
            }
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$category = new Category;
				$category->name = $request->name;
				$category->description = $request->description;
				$category->status = $request->status;
				$category->is_dietary = $request->is_dietary=='yes'?1:null;
				$category->save();

				foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $category->getTranslationById(@$translation_data['locale'], $category->id);
                    $translation->name = $translation_data['name'];                    
                    $translation->description = $translation_data['description'];
                    $translation->save();
                }

				if ($request->file('image')) {
						$file = $request->file('image');

						$file_path = $this->fileUpload($file, 'public/images/category_image');

						$this->fileSave('category_image', $category->id, $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						$this->fileCrop($orginal_path, get_image_size('category_image_size')['width'], get_image_size('category_image_size')['height']);
					}
				if ($request->file('dietary_icon') && $request->is_dietary=='yes') {
						$file = $request->file('dietary_icon');

						$file_path = $this->fileUpload($file, 'public/images/category_image');

						$this->fileSave('dietary_icon', $category->id, $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						$this->fileCrop($orginal_path, get_image_size('dietary_icon_size')['width'], get_image_size('dietary_icon_size')['height']);
					}


				flash_message('success', trans('admin_messages.added_successfully'));
				return redirect()->route('admin.category');
			}

		}
	}



	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(CategoryDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.category_management');
		return $dataTable->render('admin.category.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete(Request $request) {
		$storer_category = StoreCategory::where('category_id', $request->id)->get()->count();
		if($storer_category>0)
		{
			flash_message('danger', 'Sorry, Some store use this category so can\'t delete this');
		}
		else{
			StoreCategory::where('category_id', $request->id)->forcedelete();
			Category::find($request->id)->forcedelete();
			flash_message('success', trans('admin_messages.deleted_successfully'));
		}
		return redirect()->route('admin.category');
	}


	public function change_status() {
		$column = request()->column;
		$category = Category::find(request()->id);
		if($category->$column==1)
			$category->$column = 0;
		else 
			$category->$column = 1;
		$category->save(); 
		flash_message('success', trans('admin_messages.updated_successfully'));
		return redirect()->route('admin.category');
	}
	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['form_name'] = trans('admin_messages.edit_category');
			$this->view_data['form_action'] = route('admin.edit_category', $request->id);
			$this->view_data['category'] = Category::findOrFail($request->id);

			return view('admin/category/category_form_edit', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'description'=>'required',
				'status' => 'required',
				'image' => 'mimes:jpg,png,jpeg,gif',
			);
			if($request->is_dietary=='yes')
				$rules['dietary_icon'] = 'mimes:jpg,png,jpeg,gif';

			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'description' => trans('admin_messages.description'),
				'status' => trans('admin_messages.status'),
			);

			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
                $rules['translations.'.$k.'.description'] = 'required';
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
                $niceNames['translations.'.$k.'.description'] = 'Name';
               
            }

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				$category = Category::find($request->id);
				$category->name = $request->name;
				$category->description = $request->description;
				$category->status = $request->status;
				$category->is_dietary = $request->is_dietary=='yes'?1:null;
				$category->save();
				
				$removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $category->deleteTranslationById($id);
                }	

                 foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $category->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];$translation->description = $translation_data['description'];                    
                    $translation->save();
                }
					if ($request->file('image')) {
						$file = $request->file('image');

						$file_path = $this->fileUpload($file, 'public/images/category_image');

						$this->fileSave('category_image', $category->id, $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						$this->fileCrop($orginal_path, get_image_size('category_image_size')['width'], get_image_size('category_image_size')['height']);
					}
					if ($request->file('dietary_icon') && $request->is_dietary=='yes') {
						$file = $request->file('dietary_icon');

						$file_path = $this->fileUpload($file, 'public/images/category_image');

						$this->fileSave('dietary_icon', $category->id, $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						$this->fileCrop($orginal_path, get_image_size('dietary_icon_size')['width'], get_image_size('dietary_icon_size')['height']);
					}

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.category');
			}

		}
	}

}
