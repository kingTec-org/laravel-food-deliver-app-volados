<?php

/**
 * StoreCategory Model
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
use DateTime;
use DB;
class StoreCategory extends Model
{
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $table = 'store_category';

    protected $appends = ['category_name'];

    public $timestamps =false; 



    // Join with category table

    public function category()
    {
        return $this->belongsTo('App\Models\Category','category_id','id');
    }

  	public function getCategoryNameAttribute() {

  		return $this->category->name;
        
  	}
  

}
