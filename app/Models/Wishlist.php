<?php

/**
 * Wishlist Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Wishlist
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $table = 'wishlist';

	protected $appends = ['wishlist_count'];

	// Join with Store table

	public function store() {
		return $this->belongsTo('App\Models\Store', 'store_id', 'id');

	}

	public function getWishlistCountAttribute() {

		return Wishlist::where('store_id', $this->store_id)->count();

	}

}
