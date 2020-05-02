<?php

/**
 * Help Subcategory Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Help Subcategory
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;
class HelpSubCategory extends Model
{

  public $statusArray = [
        'Active' => 1,
        'In Active' => 0,
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'help_subcategory';

    public $timestamps = false;

    public $appends = ['category_name'];



    public static function active_all()
    {
      return HelpCategory::where('Status',1)->get();
    }
    /**
     * To check the status
    */
    public function scopeStatus($query, $status = 'Active') {
      $status_value = $this->statusArray[$status];
      return $query->where('status', $status_value);
    }
    public function translate()
    {
      return $this->hasmany('App\Models\HelpSubCategoryLang','sub_category_id','id');
    }
    //status_text
    public function getStatusTextAttribute(){
        return array_search($this->status, $this->statusArray);
    }

    public function category()
    {
      return $this->belongsTo('App\Models\HelpCategory','category_id','id');
    }

    public function getCategoryNameAttribute()
    {
      return HelpCategory::find($this->attributes['category_id'])->name;
    }

    public function help()
    {
      return $this->hasMany('App\Models\Help','subcategory_id','id');
    }

    public function getHelpSubCategoryAttribute()
    {
        return HelpSubCategoryLang::where('sub_category_id',$this->attributes['id'])->get();
    }
    // name_lang
    public function getNameLangAttribute()
    {
      // Not Translate to admin Panel
      if (Request::segment(1) == ADMIN_URL) {
        return $this->attributes['name'];
      }

      $lan = Session::get('language');
      if($lan=='en')
        return $this->attributes['name'];
      else{ 
         $get = HelpSubCategoryLang::where('sub_category_id',$this->attributes['id'])->where('locale',$lan)->first();
         if($get)
          return $get->name;
        else
          return $this->attributes['name'];
      }
    }
    // description_lang
    public function getDescriptionLangAttribute()
    {
      // Not Translate to admin Panel
      if (Request::segment(1) == ADMIN_URL) {
        return $this->attributes['description'];
      }

      $lan = Session::get('language');
      if($lan=='en')
        return $this->attributes['description'];
      else{ 
         $get = HelpSubCategoryLang::where('sub_category_id',$this->attributes['id'])->where('locale',$lan)->first();
         if($get)
          return $get->description;
        else
          return $this->attributes['description'];
      }
    }
}
