<?php

/**
 * Help Category Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Help Category
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;
use Request;
class HelpCategory extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'help_category';

    public $timestamps = false;

    public $statusArray = [
        'Active' => 1,
        'In Active' => 0,
    ];
    public $typeArray = [
        'User' => 0,
        'Store' => 1,
        'Driver' => 2,
    ];

    public function translate()
    {
      return $this->hasmany('App\Models\HelpCategoryLang','category_id','id');
    }

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
    /**
     * To check the type
    */
    public function scopeType($query, $status = 'User') {
      $type_value = $this->typeArray[$status];
      return $query->where('type', $type_value);
    }

    //status_text
    public function getStatusTextAttribute(){
        return array_search($this->status, $this->statusArray);
    }
    //type_text
    public function getTypeTextAttribute(){
        return array_search($this->type, $this->typeArray);
    }
    public function subcategory()
    {
      return $this->hasMany('App\Models\HelpSubCategory','category_id','id')->status();
    }

    public function subcategory_limit()
    {
      return $this->hasMany('App\Models\HelpSubCategory','category_id','id')->status()->limit(4);
    }

    public function getHelpCategoryAttribute()
    {
        return HelpCategoryLang::where('category_id',$this->attributes['id'])->get();
    }

    // category_name_lang
    public function getCategoryNameLangAttribute()
    {
      // Not Translate to admin Panel
      if (Request::segment(1) == ADMIN_URL) {
        return $this->attributes['name'];
      }

      $lan = Session::get('language');
      if($lan=='en')
        return $this->attributes['name'];
      else{ 
         $get = HelpCategoryLang::where('category_id',$this->attributes['id'])->where('locale',$lan)->first();
         if($get)
          return $get->name;
        else
          return $this->attributes['name'];
      }
    }
    // category_description_lang
    public function getCategoryDescriptionLangAttribute()
    {
      // Not Translate to admin Panel
      if (Request::segment(1) == ADMIN_URL) {
        return $this->attributes['description'];
      }

      $lan = Session::get('language');
      if($lan=='en')
       return $this->attributes['description'];
      else{ 
         $get = HelpCategoryLang::where('category_id',$this->attributes['id'])->where('locale',$lan)->first();
         if($get)
          return $get->description;
        else
          return $this->attributes['description'];
      }
    }
    
}
