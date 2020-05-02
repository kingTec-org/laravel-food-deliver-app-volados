<?php

/**
 * Pages Translations Model
 *
 * @package     Makent
 * @subpackage  Model
 * @category    Pages Translations
 * @author      Trioangle Product Team
 * @version     1.6
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagesTranslations extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'content'];
    
    public function language() {
    	return $this->belongsTo('App\Models\Language','locale','value');
    }
}
