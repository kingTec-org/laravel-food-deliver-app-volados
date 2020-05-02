<?php

/**
 * Help Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Help
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use Session;
class Help extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'help';
    use Translatable;

    public $appends = ['category_name', 'subcategory_name'];
    public $translatedAttributes = ['name', 'description'];
    public $statusArray = [
        'Active' => 1,
        'In Active' => 0,
    ];

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

    public function getUpdatedAtAttribute(){
        return date('d-m-Y'.' H:i:s',strtotime($this->attributes['updated_at']));
    }
    /**
     * To check the status
    */
    public function scopeStatus($query, $status = 'Active') {
      $status_value = $this->statusArray[$status];
      return $query->where('status', $status_value);
    }
    //status_text
    public function getStatusTextAttribute(){
        return array_search($this->status, $this->statusArray);
    }

    // question_lang
    public function getQuestionLangAttribute()
    {

      $lan = Session::get('language');
      if($lan=='en')
        return $this->attributes['question'];
      else{ 
         $get = HelpTranslations::where('help_id',$this->attributes['id'])->where('locale',$lan)->first();
         if($get)
          return $get->name;
        else
          return $this->attributes['question'];
      }
    }
// answer_lang
    public function getAnswerLangAttribute()
    {
      $lan = Session::get('language');
      // dd($lan);
      if($lan=='en')
        return $this->attributes['answer'];
      else{ 
         $get = HelpTranslations::where('help_id',$this->attributes['id'])->where('locale',$lan)->first();
         // dd($get);
         if($get)
          return $get->description;
        else
          return $this->attributes['answer'];
      }
    }

    public function category()
    {
      return $this->belongsTo('App\Models\HelpCategory','category_id','id');
    }

    public function subcategory()
    {
      return $this->belongsTo('App\Models\HelpSubCategory','subcategory_id','id');
    }


    public function scopeSubcategory_($query, $id)
    {
      return $query->where('subcategory_id', $id);
    }

    public function getCategoryNameAttribute()
    {
      return HelpCategory::find($this->attributes['category_id'])->name;
    }

    public function getSubcategoryNameAttribute()
    {

      return @HelpSubCategory::find($this->attributes['subcategory_id'])->name_lang;
    }
}
