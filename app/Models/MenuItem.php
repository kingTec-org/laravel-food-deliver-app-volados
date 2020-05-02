<?php

/**
 * MenuItem Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   MenuItem
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class MenuItem extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	use Translatable;
	

	protected $table = 'menu_item';

	protected $appends = ['menu_item_image', 'offer_price', 'menu_item_thump_image', 'is_offer','org_name','org_description'];

	public $translatedAttributes = ['name','description'];
	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	public function scopeMenuRelations($query) {
		$query->with(
			[
				'menu_item_modifier' => function ($query) {
					$query->menuRelations();
				},
			]
		);
	}

	public function scopeVisible($query, $visible = 1) {
		return $query->where('is_visible', $visible);
	}

	public function scopeStore($query, $store_id) {
		return $query->with(
			[
				'menu' => function ($query) use ($store_id) {
					$query->store($store_id);
				},
			]
		)->whereHas('menu', function ($query) use ($store_id) {
			$query->store($store_id);
		}
		);
	}

	// Join with menu_item_image table
	public function getMenuItemImageAttribute() {
		$menu_image = File::where('source_id', $this->attributes['id'])->where('type', 6)->first();

		if ($menu_image) {
			$name = explode('.', $menu_image->name);
			$file_name = $name[0] . '_120x120.' . $name[1];
			$file_name0 = $name[0] . '_520x320.' . $name[1];
			$file_name1 = $name[0] . '_600x350.' . $name[1];
			$menu_item = MenuItem::find($this->attributes['id']);
			$store_id = Menu::find($menu_item->menu_id)->store_id;
			if (get_current_root() == 'api') {
				$image = [

					'small' => url(Storage::url('images/store/' . $store_id . '/menu_item/' . $file_name)),
					'medium' => url(Storage::url('images/store/' . $store_id . '/menu_item/' . $file_name0)),
					'original' => url(Storage::url('images/store/' . $store_id . '/menu_item/' . $menu_image->name)),

				];
				return $image;
			} else {
				return url(Storage::url('images/store/' . $store_id . '/menu_item/' . $file_name1));
			}

		} else {
			if (get_current_root() == 'api') {
				return (object) [];
			} else {
				return '';
			}

		}
	}

	// Join with menu_item_thump_image table
	public function getMenuItemThumpImageAttribute() {
		$menu_image = File::where('source_id', $this->attributes['id'])->where('type', 6)->first();

		if ($menu_image) {
			$name = explode('.', $menu_image->name);
			$file_name = $name[0] . '_120x120.' . $name[1];
			$menu_item = MenuItem::find($this->attributes['id']);
			$store_id = Menu::find($menu_item->menu_id)->store_id;
			return url(Storage::url('images/store/' . $store_id . '/menu_item/' . $file_name));
		} else {
			return (object) [];
		}
	}

	public function menu() {
		return $this->belongsTo('App\Models\Menu', 'menu_id', 'id');
	}

	public function language_menu() {
		return $this->hasMany('App\Models\MenuItemTranslations', 'menu_item_id', 'id');
	}

	public function review() {

		return $this->belongsTo('App\Models\Review', 'id', 'reviewee_id')->where('type', 0);
	}

	public function menu_review() {

		return $this->hasMany('App\Models\Review', 'reviewee_id', 'id')->where('type', 0);
	}

	public function menu_item_main_addon() {
		return $this->hasMany('App\Models\MenuItemModifier', 'menu_item_id', 'id');
	}

	public function menu_item_modifier() {
		return $this->hasMany('App\Models\MenuItemModifier', 'menu_item_id', 'id');
	}

	public function getOfferPriceAttribute() {

		$menu = Menu::find($this->menu_id);

		if ($menu) {

			$date = \Carbon\Carbon::today();

			$store_offer = StoreOffer::where('start_date', '<=', $date)->where('end_date', '>=', $date)
				->where('store_id', $menu->store_id)->where('status', '1')->first();

			if ($store_offer) {

				if ($store_offer->percentage != 0) {

					return number_format($this->price - ($this->price * $store_offer->percentage / 100), '2', '.', '');

				}

			}

		}

		return 0;

	}

	public function getOrgNameAttribute() {
		return $this->attributes['name'];
	}

	public function getOrgDescriptionAttribute() {
		return $this->attributes['description'];
	}

	public function getIsOfferAttribute() {

		$menu = Menu::find($this->menu_id);

		if ($menu) {

			$date = \Carbon\Carbon::today();

			$store_offer = StoreOffer::where('start_date', '<=', $date)->where('end_date', '>=', $date)
				->where('store_id', $menu->store_id)->where('status', '1')->first();

			if ($store_offer) {

				if ($store_offer->percentage != 0) {

					return 1;

				}

			}

		}

		return 0;

	}

}
