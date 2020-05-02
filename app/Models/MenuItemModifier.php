<?php

/**
 * MenuItemModifier Model
 *
 * @package    GoferEats
 * @subpackage Model
 * @category   MenuItemModifier
 * @author     Trioangle Product Team
 * @version    1.0
 * @link       http://trioangle.com
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItemModifier extends Model
{
   

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'menu_item_modifier';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function scopeMenuRelations($query) {
        return $query->with(
            [
                'menu_item_modifier_item'
            ]
        );
    }

    public function scopeStore($query, $store_id) {
        return $query->with(
            [
                'menu_item' => function ($query) use($store_id){
                    $query->store($store_id);
                }
            ]
        )->whereHas('menu_item', function ($query) use($store_id) {
                $query->store($store_id);
            }
        );
    }

    public function menu_item()
    {
        return $this->belongsTo('App\Models\MenuItem', 'menu_item_id', 'id');
    }

    public function menu_item_sub_addon()
    {
        return $this->hasMany('App\Models\MenuItemModifierItem', 'menu_item_modifier_id', 'id');
    }

    public function menu_item_modifier_item()
    {
        return $this->hasMany('App\Models\MenuItemModifierItem', 'menu_item_modifier_id', 'id');
    }
}
