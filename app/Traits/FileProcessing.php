<?php

/**
 * File Processing Trait
 *
 * @package     Gofereats
 * @subpackage  File Processing Trait
 * @category    File Upload
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Traits;

use App\Models\File;
use App\Models\FileType;
use Image;
use Storage;

trait FileProcessing {
	/**
	 * $file upload file
	 * $file_save_path File save path
	 * $file_name upload file name
	 * @return \Illuminate\Http\Response
	 */
	public function fileUpload($file, $file_save_path = 'public', $file_name = '') {
		if ($file_name == '') {
			$fullName = $file->getClientOriginalName();
			$ext = $file->getClientOriginalExtension();
			$file_name = explode('.'.$ext,$fullName)[0];

		}
		$check_path = Storage::url($file_save_path);
		if (!Storage::exists($check_path)) {
			Storage::makeDirectory($check_path);
		}
		$extension = '.' . $file->getClientOriginalExtension();
		$file_name = str_slug($file_name, '-') . time() . $extension;
		$path = Storage::putFileAs($file_save_path, $file, $file_name);
		// dd($path);
		return collect(['file_name' => $file_name, 'path' => $path]);

	}

	/**
	 * $type file save type eg:user_profile_picture
	 * $type_id file type id eg:user_id
	 * $file_name file name
	 * $source file save in local or cloud
	 * @return \Illuminate\Http\Response
	 */
	public function fileSave($type, $type_id, $file_name, $source = '1', $is_multiple = false, $update_id = false) {

		$file_type = FileType::where('name', $type)->first();
		if ($file_type) {
			$type = $file_type->id;
			$file = File::where('type', $type)->where('source_id', $type_id)->first();
			if ($update_id) {
				$file = File::find($update_id);
			}

			if ($is_multiple) {
				$file_id = $this->save_new_files($type, $type_id, $file_name, $source);
			} else {
				if ($file) {
					//delete old file
					/*if ($type=='1')
			   			Storage::delete('public/images/site_setting/'.$file->name);*/

					$file->name = $file_name;
					$file->source = $source;
					$file->save();
					$file_id = $file->id;
				} else {
					$file_id = $this->save_new_files($type, $type_id, $file_name, $source);
				}

			}

			return $file_id;
		} else {
			return false;
		}

	}

	public function fileCrop($source_url, $crop_width = 225, $crop_height = 225, $destination_url = '') {
		$source_url = trim($source_url, '/');
		$destination_url = trim($destination_url, '/');
		$image = Image::make($source_url);
		$image_width = $image->width();
		$image_height = $image->height();

		if ($image_width < $crop_width && $crop_width < $crop_height) {
			$image = $image->fit($crop_width, $image_height);
		}if ($image_height < $crop_height && $crop_width > $crop_height) {
			$image = $image->fit($crop_width, $crop_height);
		}

		$primary_cropped_image = $image;
		$croped_image = $primary_cropped_image->fit($crop_width, $crop_height);

		if ($destination_url == '') {
			$source_url_details = pathinfo($source_url);
			$destination_url = @$source_url_details['dirname'] . '/' . @$source_url_details['filename'] . '_' . $crop_width . 'x' . $crop_height . '.' . @$source_url_details['extension'];
		}
		$croped_image->save($destination_url);

		return $destination_url;
	}

	public function fileResize($source_url, $resize_width = 225, $resize_height = 225, $destination_url = '') {
		// dd($source_url);
		$source_url = trim($source_url, '/');
		$destination_url = trim($destination_url, '/');
		$image = Image::make($source_url)->resize($resize_width, $resize_height);

		if ($destination_url == '') {
			$source_url_details = pathinfo($source_url);
			$destination_url = @$source_url_details['dirname'] . '/' . @$source_url_details['filename'] . '_' . $resize_width . 'x' . $resize_height . '.' . @$source_url_details['extension'];
		}
		$image->save($destination_url);

		return $destination_url;
	}

	public function save_new_files($type, $type_id, $file_name, $source) {
		$file = new File;
		$file->type = $type;
		$file->source_id = $type_id;
		$file->name = $file_name;
		$file->source = $source;
		$file->save();

		return $file->id;
	}
}
