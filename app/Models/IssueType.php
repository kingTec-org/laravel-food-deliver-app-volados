<?php

/**
 * IssueType Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   IssueType
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;
class IssueType extends Model {

	public $timestamps = false;
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'issue_type';

	/**
	 * Array of data for status
	 *
	 * @var array
	 */

	public $typeArray = [
		'user_menu_item' => 0,
		'user_driver' => 1,
		'store_delivery' => 2,
		'driver_delivery' => 3,
		'driver_store' => 4,
	];

	public $statusArray = [
		'inactive' => 0,
		'active' => 1,
	];

	use Translatable;
    public $translatedAttributes = ['name'];

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
	 * To filter based on status
	 */
	public function scopeStatus($query, $status = "active") {
		$status_value = $this->statusArray[$status];
		return $query->where('status', $status_value);
	}

	/**
	 * To filter based on type text
	 */
	public function scopeTypeText($query, $type = "user_menu_item") {
		$type_value = $this->typeArray[$type];
		return $query->type($type_value);
	}
	/**
	 * To filter based on type
	 */
	public function scopeType($query, $type = 0) {
		return $query->where('type_id', $type);
	}
	
	//status
	public function getStatusTextAttribute() {
		return array_search($this->status, $this->statusArray);
	}
	//ger user_type
	public function getUserTypeAttribute() {
		$typeArray = [
			0 => 'User item',
			1 => 'User driver',
			2 => 'Store delivery',
			3 => 'Driver delivery',
			4 => 'Driver store',
		];
		return $typeArray[$this->attributes['type_id']];
	}
}
