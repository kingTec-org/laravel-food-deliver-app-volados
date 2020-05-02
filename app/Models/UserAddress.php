<?php

/**
 * UserAddress Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    UserAddress
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $table = 'user_address';
	protected $appends = ['static_map', 'address1', 'default_timezone'];
	public $timestamps = false;

	Public $AddresstypeArray = [

		'home' => 0,
		'work' => 1,
		'delivery' => 2,

	];

	public function scopeDefault($query, $default = 1) {
		return $query->where('default', $default);
	}
	/**
	 * To check the Addresstype
	 */
	public function scopeAddressType($query, $status = 'home') {
		$type_value = $this->AddresstypeArray[$status];

		return $query->where('type', $type_value);
	}

	public function getStaticMapAttribute() {

		if (isset($this->attributes['latitude'])) {
			return 'https://maps.googleapis.com/maps/api/staticmap?center=' . $this->attributes['latitude'] . ',' . $this->attributes['longitude'] . '&markers=icon:' . url('images/map_green.png') . '|color:red|label:C|' . $this->attributes['latitude'] . ',' . $this->attributes['longitude'] . '&zoom=12&size=100x100&key=' . site_setting('google_api_key');
		} else {
			return '';
		}

	}

	public function getAddress1Attribute() {

		$address = '';
		if (isset($this->attributes['street'])) {
			if ($this->attributes['street']) {
				$address .= $this->attributes['street'];
			}

			if ($this->attributes['city']) {
				$address .= ' ' . $this->attributes['city'];
			}

			if ($this->attributes['state']) {
				$address .= ' ' . $this->attributes['state'];
			}

			if ($this->attributes['country']) {
				$address .= ' ' . $this->attributes['country'];
			}

		}
		return str_replace('  ', '', $address);

	}

	public function getDefaultTimezoneAttribute() {

		$time = Timezone::where('name', $this->country)->first();
		if ($time) {
			return $time->value;
		} else {
			return 'Asia/kolkata';
		}

	}

}
