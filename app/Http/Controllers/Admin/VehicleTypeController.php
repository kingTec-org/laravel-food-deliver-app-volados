<?php
/**
 * VehicleTypeController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    VehicleType
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\DataTables\VehicleTypeDataTable;
use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use App\Models\Driver;
use App\Traits\FileProcessing;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;

class VehicleTypeController extends Controller {

	use FileProcessing;
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

				$this->view_data['form_action'] = route('admin.add_vehicle_type');
				$this->view_data['form_name'] = trans('admin_messages.add_vehicle_type');
				

			return view('admin/vehicle_type/vehicle_type_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'status' => 'required',
			);
			if($request->id)
				$rules['vehicle_image'] = 'image|mimes:jpg,png,jpeg,gif';
			else
				$rules['vehicle_image'] = 'image|mimes:jpg,png,jpeg,gif';
			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'status' => trans('admin_messages.status'),
				'vehicle_image' => trans('admin_messages.vehicle_image'),
			);

			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required | nullable';
                $rules['translations.'.$k.'.name'] = 'required';
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
               
            }
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($request->id) {
					$vehicle_type = VehicleType::find($request->id);
				} else {
					$vehicle_type = new VehicleType;
				}

				$vehicle_type->name = $request->name;
				$vehicle_type->status = $request->status;
				$vehicle_type->save();

				foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $vehicle_type->getTranslationById(@$translation_data['locale'], $vehicle_type->id);
                    $translation->name = $translation_data['name'];
                    

                    $translation->save();
                }


				if ($request->file('vehicle_image')) {
						$file = $request->file('vehicle_image');

						$file_path = $this->fileUpload($file, 'public/images/vehicle_image');
						$this->fileSave('vehicle_image', $vehicle_type->id, $file_path['file_name'], '1');
					}


				if ($request->id) {
					flash_message('success', trans('admin_messages.updated_successfully'));
				} else {
					flash_message('success', trans('admin_messages.added_successfully'));
				}

				return redirect()->route('admin.vehicle_type');
			}

		}
	}

	public function update(){

		$request = request();
		if ($request->getMethod() == 'GET') {

			
				$this->view_data['form_name'] = trans('admin_messages.edit_vehicle_type');
				$this->view_data['form_action'] = route('admin.edit_vehicle_type', $request->id);
				$this->view_data['vehicle_type'] = VehicleType::findOrFail($request->id);

			

			return view('admin/vehicle_type/vehicle_type_edit_form', $this->view_data);
		} else {
			$rules = array(
				'name' => 'required',
				'status' => 'required',
			);
			if($request->id)
				$rules['vehicle_image'] = 'image|mimes:jpg,png,jpeg,gif';
			else
				$rules['vehicle_image'] = 'required|image|mimes:jpg,png,jpeg,gif';
			// Validation Custom Names
			$niceNames = array(
				'name' => trans('admin_messages.name'),
				'status' => trans('admin_messages.status'),
				'vehicle_image' => trans('admin_messages.vehicle_image'),
			);

			foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
               

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
               
            }
			$validator = Validator::make($request->all(), $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				if ($request->id) {
					$vehicle_type = VehicleType::find($request->id);
				} 

				$vehicle_type->name = $request->name;
				$vehicle_type->status = $request->status;
				$vehicle_type->save();

				$removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $vehicle_type->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $vehicle_type->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];                    
                    $translation->save();
                }


				if ($request->file('vehicle_image')) {
						$file = $request->file('vehicle_image');

						$file_path = $this->fileUpload($file, 'public/images/vehicle_image');
						$this->fileSave('vehicle_image', $vehicle_type->id, $file_path['file_name'], '1');
					}


				if ($request->id) {
					flash_message('success', trans('admin_messages.updated_successfully'));
				} 

				return redirect()->route('admin.vehicle_type');
			}

		}
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function view(VehicleTypeDataTable $dataTable) {
		$this->view_data['form_name'] = trans('admin_messages.vehicle_type_management');
		return $dataTable->render('admin.vehicle_type.view', $this->view_data);
	}

	/**
	 * Manage
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function delete() {
		$vehicle = Driver::where('vehicle_type',request()->id)->first();
		if($vehicle){
			flash_message('danger', 'Sorry, some drivers using this vehicle type. So can\'t delete this vehicle type');
		}
		else{
			VehicleType::find(request()->id)->forcedelete();
			
			flash_message('success', trans('admin_messages.deleted_successfully'));
		}
		return redirect()->route('admin.vehicle_type');
	}

}
