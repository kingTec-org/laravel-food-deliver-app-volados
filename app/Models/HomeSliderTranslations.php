<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeSliderTranslations extends Model
{
	protected $table = 'home_slider_translations';
    public $timestamps = false;
    protected $fillable = ['title', 'description'];

    public function language() {
    	return $this->belongsTo('App\Models\Language','locale','value');
    }
}
