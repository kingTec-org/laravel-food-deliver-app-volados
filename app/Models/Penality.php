<?php

/**
 * Penality Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Penality
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penality extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $table = 'penality';

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	//user_type
	public function getUserTypeAttribute() {
		return $this->user->type_text;
	}
	//user_name
	public function getUserNameAttribute() {
		return $this->user->name;
	}

}
