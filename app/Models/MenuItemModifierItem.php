<?php

/**
 * MenuItemModifierItem Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   MenuItemModifierItem
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemModifierItem extends Model
{
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $table = 'menu_item_modifier_item';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function scopeVisible($query, $visible = 1)
    {
        return $query->where('is_visible', $visible);
    }

    public function scopeStore($query, $store_id)
    {
        return $query->with(
            [
                'menu_item_modifier' => function ($query) use ($store_id) {
                    $query->store($store_id);
                }
            ]
        )->whereHas(
            'menu_item_modifier',
            function ($query) use ($store_id) {
                $query->store($store_id);
            }
        );
    }

    public function menu_item_modifier()
    {
        return $this->belongsTo('App\Models\MenuItemModifier', 'menu_item_modifier_id', 'id');
    }
}
