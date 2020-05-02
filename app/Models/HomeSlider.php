<?php

/**
 * HomeSlider Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    HomeSlider
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;

class HomeSlider extends Model {

    
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	public $statusArray = [
		'Active' => 1,
		'Inactive' => 0,
	];
	public $typeArray = [
		'store' => 1,
		'user' => 0,
	];

	protected $table = 'home_slider';

	public $timestamps = false;
	
	use Translatable;

	public $translatedAttributes = ['title', 'description'];

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
	 * To check the status
	 */
	public function scopeStatus($query, $status = 'online') {
		$status_value = $this->statusArray[$status];
		return $query->where('status', $status_value);
	}

	public function scopeType($query,$type) {
		$current_type = $this->typeArray[$type];
		$query->where('type', $current_type);

	}
	public function getStatusTextAttribute() {
		return array_search($this->attributes['status'], $this->statusArray);
	}
	public function getTypeTextAttribute() {
		return array_search($this->attributes['type'], $this->typeArray);
	}
	//slider_image
	public function getSliderImageAttribute() {

		$type = ($this->type==1)?17:20;
		$file = File::where('type',$type)->where('source_id',$this->attributes['id'])->first();
		if($file){
			if($this->type==1)
				return $file->store_home_slider_image;
			else
				return $file->eater_home_slider_image;
		}
	}

}
