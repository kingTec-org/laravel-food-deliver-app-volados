<?php

/**
 * Help Controller
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    Help
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\DataTables\HelpDataTable;
use App\Models\Help;
use App\Models\HelpCategory;
use App\Models\HelpSubCategory;
use App\Models\HelpTranslations;
use App\Models\Language;
use Log;
use Validator;

class HelpController extends Controller
{

    /**
     * Load Datatable for Help
     *
     * @param array $dataTable  Instance of HelpDataTable
     * @return datatable
     */
    public function index(HelpDataTable $dataTable)
    {
        return $dataTable->render('admin.help.view');
    }

    /**
     * Add a New Help
     *
     * @param array $request  Input values
     * @return redirect     to Help view
     */
    public function add(Request $request)
    {
        
        if ($request->getMethod() == 'GET')
        {
            $this->view_data['category'] = HelpCategory::status()->get();
            $this->view_data['subcategory'] = HelpSubCategory::status()->get();
            $this->view_data['form_action'] = route('admin.add_help');
            $this->view_data['form_name'] = trans('admin_messages.add_help');
            $help = new Help;
            $this->view_data['status_list'] = array_flip($help->statusArray);
            

            return view('admin.help.add', $this->view_data);
        }
        else
        {
            
            // Add Help Validation Rules
            $rules = array(
                    'question'    => 'required',
                    'category_id' => 'required',
                    'answer'      => 'required',
                    'status'      => 'required',
                    'subcategory_id'=>'required',
                    );

            // Add Help Validation Custom Names
            $niceNames = array(
                        'question'    => 'Question',
                        'category_id' => 'Category',
                        'answer'      => 'Answer',
                        'status'      => 'Status',
                        'subcategory_id'=>'SubCategory'
                        );
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
                $rules['translations.'.$k.'.description'] = 'required';

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
                $niceNames['translations.'.$k.'.description'] = 'Description';
                $except[] = 'translations.'.$k.'.description';
            }
            $validator = Validator::make($request->all(), $rules);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $help = new Help;

                $help->category_id    = $request->category_id;
                $help->subcategory_id = $request->subcategory_id;
                $help->question       = $request->question;
                $help->answer         = $request->answer;
                $help->suggested      = $request->suggested;
                $help->status         = $request->status;

                $help->save();

                 foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $help->getTranslationById(@$translation_data['locale'], $help->id);
                    $translation->name = $translation_data['name'];
                    $translation->description = $translation_data['description'];

                    $translation->save();
                }

                flash_message('success', 'Added Successfully'); // Call flash message function
                return redirect()->route('admin.help');
            }
        }
    }

    /**
     * Update Help Details
     *
     * @param array $request    Input values
     * @return redirect     to Help View
     */
    public function update(Request $request)
    {
        if ($request->getMethod() == 'GET')
        {

            /*$this->view_data['category'] = HelpCategory::status()->get();
            $this->view_data['form_action'] = route('admin.edit_help',$request->id);
            $this->view_data['form_name'] = trans('admin_messages.edit_help');
            $this->view_data['help'] = Help::find($request->id);
            $this->view_data['subcategory'] = HelpSubCategory::where('category_id',$this->view_data['help']->category_id)->status()->get();
            $this->view_data['status_list'] = array_flip($this->view_data['help']->statusArray);*/
            
            $data['languages'] = Language::where('status', '=', 'Active')->pluck('name', 'value');
            $data['category'] = HelpCategory::active_all();
            $data['subcategory'] = HelpSubCategory::active_all();
            $data['result'] = Help::find($request->id);
            
            return view('admin.help.edit', $data);

            
        }
        else
        {
            Log::info('check log');
            if($request->status == 'Active'){

                $setStatus = 1;

            }
            else{
                $setStatus = 0;                
            }
            // Edit Help Validation Rules
            $rules = array(
                    'question'    => 'required',
                    'category_id' => 'required|numeric',
                    'subcategory_id'=> 'numeric',
                    'answer'      => 'required',
                    'status'      => 'required'
                    );

            // Edit Help Validation Custom Fields Name
            $niceNames = array(
                        'question'    => 'Question',
                        'category_id' => 'Category',
                        'answer'      => 'Answer',
                        'status'      => 'Status'
                        );
            $except = array('description');
            foreach($request->translations ?: array() as $k => $translation)
            {
                $rules['translations.'.$k.'.locale'] = 'required';
                $rules['translations.'.$k.'.name'] = 'required';
                $rules['translations.'.$k.'.description'] = 'required';

                $niceNames['translations.'.$k.'.locale'] = 'Language';
                $niceNames['translations.'.$k.'.name'] = 'Name';
                $niceNames['translations.'.$k.'.description'] = 'Description';
                $except[] = 'translations.'.$k.'.description';
            }
            $validator = Validator::make($request->all(), $rules, ['numeric' => trans('validation.required')]);
            $validator->setAttributeNames($niceNames); 

            if ($validator->fails()) 
            {
                return back()->withErrors($validator)->withInput(); // Form calling with Errors and Input values
            }
            else
            {
                $help = Help::find($request->id);

                $help->category_id    = $request->category_id;
                $help->subcategory_id = $request->subcategory_id;
                $help->question       = $request->question;
                $help->answer         = $request->answer;
                $help->suggested      = $request->suggested;
                $help->status         = $setStatus;

                $help->save();

                 $removed_translations = explode(',', $request->removed_translations);
                foreach(array_values($removed_translations) as $id) {
                    $help->deleteTranslationById($id);
                }

                foreach($request->translations ?: array() as $translation_data) {  
                    $translation = $help->getTranslationById(@$translation_data['locale'], $translation_data['id']);
                    $translation->name = $translation_data['name'];
                    $translation->description = $translation_data['description'];

                    $translation->save();
                }

                flash_message('success', 'Updated Successfully'); // Call flash message function

                return redirect()->route('admin.help');
            }
        }
    }


    

    /**
     * Delete Help
     *
     * @param array $request    Input values
     * @return redirect     to Help View
     */
    public function delete(Request $request)
    {
        Help::find($request->id)->delete();

        flash_message('success', 'Deleted Successfully'); // Call flash message function
        return redirect()->route('admin.help');
    }

    public function ajax_help_subcategory(Request $request)
    {
        $result = HelpSubCategory::where('category_id', $request->id)->status()->get();
        return json_encode($result);
    }
}
