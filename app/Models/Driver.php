<?php

/**
 * Driver Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   Driver
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;

class Driver extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $table = 'driver';
	public $timestamps = false;

	protected $appends = [
		'vehicle_image', 'vehicle_type_name', 'owe_amount', 'driver_contact', 'driver_timezone', 'driver_profile_picture',
	];

	public $statusArray = [
		'offline' => 0,
		'online' => 1,
		'trip' => 2,
	];

	public $document_type_array;

	public function __construct() {
		parent::__construct();

		$this->document_type_array = FileType::whereIn(
			'name',
			[
				'driver_licence_front',
				'driver_licence_back',
				'driver_registeration_certificate',
				'driver_insurance',
				'driver_motor_certiticate',
				'driver_image',
			]
		)->pluck('id', 'name')->toArray();
	}

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

	public function scopeActive($query) {
		return $query->with(
			[
				'user' => function ($query) {
					$query->status();
				},
			]
		)->whereHas(
			'user',
			function ($query) {
				$query->status();
			}
		);
	}

	public function scopeVehicleType($query, $vehicle_types = []) {
		if (!is_array($vehicle_types)) {
			$vehicle_types = explode(',', $vehicle_types);
		}

		return $query->with(['vehicle_type_details'])->whereIn('vehicle_type', $vehicle_types);
	}

	public function scopeWithinDistance($query, $latitude, $longitude, $distance) {

		return $query->select()->addSelect(DB::raw('( 6371 * acos( cos( radians(' . $latitude . ') ) * cos( radians( latitude ) ) * cos(radians( longitude ) - radians(' . $longitude . ') ) + sin( radians(' . $latitude . ') ) * sin( radians( latitude ) ) ) ) as distance'))
			->having('distance', '<=', $distance)
			->orderBy('distance', 'asc');
	}

	public function scopeNoPreviousRequest($query, $group_id) {

		$results = $query->with('driver_request')->whereHas('driver_request',

			function ($query) use ($group_id) {

				$query->status(['pending']);

			}, '=', 0

		)->WhereHas('driver_request',

			function ($query) use ($group_id) {

				$query->status(['accepted', 'cancelled'])->groupId([$group_id]);

			}, '=', 0
		);

		return $results;

	}

	public function scopeSearch($query, $latitude, $longitude, $distance, $group_id) {
		$results = $query->status()->active()->withinDistance($latitude, $longitude, $distance)->with('driver_request')
			->whereHas('driver_request', function ($query) use ($group_id) {
				$query->where('group_id', $group_id)->whereIn('status', ['0', '2'])->whereNotIn('status', ['1']);
			}, '=', 0);

		return $results;

		/*select *, ( 6371 * acos( cos( radians(9.9441093) ) * cos( radians( latitude ) ) * cos(radians( longitude ) - radians(78.1560945) ) + sin( radians(9.9441093) ) * sin( radians( latitude ) ) ) ) as distance from `driver` where `status` = 1 and exists (select * from `user` where `driver`.`user_id` = `user`.`id` and `status` = 1) and (select count(*) from `request` where `driver`.`id` = `request`.`driver_id` and `group_id` = ? and `status` in (0, 2) and `status` not in (1) and `request`.`deleted_at` is null) = 0 having `distance` <= 5 order by `distance` asc*/

	}

	public function scopeUser($query, $user_id) {
		return $query->where('user_id', $user_id);
	}

	public function getOweAmountAttribute() {

		$owe_amount = DriverOweAmount::where('user_id', get_current_login_user_id())->first();

		if ($owe_amount) {
			return $owe_amount->amount;

		}

		return '0';

	}

	public function user() {
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

	public function user_address() {
		return $this->belongsTo('App\Models\UserAddress', 'user_id', 'user_id');
	}

	public function vehicle_type_details() {
		return $this->belongsTo('App\Models\VehicleType', 'vehicle_type', 'id');
	}

	public function documents() {
		return $this->hasMany('App\Models\File', 'source_id', 'id')->whereIn('type', array_values($this->document_type_array));
	}

	public function order_delivery() {
		return $this->hasMany('App\Models\OrderDelivery', 'driver_id', 'id');
	}

	public function driver_cancel_history() {
		return $this->hasMany('App\Models\DriverCancelHistory', 'driver_id', 'id');
	}

	public function driver_request() {
		return $this->hasMany('App\Models\DriverRequest', 'driver_id', 'id');
	}
	public function get_document_name($type, $field) {

		$documents = $this->documents()->whereType($type)->first();
		if (isset($documents)) {
			return $documents->$field;
		}
		return getEmptyUserImageUrl();
	}

	// Join with file table
	public function driver_licence_front() {
		return $this->belongsTo('App\Models\File', 'id', 'source_id')->type($this->document_type_array['driver_licence_front']);
	}
	// Join with file table
	public function driver_licence_back() {
		return $this->belongsTo('App\Models\File', 'id', 'source_id')->type($this->document_type_array['driver_licence_back']);
	}
	// Join with file table
	public function driver_registeration_certificate() {
		return $this->belongsTo('App\Models\File', 'id', 'source_id')->type($this->document_type_array['driver_registeration_certificate']);
	}
	// Join with file table
	public function driver_insurance() {
		return $this->belongsTo('App\Models\File', 'id', 'source_id')->type($this->document_type_array['driver_insurance']);
	}
	// Join with file table
	public function driver_motor_certiticate() {
		return $this->belongsTo('App\Models\File', 'id', 'source_id')->type($this->document_type_array['driver_motor_certiticate']);
	}

	public function getStatusTextAttribute() {
		return array_search($this->status, $this->statusArray);
	}

	public function getVehicleTypeNameAttribute() {
		return $this->vehicle_type_details ? $this->vehicle_type_details->name : "";
	}

	public function getDriverContactAttribute() {
		$user = User::find($this->user_id);
		if ($user) {
			return $user->country_code . $user->mobile_number;
		}
		return '';
	}
	//vehicle_image
	public function getVehicleImageAttribute() {

		if (isset($this->vehicle_type_details)) {
			return $this->vehicle_type_details->vehicle_image;
		} else {
			return '';
		}

	}

	public function review() {
		return $this->belongsTo('App\Models\Review', 'id', 'reviewee_id');
	}
	public function getDriverTimezoneAttribute() {

		if (isset($this->latitude)) {

			$geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $this->latitude . ',' . $this->longitude . '&sensor=false&key=' . site_setting('google_api_key') . '');

			$output = json_decode($geocode);

			if (!@$output->results[0]->formatted_address) {
				return 'Asia/kolkata';
			}

			$address = $output->results[0]->formatted_address;

			$country = explode(",", $address);
			$country = end($country);

			$country = Country::where('name', trim($country))->first();

			if ($country) {
				$code = $country->code;
			} else {
				$code = 'IN';
			}

			$time = Timezone::where('name', $country)->first();

			if ($time) {

				return $time->value;

			} else {
				return 'Asia/kolkata';
			}

		}

		return 'Asia/kolkata';

	}

	public function getDriverProfilePictureAttribute() {

		$profile_image = '';

		$filetype = FileType::where('name', 'driver_image')->first();

		$file_image = File::where(['source_id' => $this->attributes['id'], 'type' => $filetype->id])->first();

		if ($file_image != '') {
			$profile_image = url('/') . '/storage/images/driver/' . $file_image->name;
		} else {
			$profile_image = url('/') . '/images/user.png';
		}

		return $profile_image;
	}

}
