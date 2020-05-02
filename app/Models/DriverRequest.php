<?php

/**
 * DriverRequest Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   DriverRequest
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;

class DriverRequest extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    use SoftDeletes;
    protected $table = 'request';

    // protected $appends = ['accepted_count','pending_count','cancelled_count','total_count','date_time','total_fare','payment_status','currency_code','currency_symbol'];
    protected $dates = ['deleted_at'];

    public $statusArray = [
        'pending'   => 0,
        'accepted'  => 1,
        'cancelled' => 2
    ];

    /**
     * To filter status
     */
    public function scopeStatus($query, $status = ['accepted'])
    {
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
    public function scopeOrderId($query, $order_id = [])
    {
        return $query->whereIn('order_id', $order_id);
    }

    /**
     * To filter groupId
     */
    public function scopeGroupId($query, $group_id = [])
    {
        return $query->whereIn('group_id', $group_id);
    }


    // Join with users table
    public function users()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    // Join with driver table
    public function driver()
    {
        return $this->belongsTo('App\Models\User', 'driver_id', 'id');
    }

    // Join with profile_picture table
    public function profile_picture()
    {
        return $this->belongsTo('App\Models\ProfilePicture', 'user_id', 'user_id');
    }

    public function getStatusTextAttribute()
    {
        return array_search($this->status, $this->statusArray);
    }
    
    public function getAcceptedTripsAttribute()
    {
        $trips = $this->trips()->first();
        $accpted_request = Request::where('group_id', $this->attributes['group_id'])->where('status', 'Accepted')->first();
        if ($accpted_request) {
            $trips = Trips::where('request_id', $accpted_request->id)->first();
        }
        return $trips;
    }
    
    //get user Accepted count
    public function getAcceptedCountAttribute()
    {
        return Request::where('driver_id', $this->attributes['driver_id'])->where('status', 'Accepted')->count();
    }
    //get user Pending count
    public function getPendingCountAttribute()
    {
        return Request::where('driver_id', $this->attributes['driver_id'])->where('status', 'Pending')->count();
    }
    //get user Cancelled count
    public function getCancelledCountAttribute()
    {
        return Request::where('driver_id', $this->attributes['driver_id'])->where('status', 'Cancelled')->count();
    }
    
    //get user Total count
    public function getTotalCountAttribute()
    {
        return Request::where('driver_id', $this->attributes['driver_id'])->count();
    }

    //get trip total fare
    public function getTotalFareAttribute()
    {
        $trips= Trips::where('request_id', $this->attributes['id']);
        if ($trips->count()) {
            return number_format(($trips->get()->first()->total_fare), 2, '.', '');
        } else {
            return "N/A";
        }
    }

    //get trip payment status
    public function getPaymentStatusAttribute()
    {
        $trips= Trips::where('request_id', $this->attributes['id']);
        if ($trips->count()) {
            return @$trips->get()->first()->payment_status;
        } else {
            return "Not Paid";
        }
    }

    //get trip currency code
    public function getCurrencyCodeAttribute()
    {
        $trips= Trips::where('request_id', $this->attributes['id']);
        if ($trips->count()) {
            return  @$trips->get()->first()->currency_code;
        } else {
            return DEFAULT_CURRENCY;
        }
    }

    //get trip currency code
    public function getCurrencySymbolAttribute()
    {
        $trips= Trips::where('request_id', $this->attributes['id']);
        if ($trips->count()) {
            $code =  @$trips->get()->first()->currency_code;

            return Currency::where('code', $code)->first()->symbol;
        } else {
            return "$";
        }
    }

    public function getDateTimeAttribute()
    {
        $full = false;

        $now = new DateTime;
        $ago = new DateTime($this->attributes['updated_at']);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
