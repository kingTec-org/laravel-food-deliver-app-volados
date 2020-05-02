<?php

/**
 * Currency Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Currency
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'currency';

	//protected $appends = ['original_symbol'];

	public $timestamps = false;

	public function scopeActive($query) {
		return $query->where('status', 'Active');
	}

	public function scopeDefaultCurrency($query) {
		return $query->where('default_currency', '1');
	}

	public function scopePaypal($query) {
		return $query->where('paypal_currency', 'Yes');
	}

	public function scopeCodeSelect($query) {
		return $query->active()->pluck('code', 'code');
	}

	public function scopeCodeSelectPaypal($query) {
		return $query->active()->paypal()->pluck('code', 'code');
	}

	// Get symbol by where given code
	public static function original_symbol($code) {
		$symbol = DB::table('currency')->where('code', $code)->first()->symbol;
		return $symbol;
	}

	// Get currenct record symbol
	public function getOriginalSymbolAttribute() {
		$symbol = $this->attributes['symbol'];
		return $symbol;
	}

	// Get currency_status
	public function getCurrencyStatusAttribute() {
		return get_status_text($this->attributes['status']);
	}

	public function setSymbolAttribute($value) {
		$this->attributes['symbol'] = htmlentities($value);
	}
}
