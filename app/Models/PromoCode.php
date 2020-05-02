<?php

/**
 * PromoCode Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   PromoCode
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;

class PromoCode extends Model {
	// use CurrencyConversion;
    
	protected $table = 'promo_code';

	public $statusArray = [
		'Active' => 0,
		'Inactive' => 1,

	];
	use Translatable;
	public $translatedAttributes = ['code'];
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
	/**
	 * To check the order is in cart
	 */
	public function scopeStatus($query, $status = 'Active') {
		$status_value = $this->statusArray[$status];
		return $query->where('status', $status_value);
	}
	public function currency() {
		return $this->belongsTo('App\Models\Currency', 'currency_code', 'code');
	}

	public function promotranslation() {
		return $this->belongsTo('App\Models\PromoCodeTranslations', 'id', 'promo_code_id');
	}

	//promo_status
	public function getPromoStatusAttribute() {
		return get_status_text($this->attributes['status']);
	}
	//promo_type_show
	public function getPromoTypeShowAttribute() {
		return $this->attributes['promo_type'] == 1 ? trans('admin_messages.percentage') : trans('admin_messages.amount');
	}

}
