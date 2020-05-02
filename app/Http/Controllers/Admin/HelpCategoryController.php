<?php

/**
 * Help Category Controller
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Help Category
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HelpCategoryDataTable;
use App\Models\HelpCategory;
use App\Models\HelpSubCategory;
use App\Models\Help;
use App\Models\HelpCategoryLang;
use App\Models\Language;
use Validator;

class HelpCategoryController extends Controller
{

    /**
     * Load Datatable for Help Category
     *
     * @param array $dataTable  Instance of HelpCategoryDataTable
     * @return datatable
     */
    public function index(HelpCategoryDataTable $dataTable)
    {
        return $dataTable->render('admin.help_category.view');
    }

    /**
     * Add a New Help Category
     *
     * @param array $request  Input values
     * @return redirect     to Help Category view
     */
    public function add(Request $request)
    {
        if ($request->getMethod() == 'GET')
        {
            $data['languages'] = Language::pluck('name', 'value');
            $this->view_data['form_name'] = trans('admin_messages.add_help_category');
            $help_category = new HelpCategory;
            $this->view_data['type_array'] = array_flip($help_category->typeArray);
            return view('admin.help_category.add',$this->view_data);
        }
        else
        {
            
            // Add Help Category Validation Rules
            $rules = array(
                    'name'    => 'required|unique:help_category',
                    'status'  => 'required',
                    'type' => 'required'
                    );

            // Add Help Category Validation Custom Names
            $niceNames = array(
                        'name'    => 'Name',
                        'status'  => 'Status',
                        'type' => 'Type'
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

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                 // Edit Help Category Validation Rules
                if($request->status == 'Active'){

                    $setStatus = 1;

                }
                else{
                    $setStatus = 0;                
                }
                $help_category = new HelpCategory;

			    $help_category->name        = $request->name;
			    $help_category->description = $request->description;
                $help_category->status      = $setStatus;
			    $help_category->type      = $request->type;

                $help_category->save();

                foreach($request->translations ?: array() as $translation_data) {  
                    if($translation_data){
                        $help_category_lang = new HelpCategoryLang;
                        $help_category_lang->name        = $translation_data['name'];
                        $help_category_lang->description = $translation_data['description'];
                        $help_category_lang->locale      = $translation_data['locale'];
                        $help_category_lang->category_id = $help_category->id;
                        $help_category_lang->save();
                    }
                }

               flash_message('success', 'Added Successfully'); // Call flash message function

                return redirect()->route('admin.help_category');
            }
        }
    }

    /**
     * Update Help Category Details
     *
     * @param array $request    Input values
     * @return redirect     to Help Category View
     */
    public function update(Request $request)
    {
        if ($request->getMethod() == 'GET')
        {
			$data['result'] = HelpCategory::find($request->id);
            $data['languages'] = Language::pluck('name', 'value');
            $data['help_category'] = HelpCategory::find($request->id);
            $data['form_action'] = route('admin.edit_help_category',$request->id);
            $data['form_name'] = trans('admin_messages.edit_help_category');
            $data['status_list'] = array_flip($data['help_category']->statusArray);
            $data['type_array'] = array_flip($data['help_category']->typeArray);
            return view('admin.help_category.edit',$data);
        }
        else
        {
            // Edit Help Category Validation Rules
            if($request->status == 'Active'){

                $setStatus = 1;

            }
            else{
                $setStatus = 0;                
            }
            $rules = array(
                    'name'    => 'required|unique:help_category,name,'.$request->id,
                    'status'  => 'required',
                    
                    );

            // Edit Help Category Validation Custom Fields Name
            $niceNames = array(
                        'name'    => 'Name',
                        'status'  => 'Status',
                        
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

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $help_category = HelpCategory::find($request->id);

			    $help_category->name        = $request->name;
			    $help_category->description = $request->description;
                $help_category->status      = $setStatus;
			    $help_category->type      = $request->type;

                $help_category->save();
                $data['locale'][0] = 'en';
                  foreach($request->translations ?: array() as $translation_data) {  
                    if($translation_data){
                         $get_val = HelpCategoryLang::where('category_id',$help_category->id)->where('locale',$translation_data['locale'])->first();
                            if($get_val)
                                $help_category_lang = $get_val;
                            else
                                $help_category_lang = new HelpCategoryLang;
                        $help_category_lang->name        = $translation_data['name'];
                        $help_category_lang->description = $translation_data['description'];
                        $help_category_lang->locale      = $translation_data['locale'];
                        $help_category_lang->category_id     = $help_category->id;
                        $help_category_lang->save();
                        $data['locale'][] = $translation_data['locale'];
                    }
                }
                if(@$data['locale'])
                HelpCategoryLang::where('category_id',$help_category->id)->whereNotIn('locale',$data['locale'])->delete();
                flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect()->route('admin.help_category');
            }
        }
    }

    /**
     * Delete Help Category
     *
     * @param array $request    Input values
     * @return redirect     to Help Category View
     */
    public function delete(Request $request)
    {
        $count = Help::where('category_id', $request->id)->count();
        $subcategory_count = HelpSubCategory::where('category_id', $request->id)->count();

        if($count > 0)
            flash_message('danger', 'Help have this Help Category. So, Delete that Help or Change that Help Help Category.'); // Call flash message function
        elseif($subcategory_count > 0)
            flash_message('danger', 'Help Subcategory have this Help Category. So, Delete that Help Subcategory or Change that Help Subcategory.'); // Call flash message function
        else {
            HelpCategory::find($request->id)->delete();
            flash_message('success', 'Deleted Successfully'); // Call flash message function
        }
        return redirect()->route('admin.help_category');
    }
}
