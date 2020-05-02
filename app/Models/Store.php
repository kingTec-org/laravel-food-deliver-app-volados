<?php

/**
 * Store Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   Store
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use App\Models\Currency;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;

class Store extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $table = 'store';
	protected $appends = ['convert_mintime', 'convert_maxtime', 'store_image', 'banner', 'currency_symbol', 'wishlist_count', 'store_next_opening'];
	public $timestamps = false;

	public $statusArray = [
		'offline' => 0,
		'online' => 1,
	];
	public $fileArray = [
		'store_banner' => 3,
	];
	public $userstatusArray = [
		'inactive' => 0,
		'active' => 1,
	];

	public $image_size = [

		0 => '520x280',
		1 => '480x320',
		2 => '520x320',
		3 => '100x100',

	];

	public function scopeAuthUser($query) {
		$user_id = '';
		if (request()->segment(1) == 'api') {
			$user_id = JWTAuth::parseToken()->authenticate()->id;
		}
		return $query->user($user_id);
	}

	/**
	 * To check the status
	 */
	public function scopeStatus($query, $status = 'online') {
		$status_value = $this->statusArray[$status];
		return $query->where('status', $status_value);
	}

	public function scopeUserStatus($query, $status = 'active') {

		$status_value = $this->userstatusArray[$status];

		$result = $query->whereHas('user', function ($query) {

			$query->status();
		});

		return $result;

	}

	public function scopeUser($query, $user_id) {
		return $query->where('user_id', $user_id);
	}

	public function scopeMenuRelations($query) {
		return $query->with(
			[
				'store_menu' => function ($query) {
					$query->menuRelations();
				},
			]
		);
	}

	// Join with store tablehasMany

	public function store_category() {
		return $this->hasMany('App\Models\StoreCategory', 'store_id', 'id');
	}
	// Join with store document table
	public function store_document() {
		return $this->hasMany('App\Models\StoreDocument', 'store_id', 'id');
	}

	// Join with store_preparation_time table

	public function store_preparation_time() {
		return $this->hasMany('App\Models\StorePreparationTime', 'store_id', 'id');
	}

	// Join with store_time table

	public function store_all_time() {
		return $this->hasMany('App\Models\StoreTime', 'store_id', 'id');
	}

	// Join with store_time table

	public function store_time() {
		return $this->belongsTo('App\Models\StoreTime', 'id', 'store_id')->where('status', 1)->where('day', date('N'));
	}

	public function getStoreNextOpeningAttribute() {

		$user_id = get_current_login_user_id();
		$address = get_user_address($user_id);

		if (isset($address) && $address->order_type == 1) {

			$date = strtotime($address->delivery_time);

		} else {

			$date = time();
		}

		$cur_Date = date('N', $date);

		$store = StoreTime::where('store_id', $this->id)->where('status', '1')->orderBy('day', 'ASC')->get()->toArray();

		if ($store) {

			$days = array_column($store, 'day');

			$match = array_search($cur_Date, $days);

			if (isset($match) && ((string) $match != '')) {

				if (strtotime($store[$match]['end_time']) >= $date) {
					return trans('api_messages.store.opens_at') . $store[$match]['start_time'];
				}

				if ($match == count($days) - 1) {
					return trans('api_messages.store.opens_on') . $store[0]['day_name'];
				}

				if ($match != count($days) - 1) {
					return trans('api_messages.store.opens_on') . $store[$match + 1]['day_name'];
				}

			} else {

				$match = array_search($cur_Date + 1, $days);
				if ($match) {
					return trans('api_messages.store.opens_on') . $store[$match]['day_name'];
				}
				$match = array_search($cur_Date + 2, $days);
				if ($match) {
					return trans('api_messages.store.opens_on') . $store[$match]['day_name'];
				}

				return trans('api_messages.store.opens_on') . $store[0]['day_name'];

			}

		}
	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function user_address() {
		return $this->belongsTo('App\Models\UserAddress', 'user_id', 'user_id');
	}

	//recommend_status
	public function getRecommendStatusAttribute() {
		return get_status_yes($this->attributes['recommend']);
	}
	public function scopelocation($query, $latitude, $longitude) {

		$km = site_setting('store_km');

		if ($latitude != '') {
			return $query->whereHas(
				'user_address',
				function ($query) use ($latitude, $longitude, $km) {
					$query->select(DB::raw('*,( 6371 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) as distance'))
						->having('distance', '<=', $km);

				}
			);
		}
	}

	public function getWishlistCountAttribute() {

		return Wishlist::where('store_id', $this->store_id)->count();

	}

	//store images

	public function getBannerAttribute() {

		$store_id = $this->attributes['id'];

		$image = File::where('type', $this->fileArray['store_banner'])->where('source_id', $store_id)->first();

		if ($image) {

			$name = explode("/", $image->image_name);
			$filename = end($name);

			$url = explode('/', $image->image_name);
			array_pop($url);
			$url = implode('/', $url);

			$name = explode('.', $filename);
			$filename = $name[0];
			$extension = $name[1];
			$image = [

				'small' => $url . '/' . $filename . '_' . $this->image_size['0'] . '.' . $extension,
				'medium_x' => $url . '/' . $filename . '_' . $this->image_size['1'] . '.' . $extension,
				'medium' => $url . '/' . $filename . '_' . $this->image_size['2'] . '.' . $extension,
				'original' => $image->image_name,
				'smallest' => $url . '/' . $filename . '_' . $this->image_size['3'] . '.' . $extension,

			];

			return $image;
		} else {
			return $image = [

				'small' => getEmptyStoreImage(),
				'medium_x' => getEmptyStoreImage(),
				'medium' => getEmptyStoreImage(),
				'original' => getEmptyStoreImage(),
				'smallest' => getEmptyStoreImage(),

			];
		}
	}
	public function getStoreImageAttribute() {

		$store_id = $this->attributes['id'];

		$image = File::where('type', $this->fileArray['store_banner'])->where('source_id', $store_id)->first();

		if ($image) {

			$image = $image->image_name;

			return $image;
		} else {
			return getEmptyStoreImage();
		}
	}

	// Join with store_offer table

	public function store_offer() {

		$date = \Carbon\Carbon::today();

		return $this->hasMany('App\Models\StoreOffer', 'store_id', 'id')->where('status', '1')
			->where('start_date', '<=', $date)->where('end_date', '>=', $date)->orderBy('id', 'desc');
	}

	// Join with file table

	public function file() {
		return $this->hasMany('App\Models\File', 'source_id', 'id');
	}

	// Join with Menu table

	public function store_menu() {
		return $this->hasMany('App\Models\Menu', 'store_id', 'id');
	}

	// Join with Review table

	public function review() {
		return $this->belongsTo('App\Models\Review', 'id', 'reviewee_id')->withDefault();

	}

	// Join with all Review table

	public function all_review() {
		return $this->hasMany('App\Models\Review', 'reviewee_id', 'id')->where('type', 2);

	}

	// Join with Order table

	public function order() {
		return $this->hasMany('App\Models\Order', 'store_id', 'id');

	}

	// Join with wished table

	public function wished() {
		return $this->hasMany('App\Models\Wishlist', 'store_id', 'id');
	}

	// Get Restaurnat Wishlist

	public function wishlist($user_id, $store_id) {
		$wishlist = Wishlist::where('user_id', $user_id)->where('store_id', $store_id)->first();

		if ($wishlist) {
			return 1;
		}
		return 0;
	}

	public function getConvertMintimeAttribute() {

		$time = $this->preparationTime();
		return convert_minutes($time);
	}

	public function getConvertMaxtimeAttribute() {

		$time = $this->preparationTime();
		return convert_minutes($time) + 10;
	}

	public function getStatusTextAttribute() {

		return array_search($this->status, $this->statusArray);
	}

	public function preparationTime() {

		$user_id = get_current_login_user_id();

		$address = get_user_address($user_id);

		if (isset($address) && $address->order_type == 1) {

			$date = strtotime($address->delivery_time);

		} else {

			$date = time();

		}

		$day = date('N', $date);

		$store_preparation_time = $this->store_preparation_time()->where('day', $day)->first();

		if ($store_preparation_time) {
			$preparation_time = $store_preparation_time->attributes['max_time'];
		} else {
			$preparation_time = $this->attributes['max_time'];
		}

		return $preparation_time;
	}

	public function getStorePreparationTime($date) {

		$date = strtotime($date);
		$day = date('N', $date);

		$store_preparation_time = $this->store_preparation_time()->where('day', $day)->first();

		if ($store_preparation_time) {
			$preparation_time = $store_preparation_time->attributes['max_time'];
		} else {
			$preparation_time = $this->attributes['max_time'];
		}

		return $preparation_time;
	}

	public function currency() {
		return $this->belongsTo('App\Models\Currency', 'currency_code', 'code');
	}

	public function getCurrencySymbolAttribute() {

		$currency_code = $this->attributes['currency_code'];

		$symbol = Currency::where('code', $currency_code)->first()->symbol;

		return $symbol;
	}

	public function getProfileStepAttribute() {
		$image = File::where('type', $this->fileArray['store_banner'])->where('source_id', $this->attributes['id'])->first();
		if ($this->user->mobile_no_verify == 1 && $this->user->date_of_birth && $this->attributes['description']  && $this->attributes['price_rating'] && $image) {
			return true;
		} else {
			return false;
		}

	}
}
