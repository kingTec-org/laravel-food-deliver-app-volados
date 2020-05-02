<?php

/**
 * Store Document Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Store Document
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreDocument extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */

	protected $table = 'store_document';

	protected $appends = ['document_file'];

	public $timestamps = false;

	public function file() {
		return $this->belongsTo('App\Models\File', 'document_id', 'id');
	}

	//document_file
	public function getDocumentFileAttribute() {
		if ($this->file()->first()) {
			return $this->file()->first()->store_document;
		} else {
			return sample_image();
		}

	}
}
