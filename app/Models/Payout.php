<?php

/**
 * Payout Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   Payout
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use App\Model\Order;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model {

	protected $table = 'payout';

	public $timestamps = true;

	//status_text
	public function getStatusTextAttribute() {
		return $this->status == 1 ? 'Completed' : 'pending';
	}

	/**
	 * To filter groupId
	 */
	public function scopeUserId($query, $user_id = []) {
		return $query->whereIn('user_id', $user_id);
	}

	public function order() {
		return $this->belongsTo('App\Models\Order', 'order_id', 'id');
	}
	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}
}
