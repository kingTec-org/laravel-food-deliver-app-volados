<?php

/**
 * MenuCategory Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   MenuCategory
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;
class MenuCategory extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	use Translatable;
	public $translatedAttributes = ['name'];
	protected $table = 'menu_category';
	  public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        if(Request::segment(1) == 'admin' || Request::segment(1) == 'store') {
            $this->defaultLocale = 'en';
        }
        else {
            $this->defaultLocale = Session::get('language');
        }
    }
	public function scopeMenuRelations($query) {
		return $query->with(
			[
				'menu_item' => function ($query) {
					$query->menuRelations();
				},
			]
		);
	}

	// Join with Menu table
	public function menu_item() {
		return $this->hasMany('App\Models\MenuItem', 'menu_category_id', 'id')->where('status', 1);
	}
	// Join with All Menu Item table
	public function all_menu_item() {
		return $this->hasMany('App\Models\MenuItem', 'menu_category_id', 'id');
	}
}
