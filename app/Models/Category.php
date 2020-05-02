<?php

/**
 * Category Model
 *
 * @package     GoferEats
 * @subpackage  Model
 * @category    Category
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;
use Session;
use Request;
class Category extends Model {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	use Translatable;
	protected $table = 'category';

	protected $appends = ['category_image', 'dietary_icon'];

	public $translatedAttributes = ['name','description'];
	public function scopeActive($query) {
		$query->where('status', '1');

	}

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

	public function getCategoryImageAttribute() {

		$category = File::where('type', 7)->where('source_id', $this->attributes['id'])->first();

		// dump($category);

		if ($category) {
			$size = get_image_size('category_image_size')['width'] . 'x' . get_image_size('category_image_size')['height'];
			$name = explode('.', $category->name);
			$file_name = $name[0] . '_' . $size . '.' . @$name[1];
			return url(Storage::url('images/category_image/' . $file_name));
		} else {

			return url('images/category/food-general.jpg');
		}

	}

	//category_status
	public function getCategoryStatusAttribute() {
		return get_status_text($this->attributes['status']);
	}

	//most_popular_status
	public function getMostPopularStatusAttribute() {
		return get_status_yes($this->attributes['most_popular']);
	}
	//is_top_status
	public function getIsTopStatusAttribute() {
		return get_status_yes($this->attributes['is_top']);
	}

	//image
	public function getImageAttribute() {

		$menu_image = File::where('source_id', $this->attributes['id'])->where('type', 7)->first();
		if ($menu_image) {
			$size = get_image_size('category_image_size')['width'] . 'x' . get_image_size('category_image_size')['height'];
			$name = explode('.', $menu_image->name);
			$file_name = $name[0] . '_' . $size . '.' . @$name[1];
			// if(get_current_root()=='')
			return url(Storage::url('images/category_image/' . $file_name));
			/*else
				return url(Storage::url('images/category_image/' .$menu_image->name));*/
		} else {
			return '';
		}
	}

	public function getDietaryIconAttribute() {

		$file = File::where('type', 19)->where('source_id', $this->attributes['id'])->first();

		if ($file) {
			$size = get_image_size('dietary_icon_size')['width'] . 'x' . get_image_size('dietary_icon_size')['height'];
			$name = explode('.', $file->name);
			$file_name = $name[0] . '_' . $size . '.' . @$name[1];
			return url(Storage::url('images/category_image/' . $file_name));
		} else {

			return url('images/diet_default.png');
		}

	}

	public function language_category() {
		return $this->hasMany('App\Models\CategoryTranslations', 'category_id', 'id');
	}

}
