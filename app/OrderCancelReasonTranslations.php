<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderCancelReasonTranslations extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'order_cancel_reason_lang';

    public $timestamps = false;

    protected $fillable = ['cancel_reason'];

    public function language() {
        return $this->belongsTo('App\Models\Language','locale','value');
    }
}
