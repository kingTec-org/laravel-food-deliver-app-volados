<?php

/**
 * UserPromoCode Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   UserPromoCode
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersPromoCode extends Model
{
    protected $table = 'users_promo_code';

    public function promo_code()
    {
    	return $this->belongsTo('App\Models\PromoCode','promo_code_id','id')->where('end_date', '>=', date('Y-m-d'));
    }
    public function promo_code_many()
    {
    	return $this->hasMany('App\Models\PromoCode','id','promo_code_id')->where('end_date', '>=', date('Y-m-d'))->limit(1);
    }
}
