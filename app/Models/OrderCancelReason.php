<?php

/**
 * order Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   order
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;
use JWTAuth;
class OrderCancelReason extends Model {

	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $table = 'order_cancel_reason';
	public $appends = ['add_name'];
	/**
	 * Array of data for status
	 *
	 * @var array
	 */
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
	public $typeArray = [
		'eater' => 0,
		'store' => 1,
		'driver' => 2,
		'admin' => 3,
	];

	public $statusArray = [
		'inactive' => 0,
		'active' => 1,
	];

	 /**
     * To filter based on status
     */
    public function scopeStatus($query, $status='active')
    {
        $status_value = $this->statusArray[$status];

        return $query->where('status', $status_value);
    }

     public function getAddNameAttribute()
    {
    if(request('token'))
    {
      $user=JWTAuth::parseToken()->authenticate();
      if($user)
      {
      $lan=$user->language;

      if($lan=='en')
        return $this->attributes['name'];
      else{ 
         $get = OrderCancelReasonTranslations::where('order_cancel_reason_id',$this->attributes['id'])->where('locale',$lan)->first();
         if($get)
          return $get->name;
        else
          return $this->attributes['name'];
     	 }
  	   }
	}
    }

	/**
	 * To filter based on type text
	 */
	public function scopeTypeText($query, $type = "eater") {
		$type_value = $this->typeArray[$type];
		return $query->type($type_value);
	}
	/**
	 * To filter based on type
	 */
	public function scopeType($query, $type = 0) {
		return $query->where('type', $type);
	}

	//status_text
	public function getStatusTextAttribute() {
		return array_search($this->status, $this->statusArray);
	}

	//ger user_type
	public function getUserTypeAttribute() {
		return array_search($this->attributes['type'], $this->typeArray);
	}
}
