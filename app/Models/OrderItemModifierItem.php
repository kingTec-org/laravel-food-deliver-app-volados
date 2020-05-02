<?php

/**
 * OrderItemModifierItem Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   OrderItemModifierItem
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DB;

class OrderItemModifierItem extends Model
{    
    protected $table = 'order_item_modifier';
    public $timestamps = false;

    // Join with Menu table
    public function menu_item_modifier_item()
    {
        return $this->belongsTo('App\Models\MenuItemModifierItem', 'menu_item_modifier_item_id', 'id');
    }

    // Join with OrderItemModifierItemItem table
    public function order_item_modifier_item()
    {
        return $this->hasMany('App\Models\OrderItemModifierItemItem', 'order_item_modifier_id', 'id');
    }
}
