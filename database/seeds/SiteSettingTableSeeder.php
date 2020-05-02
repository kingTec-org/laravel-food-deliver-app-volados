<?php

use Illuminate\Database\Seeder;

class SiteSettingTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table('site_setting')->delete();

		DB::table('site_setting')->insert(array(
			array('id' => '1', 'name' => 'site_name', 'value' => 'GoferGrocery'),
			array('id' => '2', 'name' => 'site_url', 'value' => '/gofergrocery'),
			array('id' => '3', 'name' => 'site_date_format', 'value' => 'd-m-Y'),
			array('id' => '4', 'name' => 'site_time_format', 'value' => '12'),
			array('id' => '5', 'name' => 'default_currency', 'value' => 'USD'),
			array('id' => '6', 'name' => 'default_language', 'value' => 'en'),
			array('id' => '7', 'name' => 'version', 'value' => '1.0'),
			array('id' => '8', 'name' => 'join_us_facebook', 'value' => 'https://www.facebook.com/Trioangle.Technologies/'),
			array('id' => '9', 'name' => 'join_us_twitter', 'value' => 'https://twitter.com/trioangle'),
			array('id' => '10', 'name' => 'join_us_youtube', 'value' => 'https://www.youtube.com/channel/UC2EWcEd5dpvGmBh-H4TQ0wg'),
			array('id' => '11', 'name' => 'eater_apple_link', 'value' => ''),
			array('id' => '12', 'name' => 'store_apple_link', 'value' => ''),
			array('id' => '13', 'name' => 'driver_apple_link', 'value' => ''),
			array('id' => '14', 'name' => 'eater_android_link', 'value' => 'https://play.google.com/store/apps/details?id=com.trioangle.gofergroceryuser'),
			array('id' => '15', 'name' => 'store_android_link', 'value' => 'https://play.google.com/store/apps/details?id=com.trioangle.gofergrocerystore'),
			array('id' => '16', 'name' => 'driver_android_link', 'value' => 'https://play.google.com/store/apps/details?id=com.trioangle.gofergrocerydriver'),
			array('id' => '17', 'name' => 'google_api_key', 'value' => 'AIzaSyB6lCQnISdsSUVFdcQYxaHxXXjvKDn9wcs'),
			array('id' => '18', 'name' => 'stripe_publish_key', 'value' => 'pk_test_764boQ9IBVx4RSKjr1Fx2a7W'),
			array('id' => '19', 'name' => 'stripe_secret_key', 'value' => 'sk_test_xaaV9BdpFcTmWaVoU28gwuOm'),
			array('id' => '20', 'name' => 'nexmo_key', 'value' => '8ff1c8ec'),
			array('id' => '21', 'name' => 'nexmo_secret_key', 'value' => '155a3P1Yx3x5P8d7'),
			array('id' => '22', 'name' => 'nexmo_from_number', 'value' => 'Nexmo'),
			array('id' => '23', 'name' => 'delivery_fee_type', 'value' => '1'),
			array('id' => '24', 'name' => 'delivery_fee', 'value' => '10'),
			array('id' => '25', 'name' => 'booking_fee', 'value' => '10'),
			array('id' => '26', 'name' => 'store_commision_fee', 'value' => '10'),
			array('id' => '27', 'name' => 'driver_commision_fee', 'value' => '10'),
			array('id' => '28', 'name' => 'pickup_fare', 'value' => '15'),
			array('id' => '29', 'name' => 'drop_fare', 'value' => '20'),
			array('id' => '30', 'name' => 'distance_fare', 'value' => '3'),
			array('id' => '31', 'name' => 'email_driver', 'value' => 'smtp'),
			array('id' => '32', 'name' => 'email_host', 'value' => 'smtp.gmail.com'),
			array('id' => '33', 'name' => 'email_port', 'value' => '25'),
			array('id' => '34', 'name' => 'email_to_address', 'value' => 'trioangle1@gmail.com'),
			array('id' => '35', 'name' => 'email_from_address', 'value' => 'trioangle1@gmail.com'),
			array('id' => '36', 'name' => 'email_from_name', 'value' => 'GoferEats'),
			array('id' => '37', 'name' => 'email_encryption', 'value' => 'tls'),
			array('id' => '38', 'name' => 'email_user_name', 'value' => 'trioangle1@gmail.com'),
			array('id' => '39', 'name' => 'email_password', 'value' => 'hismljhblilxdusd'),
			array('id' => '40', 'name' => 'email_domain', 'value' => 'sandboxcc51fc42882e46ccbffd90316d4731e7.mailgun.org'),
			array('id' => '41', 'name' => 'email_secret', 'value' => 'key-3160b23116332e595b861f60d77fa720'),
			array('id' => '42', 'name' => 'fcm_server_key', 'value' => 'AIzaSyB2HQAjsOtED0ZoVBFICb8YJtweklpFGs0'),
			array('id' => '43', 'name' => 'fcm_sender_id', 'value' => '157445205846'),
			array('id' => '44', 'name' => 'site_support_phone', 'value' => '1800-00-2568'),
			array('id' => '45', 'name' => 'store_km', 'value' => '10'),
			array('id' => '46', 'name' => 'driver_km', 'value' => '10'),
			array('id' => '47', 'name' => 'admin_prefix', 'value' => 'admin'),
			array('id' => '48', 'name' => 'site_translation_name', 'value' => ''),
			array('id' => '49', 'name' => 'locale', 'value' => ''),
			array('id' => '50', 'name' => 'site_pt_translation', 'value' => ''),
			array('id' => '51', 'name' => 'ios_link', 'value' => 'https://apps.apple.com/us/app/gofergrocery/id1480238189?ls=1'),
		));
	}
}
