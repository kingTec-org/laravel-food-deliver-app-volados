<?php

/**
 * User Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   User
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */

namespace App\Models;

use App\Models\Country;
use App\Models\Wallet;
use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use JWTAuth;

class User extends Authenticatable {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $table = 'user';

	protected $fillable = ['name', 'email', 'password'];

	protected $appends = ['eater_image', 'status_text', 'user_image_url', 'wallet_amount', 'wallet_currency'];

	public $typeArray = [

		'eater' => 0,
		'store' => 1,
		'driver' => 2,

	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [

		'password', 'remember_token',

	];

	public $statusArray = [
		'inactive' => 0,
		'active' => 1,
		'vehicle_details' => 2,
		'document_details' => 3,
		'pending' => 4,
		'waiting for approval' => 5,
	];

	public $statusTextArray = [
		0 => 'Inactive',
		1 => 'Active',
		2 => 'Vehicle Details',
		3 => 'Document Upload',
		4 => 'Pending',
		5 => 'Waiting for Approval',
	];

	public function delete_data()
 	{
 		if($this->order){
 			$this->order->each(function($order){
	            $order->order_item()->forcedelete();
	            $order->order_delivery()->forcedelete();
	            $order->forcedelete();
	        });
 		}
 		$this->user_payment_method()->forcedelete();

 		if($this->user_address){
	        $this->user_all_address()->forcedelete();
	    }
        $this->forcedelete();
    }

	public function delete_driver_data()
 	{
 		if(count($this->user_all_address)){
	       	 $this->user_all_address()->forcedelete();
	    }
 		$this->user_payment_method()->forcedelete();

 		if(count($this->driver->driver_cancel_history()->get()))
       		$this->driver->driver_cancel_history()->forcedelete();
 		
 		if(count($this->driver->driver_request()->withTrashed()->get()))
       		$this->driver->driver_request()->withTrashed()->forcedelete();
       	
       	if($this->driver)
       		$this->driver->forcedelete();
        $this->forcedelete();
    }


	public function scopeAuth($query) {
		$user_id = JWTAuth::parseToken()->authenticate()->id;
		return $query->where('id', $user_id);
	}

	/**
	 * To check the status
	 */
	public function scopeStatus($query, $status = 'active') {
		$status_value = $this->statusArray[$status];
		return $query->where('status', $status_value);
	}

	public function getStatusTextAttribute() {
		return array_search($this->status, $this->statusArray);
	}
	//status_text_show
	public function getStatusTextShowAttribute() {
		return $this->statusTextArray[$this->status];
	}

	//type_text
	public function getTypeTextAttribute() {
		return array_search($this->type, $this->typeArray);
	}

	/**
	 * To check the type
	 */
	public function scopeType($query, $status = 'eater') {
		$type_value = $this->typeArray[$status];

		return $query->where('type', $type_value);
	}

	/**
	 * To check the type
	 */
	public static function getType($type) {
		$user = new User;
		$type_value = array_flip($user->typeArray);
		return $type_value[$type];
	}

	// Join with store table

	public function store() {
		return $this->belongsTo('App\Models\Store', 'id', 'user_id');
	}



	// Join with penalty table

	public function penalty() {
		return $this->belongsTo('App\Models\Penality', 'id', 'user_id');
	}

	// Join with driver table

	public function driver() {
		return $this->belongsTo('App\Models\Driver', 'id', 'user_id');
	}
	public function payout_preference() {
		return $this->belongsTo('App\Models\PayoutPreference', 'id', 'user_id');
	}

	public function payout() {
		return $this->hasMany('App\Models\Payout', 'user_id', 'id');
	}

	public function getEaterImageAttribute() {
		return $this->user_image_url;
	}
	//store_id
	public function getStoreIdAttribute() {
		return $this->store()->first()->id;
	} //first_name
	public function getFirstNameAttribute() {
		$name = explode(' ', $this->attributes['name']);
		if (count($name)) {
			return $name[0];
		}

	}
	//last_name
	public function getLastNameAttribute() {
		$name = explode(' ', $this->attributes['name']);
		if (count($name) > 1) {
			return $name[1];
		}

	}

	public function user_image() {
		return $this->belongsTo('App\Models\File', 'id', 'source_id')->type('eater_image');
	}

	// get date of birth
	public function getDobArrayAttribute() {
		$dob_array = explode('-', @$this->attributes['date_of_birth']);
		return $dob_array;
	}

	public function getUserImageUrlAttribute() {
		if ($this->user_image) {
			return $this->user_image->image_name;
		}

		return getEmptyUserImageUrl();
	}

	public function getWalletAmountAttribute() {
		$wallet = $this->wallet()->first();

		if ($wallet) {
			return $wallet->amount;
		}

		return 0;

	}
	public function getPayoutIdAttribute() {
		$payout = $this->payout_preference()->where('default', 'yes')->first();

		if ($payout) {
			return $payout->paypal_email;
		}

		return '';

	}

	public function getWalletCurrencyAttribute() {
		$wallet = $this->wallet()->first();

		if ($wallet) {
			return $wallet->currency_code;
		}

		return DEFAULT_CURRENCY;

	}

	//store_total_paid_amount
	public function getTotalPaidAmountAttribute() {
		$amount = $this->payout()->whereStatus(1)->get();

		if (count($amount) > 0) {
			return $amount->sum('amount');
		}

		return '0';

	}
	//total_earnings_amount
	public function getTotalEarningsAmountAttribute() {

		$amount = Payout::where('user_id', $this->attributes['id'])->with('order')
			->whereHas('order', function ($query) {
				$query->history();
			})->get();
		if (count($amount) > 0) {
			return $amount->sum('amount');
		}

		return '0';

	}

	public function wallet() {
		return $this->belongsTo('App\Models\Wallet', 'id', 'user_id');
	}
	public function user_payment_method() {
		return $this->belongsTo('App\Models\UserPaymentMethod', 'id', 'user_id');
	}
	public function user_address() {
		return $this->belongsTo('App\Models\UserAddress', 'id', 'user_id')->default();
	}
	public function user_all_address() {
		return $this->hasMany('App\Models\UserAddress', 'user_id', 'id');
	}
	public function order() {
		return $this->hasMany('App\Models\Order', 'user_id', 'id');
	}

	public function scopeLocation($query) {

		$user_id = @Auth::user()->id;
		$user_city = UserAddress::where('user_id', $user_id)->where('default', 1)->first()->city;

		return $query->whereHas(
			'user_address',
			function ($query) use ($user_city) {
				$query->where('city', $user_city);
			}
		);
	}

	public function getCurrencyCodeAttribute() {
		return site_setting("default_currency");
	}

	public function country() {
		return $this->belongsTo('App\Models\Country', 'country_code', 'id');
	}
}
