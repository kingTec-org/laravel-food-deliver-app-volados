<?php

/**
 * StoreTime Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Store
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreTime extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	public $timestamps = false;

	protected $table = 'store_time';

	protected $appends = ['day_name', 'closed', 'orginal_start_time', 'orginal_end_time','start_time_for_english','end_time_for_english'];

	public function getDayNameAttribute() {

		return day_name($this->day);

	}

	public function getStartTimeAttribute() {

		return date('h:i', strtotime($this->attributes['start_time'])).' '.trans('api_messages.monthandtime.'.date('a', strtotime($this->attributes['start_time'])));

	}

	public function getStartTimeForEnglishAttribute() {

		return date('h:i a', strtotime($this->attributes['start_time']));

	}

	public function getEndTimeForEnglishAttribute() {

		return date('h:i a', strtotime($this->attributes['end_time']));

	}

	public function getEndTimeAttribute() {

		return date('h:i', strtotime($this->attributes['end_time'])).' '.trans('api_messages.monthandtime.'.date('a', strtotime($this->attributes['end_time'])));

	}
	//orginal_start_time
	public function getOrginalStartTimeAttribute() {

		return $this->attributes['start_time'];

	}
	//orginal_end_time
	public function getOrginalEndTimeAttribute() {

		return $this->attributes['end_time'];

	}

	/**
	 * is_available  or not
	 */

	public function getIsAvailableAttribute() {

		// store time status
		$schedule_data = session('schedule_data');
		if (isset($schedule_data) && $schedule_data['status'] == 'Schedule') {
			$day = date('N', strtotime($schedule_data['date']));
			$time = strtotime($schedule_data['time']);
		} else {
			$day = date('N');
			$time = time();
		}
		$store_time = StoreTime::where('day', $day)->where('store_id', $this->attributes['store_id'])->where('status', '1')->first();
		if ($store_time) {

			if ($time >= strtotime($store_time->start_time_for_english) &&
				$time <= strtotime($store_time->end_time_for_english)) {

				return 1;

			}

		}
		return 0;
	}

	/**
	 * Store Time closed or not
	 */

	public function getClosedAttribute() {

		// Schedule Order

		if (get_current_login_user_id()) {

			$user_address = UserAddress::where('user_id', get_current_login_user_id())->where('default', '1')->first();

			if ($user_address) {

				if ($user_address->order_type == 1) {

					$timestamp = strtotime($user_address->delivery_time);

					$day = date('N', $timestamp);
					$time = date('h:i a', strtotime($user_address->delivery_time));

					$store_time = StoreTime::where('day', $day)->where('store_id', $this->attributes['store_id'])->where('status', '1')->first();

					if ($store_time) {

						if (strtotime($time) >= strtotime($store_time->start_time_for_english) &&
							strtotime($time) <= strtotime($store_time->end_time_for_english)) {

							return 1;

						}

					}
					return 0;

				}

			}

		}

		//Asap

		$time = time();

		if ($time >= strtotime($this->attributes['start_time']) && $time <= strtotime($this->attributes['end_time'])) {

			return 1;

		}

		return 0;

	}

}
