<?php

/**
 * HelpController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    HelpController
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use App\Models\HelpCategory;
use App\Models\HelpSubCategory;
use App\Models\Help;

class HelpController extends Controller {

	//help page function

	public function help() {

		$request = request();
		$type = 'User';
		if($request->page){
			$type = ucfirst($request->page);
		}

		$data['page'] = strtolower($type); 
		$data['help'] = HelpCategory::type($type)->status()->get(); 
		return view('help',$data);
	}
	//help help_category function

	public function help_category() {

		$request = request();
		$type = 'User';
		if($request->page){
			$type = ucfirst($request->page);
		}

		$data['page'] = strtolower($type); 
		$data['category_id'] = $request->category_id; 
		$data['help_category'] = HelpCategory::type($type)->status()->get(); 
		$data['help_subcategory'] = HelpSubCategory::with('help')->where('category_id',$request->category_id)->status()->get();
		return view('help_category',$data);
	}

	//help help_category function

	public function help_subcategory() {
		
		$request = request();
		$type = 'User';
		if($request->page){
			$type = ucfirst($request->page);
		}

		$data['page'] = strtolower($type); 
		$data['subcategory_id'] = $request->subcategory_id; 
		$data['help_subcategory'] = HelpSubCategory::with('help')->where('id',$request->subcategory_id)->status()->get(); 
		$data['remain_help_subcategory'] = HelpSubCategory::with('help')->where('category_id',$request->category_id)->status()->get(); 
		$data['category'] = HelpCategory::find($request->category_id); 
		

		return view('help_subcategory',$data);
	}

	//help help_category function

	public function help_question() {

		$request = request();
		$type = 'User';
		if($request->page){
			$type = ucfirst($request->page);
		}

		$data['page'] = strtolower($type); 
		$data['help_subcategory'] = HelpSubCategory::with('help')->where('id',$request->subcategory_id)->status()->get(); 
		$data['remain_help_subcategory'] = HelpSubCategory::with('help')->where('category_id',$request->category_id)->status()->get(); 
		$data['question'] = Help::find($request->question_id); 
		
		return view('help_question',$data);
	}

	public function ajax_help_search() {
		$request = request();
		// dd($request->page);
		$term = $request->term;

		$queries = Help::where('question', 'like', '%' . $term . '%')->get();
		if ($queries->isEmpty()) {
			$results[] = ['id' => '0', 'value' => trans('messages.no_results_found'), 'question' => trans('messages.no_results_found')];
		} else {
			foreach ($queries as $query) {
				$results[] = ['page' => strtolower($query->category->type_text),'category_id' => $query->category_id,'subcategory_id' => $query->subcategory_id,'id' => $query->id, 'value' => $query->question, 'question' => str_slug($query->question, '-')];
			}
		}

		return json_encode($results);
	}

	
}
