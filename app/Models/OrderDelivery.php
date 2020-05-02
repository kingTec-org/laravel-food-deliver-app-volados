<?php

/**
 * OrderDelivery Model
 *
 * @package    Gofer
 * @subpackage Model
 * @category   OrderDelivery
 * @author     Trioangle Product Team
 * @version    1.5
 * @link       http://trioangle.com
 */
namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Storage;

class OrderDelivery extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'order_delivery';

	public $statusArray = [

		'pending' => 0,
		'confirmed' => 1,
		'declined' => 2,
		'started' => 3,
		'delivered' => 4,
		'completed' => 5,
		'cancelled' => 6,
	];

	public $fileArray = [

		'trip_image' => 15,
		'map_image' => 16,

	];

	protected $appends = ['vehicle_type_name', 'driver_earning', 'driver_payout'];

	/**
	 * To filter status
	 */
	public function scopeStatus($query, $status = ['pending']) {
		$array_status = array_map(
			function ($value) {
				return $this->statusArray[$value];
			},
			$status
		);
		return $query->whereIn('status', $array_status);
	}

	/**
	 * To filter order
	 */
	public function scopeOrderId($query, $order_id = []) {
		return $query->whereIn('order_id', $order_id);
	}

	/**
	 * To filter groupId
	 */
	public function scopeDriverId($query, $driver_id = []) {
		return $query->whereIn('driver_id', $driver_id);
	}

	/**
	 * To filter based on week
	 */
	public function scopeWeek($query, $start_date) {
		$end_date = strtotime($start_date.' 23:59:00');
		$start_date = strtotime($start_date);

		$end_date = strtotime('+6 days', $end_date);

		return $query->whereRaw('UNIX_TIMESTAMP(confirmed_at) >= ' . $start_date)
			->whereRaw('UNIX_TIMESTAMP(confirmed_at) <= ' . $end_date);
	}

	/**
	 * To filter based on date range
	 */
	public function scopeDateRange($query, $query_start_date, $query_end_date) {
		$query_start_date = strtotime($query_start_date);
		$query_end_date = strtotime($query_end_date);

		return $query->whereRaw('UNIX_TIMESTAMP(confirmed_at) >= ' . $query_start_date)
			->whereRaw('UNIX_TIMESTAMP(confirmed_at) <= ' . $query_end_date);
	}

	/**
	 * To filter based on date range
	 */
	public function scopeDate($query, $query_date) {
		return $query->whereRaw('DATE_FORMAT(updated_at, "%Y-%m-%d") = "' . $query_date . '"');
	}

	/**
	 * To filter based on date before
	 */
	public function scopePast($query, $query_date) {
		return $query->whereRaw('DATE_FORMAT(updated_at, "%Y-%m-%d") < "' . $query_date . '"');
	}

	// Join with order table
	public function order() {
		return $this->belongsTo('App\Models\Order', 'order_id', 'id');
	}

	// Join with request table
	public function driver_request() {
		return $this->belongsTo('App\Models\DriverRequest', 'request_id', 'id');
	}

	// Join with driver table
	public function driver() {
		return $this->belongsTo('App\Models\Driver', 'driver_id', 'id');
	}
	// Join with profile_picture table
	public function profile_picture() {
		return $this->belongsTo('App\Models\ProfilePicture', 'user_id', 'user_id');
	}

	public function getStatusTextAttribute() {
		return array_search($this->status, $this->statusArray);
	}

	public function getVehicleTypeNameAttribute() {
		return $this->driver->vehicle_type_name;
	}
	public function getDriverEarningAttribute() {
		return $this->order->driver_commision_fee;
	}

	public function getDriverPayoutAttribute() {

		$payout_amount = '0.00';

		if (isset($this->driver_id)) {

			$payout = Payout::where('user_id', $this->driver->user_id)->where('order_id', $this->order_id)->first();

			if ($payout) {

				$payout_amount = $payout->amount;
			}

		}

		return $payout_amount;

	}

	public function getTripPathAttribute() {

		if ($this->statusArray['completed'] == $this->status) {

			$name = File::where('type', $this->fileArray['trip_image'])->where('source_id', $this->order_id)->first();
			if ($name) {
				return url(Storage::url("public/images/trip_image/" . $this->order_id . "/" . $name->name));
			} else {
				return url('/images/map.png');
			}
		} else {

			$name = File::where('type', $this->fileArray['map_image'])->where('source_id', $this->order_id)->first();
			if ($name) {
				return url(Storage::url("public/images/map_image/" . $name->name));
			} else {

				return url('/images/map.png');
			}

		}

	}

	public function confirmed() {
		$this->status = $this->statusArray['confirmed'];
		$this->confirmed_at = date('Y-m-d H:i:s');
		$this->save();
	}

	public function started() {
		$this->status = $this->statusArray['started'];
		$this->started_at = date('Y-m-d H:i:s');
		$this->save();

		$this->order->delivery_started();
	}

	public function delivered() {
		$this->status = $this->statusArray['delivered'];
		$this->delivery_at = date('Y-m-d H:i:s');
		$this->save();
	}

	public function completed() {

		$this->status = $this->statusArray['completed'];
		$datetime1 = new DateTime($this->confirmed_at);
		$datetime2 = new DateTime($this->delivery_at);
		$interval = $datetime1->diff($datetime2);

		$duration = '';

		if ($interval) {

			if ($interval->h) {

				$duration .= $interval->h . ' hr ';

			}

			if ($interval->i) {

				$duration .= $interval->i . ' min ';

			}

			// if ($interval->s) {

			// 	$duration .= $interval->s . ' sec ';

			// }
		}

		$this->duration = trim($duration);
		$this->save();

		$this->order->delivery_completed();
	}

	public function cancelled() {
		$this->status = $this->statusArray['cancelled'];
		$this->cancelled_at = date('Y-m-d H:i:s');
		$this->save();
	}

	public function res_cancelled() {

		$this->status = $this->statusArray['cancelled'];
		$this->cancelled_at = date('Y-m-d H:i:s');
		$this->save();

		$driver = Driver::find($this->driver_id);
		$driver->status = 1;
		$driver->save();
	}

	public function revert() {

		$driver = Driver::find($this->driver_id);
		$driver->status = 1;
		$driver->save();

		//maintain driver cancel history

		$drivercancel = new DriverCancelHistory;
		$drivercancel->driver_id = $this->driver_id;
		$drivercancel->order_id = $this->order_id;
		$drivercancel->save();

		//Revert Order delivery status

		$this->status = -1;
		$this->driver_id = NULL;
		$this->request_id = NULL;
		$this->cancelled_at = date('Y-m-d H:i:s');
		$this->save();

	}

}
