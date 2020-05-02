<?php

/**
 * Pages Model
 *
 * @package     Gofer
 * @subpackage  Model
 * @category    Pages
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;
class Pages extends Model {

	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'static_page';
	use Translatable;
	public $translatedAttributes = ['name', 'content'];
	/**
	 * Array of data for status
	 *
	 * @var array
	 */

	public $userArray = [
		'user' => 0,
		'store' => 1,
		'driver' => 2,
	];

	   public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        if(Request::segment(1) == 'admin') {
            $this->defaultLocale = 'en';
        }
        else {
            $this->defaultLocale = Session::get('language');
        }
    }

	/**
	 * To check the order is in cart
	 */
	public function scopeUser($query, $status = 'user') {
		

		/*if(Request::segment(1) == 'about'){
				$get_status_value  = Pages::where('url',Request::segment(2))->first();
				$status_value = $get_status_value->user_page;
			}else{
				$status_value = $this->userArray[$status];	
			}*/
		
			$status_value = $this->userArray[$status];
			return $query->where('user_page', $status_value);	
		
		
	}
	//user_page_text
	public function getUserPageTextAttribute() {
		return array_search($this->attributes['user_page'], $this->userArray);
	}

	//page_status
	public function getPageStatusAttribute() {
		return get_status_text($this->attributes['status']);
	}
	//page_footer
	public function getPageFooterAttribute() {
		return $this->attributes['footer'] == 1 ? trans('admin_messages.yes') : trans('admin_messages.no');
	}

	public function getUserPage($url){
		 return $this->query()->select('user_page')->where('user_page',$url);
	}
}
