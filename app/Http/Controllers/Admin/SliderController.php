<?php
/**
 * HomeSliderController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    HomeSliderController
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\HomeSliderDataTable;
use Illuminate\Http\Request;

use App\Models\HomeSlider;
use App\Traits\FileProcessing;
use DataTables;
use Validator;
use Storage;

class SliderController extends Controller {

	use FileProcessing;
	public function __construct() {
		parent::__construct();
	}

		/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function home_slider(Request $request) {
		if ($request->getMethod() == 'GET') {
			
			if ($request->id) {
				$this->view_data['form_name'] = trans('admin_messages.edit_home_slider');
				$this->view_data['form_action'] = route('admin.edit_home_slider', $request->id);
				$this->view_data['slider'] = HomeSlider::findOrFail($request->id);
				$this->view_data['typeArray'] = array_flip($this->view_data['slider']->typeArray);
				$this->view_data['form_type'] = 'edit';
			} else {
				$this->view_data['form_action'] = route('admin.add_home_slider');
				$this->view_data['form_name'] = trans('admin_messages.add_home_slider');
				$this->view_data['sliders'] = new HomeSlider;
				$this->view_data['typeArray'] = array_flip($this->view_data['sliders']->typeArray);
				$this->view_data['form_type'] = 'add';
			}
			return view('admin/home_slider/home_slider_form', $this->view_data);
		} else {

			$rules = array(
				'title' => 'required',
				'description' => 'required',
				'status' => 'required',
				'type' => 'required',
			);
			if ($request->id)
				$rules['image'] = 'mimes:jpg,png,jpeg,gif';
			else
				$rules['image'] = 'required|mimes:jpg,png,jpeg,gif';
			// Validation Custom Names
			$niceNames = array(
				'title' => trans('admin_messages.title'),
				'description' => trans('admin_messages.description'),
				'status' => trans('admin_messages.status'),
				'type' => trans('admin_messages.type'),
			);

			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.title'] = 'required';
                $rules['translations.'.$k.'.description'] = 'required';

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.title'] = 'Title';
                $niceNames['translations.'.$k.'.description'] = 'Description';
                $except[] = 'translations.'.$k.'.description';
            }

			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($request->id) {
					$active_records = HomeSlider::where('id','!=',$request->id)->where('status',1)->get()->count();
					if($active_records<1 &&  $request->status==0)
						return back()->withErrors(['status'=>'At least one slider is active status'])->withInput(); // Form calling with Errors and Input values
					$slider = HomeSlider::find($request->id);
				} else {
					$slider = new HomeSlider;
				}
				$slider->title = $request->title;
				$slider->description = $request->description;
				$slider->status = $request->status;
				$slider->type = $request->type;
				$slider->save();

				if ($request->file('image')) {
						$file = $request->file('image');
						$folder = $request->type==1?'store_home_slider':'eater_home_slider';
						$file_path = $this->fileUpload($file, 'public/images/'.$folder);

						$this->fileSave($folder, $slider->id, $file_path['file_name'], '1');
					}

					$removed_translations = explode(',', $request->removed_translations);
                    foreach(array_values($removed_translations) as $id) {
                    $slider->deleteTranslationById($id);
                    }

					foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $slider->getTranslationById(@$translation_data['locale'], $slider->id);
                    $translation->title = $translation_data['title'];
                    $translation->description = $translation_data['description'];

                    $translation->save();
                }

				if ($request->id) {
					flash_message('success', trans('admin_messages.updated_successfully'));
				} else {
					flash_message('success', trans('admin_messages.added_successfully'));
				}

				return redirect()->route('admin.view_home_slider');
			}

		}
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view_home_slider(HomeSliderDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.home_slider');
		return $dataTable->render('admin.home_slider.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete_home_slider(Request $request) {

		$total = HomeSlider::where('id','!=',$request->id)->where('status','1')->get()->count();
		if($total<1)
			flash_message('danger',trans('admin_messages.atleast_one_slider'));
		else{	
			HomeSlider::find($request->id)->delete();
			flash_message('success', trans('admin_messages.deleted_successfully'));
			}
		return redirect()->route('admin.view_home_slider');
	}



}
