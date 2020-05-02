<?php

/**
 * StorePreparationTime Model
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

class StorePreparationTime extends Model
{
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $table = 'store_preparation_time';
     public $timestamps =false;
  
  public function getMaxTimeAttribute()
    {
        return  convert_minutes($this->attributes['max_time']);
    }

}
