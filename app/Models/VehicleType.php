<?php

/**
 * VehicleType Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   VehicleType
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Session;
use Request;
class VehicleType extends Model
{
   
     use Translatable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $timestamps = false;
    protected $table = 'vehicle_type';
    protected $appends = ['vehicle_image'];
    public $statusArray = [
        'inactive'    => 0,
        'active'      => 1,
        
    ];

   
    public $translatedAttributes = ['name'];
    
      public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        if(Request::segment(1) == 'admin') {
            $this->defaultLocale = 'en';
        }
        else {
            $this->defaultLocale = Session::get('language');
        }
    }
    /**
     * To filter based on status
     */
    public function scopeStatus($query, $status='active')
    {
        $status_value = $this->statusArray[$status];
        return $query->where('status', $status_value);
    }

    public function getStatusTextAttribute() {
        return array_search($this->status, $this->statusArray);
    }
    //vehicle_image
    public function getVehicleImageAttribute() {
        $image = File::where('type', 18)->where('source_id', $this->attributes['id'])->first();        
        if ($image) {
            return url(Storage::url('images/vehicle_image/' . $image->name));
        } else {

            return url('images/default_vehicle.jpg');
        }
    }
}
