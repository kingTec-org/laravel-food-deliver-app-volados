<?php
/**
 * SiteSettingsController
 *
 * @package    GoferEats
 * @subpackage  Controller
 * @category    SiteSettingss
 * @author      Trioangle Product Team
 * @version     1.0
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSettings;
use App\Models\Currency;
use App\Traits\FileProcessing;
use Illuminate\Http\Request;
use Storage;
use Validator;

class SiteSettingController extends Controller {
	use FileProcessing;

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Manage site setting
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function site_setting(Request $request) {
		if ($request->getMethod() == 'GET') {
			$this->view_data['tab'] = $request->tab ?: 'site_setting';
			$this->view_data['form_name'] = trans('admin_messages.site_setting');
			$data['currency'] = Currency::where('status','Active')->pluck('id', 'code');
			return view('admin/site_setting', $this->view_data);
		} else {
			$submit = $request->submit;
			if ($submit == 'site_setting') {

				$rules = array(
					'site_name' => 'required',
					'version' => 'required',
					'store_km' => 'required',
					'driver_km' => 'required',
					'site_support_phone' => 'required',
				);

				// Add Admin User Validation Custom Names
				$niceNames = array(
					'site_name' => trans('admin_messages.site_name'),
					'default_currency' => trans('admin_messages.default_currency'),
					'version' => trans('admin_messages.version'),
					'store_km' => trans('admin_messages.store_km'),
					'driver_km' => trans('admin_messages.driver_km'),
					'site_support_phone' => trans('admin_messages.site_support_phone'),
				);
			} elseif ($submit == 'site_images') {

				$rules = array(
					'site_logo' => 'image|mimes:jpg,png,jpeg,gif',
					'email_logo' => 'image|mimes:jpg,png,jpeg,gif',
					'site_favicon' => 'image|mimes:jpg,png,jpeg,gif',
					'store_logo' => 'image|mimes:jpg,png,jpeg,gif',
					'footer_logo' => 'image|mimes:jpg,png,jpeg,gif',
					'app_logo' => 'image|mimes:jpg,png,jpeg,gif',
					'driver_logo' => 'image|mimes:jpg,png,jpeg,gif',
					'driver_white_logo' => 'image|mimes:jpg,png,jpeg,gif',
					'eater_home_image' => 'image|mimes:jpg,png,jpeg,gif',
				);

				// Add Admin User Validation Custom Names
				$niceNames = array(
					'site_logo' => trans('admin_messages.site_logo'),
					'email_logo' => trans('admin_messages.email_logo'),
					'site_favicon' => trans('admin_messages.site_favIcon'),
					'store_logo' => trans('admin_messages.store_logo'),
					'footer_logo' => trans('admin_messages.footer_logo'),
					'app_logo' => trans('admin_messages.app_logo'),
					'driver_logo' => trans('admin_messages.driver_logo'),
					'driver_white_logo' => trans('admin_messages.driver_white_logo'),
					'eater_home_image' => trans('admin_messages.user_home_image'),
				);
			} elseif ($submit == 'join_us') {

				$rules = array(

					'eater_android_link' => 'required',
					'store_android_link' => 'required',
					'driver_android_link' => 'required',
					'ios_link' => 'required',
				);

				// Add Admin User Validation Custom Names
				$niceNames = array(

					'eater_android_link' => trans('admin_messages.user_android_link'),
					'store_android_link' => trans('admin_messages.store_android_link'),
					'driver_android_link' => trans('admin_messages.driver_android_link'),
					'ios_link' => trans('admin_messages.ios_link'),
				);
			} elseif ($submit == 'fees_manage') {

				$rules = array(
					'delivery_fee_type' => 'required',
					'delivery_fee' => 'required|numeric|max:100',
					'booking_fee' => 'required|numeric|max:100',
					'store_commision_fee' => 'required|numeric|max:100',
					'driver_commision_fee' => 'required|numeric|max:100',
					'pickup_fare' => 'required|numeric|max:100',
					'drop_fare' => 'required|numeric|max:100',
					'distance_fare' => 'required|numeric|max:100',
				);

				// Add Admin User Validation Custom Names
				$niceNames = array(
					'service_fee' => trans('admin_messages.service_fee_percentage'),
				);
			} elseif ($submit == 'api_credentials') {

				$rules = array(
					'fcm_server_key' => 'required',
					'fcm_sender_id' => 'required',
					'google_api_key' => 'required',
					'nexmo_key' => 'required',
					'nexmo_secret_key' => 'required',
					'nexmo_from_number' => 'required',
				);

				// Add Admin User Validation Custom Names
				$niceNames = array(
					'fcm_server_key' => trans('admin_messages.fcm_server_key'),
					'fcm_sender_id' => trans('admin_messages.fcm_sender_id'),
					'google_api_key' => trans('admin_messages.google_api_key'),
					'nexmo_key' => trans('admin_messages.nexmo_key'),
					'nexmo_secret_key' => trans('admin_messages.nexmo_secret_key'),
					'nexmo_from_number' => trans('admin_messages.nexmo_from_number'),
				);
			} elseif ($submit == 'payment_gateway') {

				$rules = array(
					'stripe_publish_key' => 'required',
					'stripe_secret_key' => 'required',
				);
				$niceNames = array(
					'stripe_publish_key' => trans('admin_messages.stripe_publish_key'),
					'stripe_secret_key' => trans('admin_messages.stripe_secret_key'),
				);

			} elseif ($submit == 'email_setting') {

				$rules = array(
					'email_driver' => 'required',
					'email_host' => 'required',
					'email_port' => 'required',
					'email_from_address' => 'required|email',
					'email_to_address' => 'required|email',
					'email_from_name' => 'required',
					'email_encryption' => 'required',
				);
				if ($request->$submit['email_driver'] == 'smtp') {
					$rules = array('email_user_name' => 'required',
						'email_password' => 'required');
				}
				if ($request->$submit['email_driver'] == 'mailgun') {
					$rules['email_domain'] = 'required';
					$rules['eamil_secret'] = 'required';
				}

				// Add Admin User Validation Custom Names
				$niceNames = array(
					'email_driver' => trans('admin_messages.email_driver'),
					'email_host' => trans('admin_messages.email_host'),
					'email_port' => trans('admin_messages.email_port'),
					'email_from_address' => trans('admin_messages.email_from_address'),
					'email_to_address' => trans('admin_messages.email_to_address'),
					'email_from_name' => trans('admin_messages.email_from_name'),
					'email_encryption' => trans('admin_messages.email_encryption'),
					'email_user_name' => trans('admin_messages.email_user_name'),
					'email_password' => trans('admin_messages.email_password'),
					'email_domain' => trans('admin_messages.email_domin'),
					'eamil_secret' => trans('admin_messages.email_secret'),
				);
			} else {
				return redirect()->route('admin.site_setting');
			}
			if (!$request->$submit) {
				return redirect()->route('admin.site_setting', ['tab' => $submit]);
			}

			$validator = Validator::make($request->$submit, $rules);
			$validator->setAttributeNames($niceNames);

			if ($validator->fails()) {
				return redirect()->route('admin.site_setting', ['tab' => $submit])->withErrors($validator)->withInput(); // Form calling with Errors and Input values
			} else {
				foreach ($request->$submit as $key => $value) {

					if ($submit == 'site_images' && $key == 'site_logo') {
						$file = $request->file('site_images')['site_logo'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '1', $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						// $this->fileResize($orginal_path, get_image_size('site_logo')['width'], get_image_size('site_logo')['height'], $orginal_path);
					} elseif ($submit == 'site_images' && $key == 'site_favicon') {
						$file = $request->file('site_images')['site_favicon'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '2', $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						// $this->fileResize($orginal_path, get_image_size('site_favicon')['width'], get_image_size('site_favicon')['height'], $orginal_path);
					} elseif ($submit == 'site_images' && $key == 'store_logo') {
						$file = $request->file('site_images')['store_logo'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '3', $file_path['file_name'], '1');
						// $orginal_path = Storage::url($file_path['path']);
						// $this->fileCrop($orginal_path, get_image_size('store_logo')['width'], get_image_size('store_logo')['height'], $orginal_path);
					} elseif ($submit == 'site_images' && $key == 'email_logo') {
						$file = $request->file('site_images')['email_logo'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '4', $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						$this->fileCrop($orginal_path, get_image_size('email_logo')['width'], get_image_size('email_logo')['height'], $orginal_path);
					} elseif ($submit == 'site_images' && $key == 'footer_logo') {
						$file = $request->file('site_images')['footer_logo'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '5', $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						// $this->fileCrop($orginal_path, get_image_size('footer_logo')['width'], get_image_size('footer_logo')['height'], $orginal_path);
					} elseif ($submit == 'site_images' && $key == 'app_logo') {
						$file = $request->file('site_images')['app_logo'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '6', $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						// $this->fileCrop($orginal_path, get_image_size('app_logo')['width'], get_image_size('app_logo')['height'], $orginal_path);
					} elseif ($submit == 'site_images' && $key == 'driver_logo') {
						$file = $request->file('site_images')['driver_logo'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '7', $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						// $this->fileCrop($orginal_path, get_image_size('driver_logo')['width'], get_image_size('driver_logo')['height'], $orginal_path);
					} elseif ($submit == 'site_images' && $key == 'driver_white_logo') {
						$file = $request->file('site_images')['driver_white_logo'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '8', $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						// $this->fileCrop($orginal_path, get_image_size('driver_white_logo')['width'], get_image_size('driver_white_logo')['height'], $orginal_path);
					} elseif ($submit == 'site_images' && $key == 'eater_home_image') {
						$file = $request->file('site_images')['eater_home_image'];

						$file_path = $this->fileUpload($file, 'public/images/site_setting');

						$this->fileSave('site_setting', '9', $file_path['file_name'], '1');
						$orginal_path = Storage::url($file_path['path']);
						// $this->fileCrop($orginal_path, get_image_size('eater_home_image')['width'], get_image_size('driver_white_logo')['height'], $orginal_path);
					} else {

						SiteSettings::where('name', $key)->update(['value' => $value]);
					}

				}

				flash_message('success', trans('admin_messages.updated_successfully'));
				return redirect()->route('admin.site_setting', ['tab' => $submit]);
			}

		}
	}

}
