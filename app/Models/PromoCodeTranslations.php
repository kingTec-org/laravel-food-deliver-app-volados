<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PromoCodeTranslations extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'promo_code_lang';

    public $timestamps = false;

    protected $fillable = ['code'];

    public function language() {
        return $this->belongsTo('App\Models\Language','locale','value');
    }
}
