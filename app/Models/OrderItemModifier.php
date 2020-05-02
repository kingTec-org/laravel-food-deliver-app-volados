<?php

/**
 * OrderItemModifier Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   OrderItemModifier
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;

class OrderItemModifier extends Model
{
    
    protected $table = 'order_item_modifier';
    public $timestamps = false;

    // Join with Menu table
    public function menu_item_modifier()
    {
        return $this->belongsTo('App\Models\MenuItemModifier', 'modifier_id', 'id');
    }
    
    // Join with OrderItemModifierItem table
    public function order_item_modifier_item()
    {
        return $this->hasMany('App\Models\OrderItemModifierItem', 'order_id', 'id');
    }
}
