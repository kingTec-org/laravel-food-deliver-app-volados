<?php

use Illuminate\Database\Seeder;

class HelpSubCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('help_subcategory')->delete();

        DB::table('help_subcategory')->insert([
        ['id' => '1','category_id' => '1','name' => 'Account Settings','description' => 'Account Settings','status' => '1'],
  			['id' => '2','category_id' => '1','name' => 'Payment','description' => 'Payment','status' => '1'],
  			['id' => '3','category_id' => '2','name' => 'Requesting a trip','description' => 'Requesting a trip','status' => '1'],
  			['id' => '4','category_id' => '2','name' => 'Taking a ride','description' => 'Taking a ride','status' => '1'],
  			['id' => '5','category_id' => '3','name' => 'Managing Orders','description' => 'Managing Orders','status' => '1'],
  			['id' => '6','category_id' => '4','name' => 'Technical Support','description' => 'Technical Support','status' => '1'],
  			['id' => '7','category_id' => '5','name' => 'Signing Up for Gofer Eats','description' => 'Signing Up for Gofer Eats','status' => '1'],
  			['id' => '8','category_id' => '6','name' => 'Updating your account','description' => 'Updating your account','status' => '1'],
  			['id' => '9','category_id' => '6','name' => 'Payment issues','description' => 'Payment issues','status' => '1'],
  			['id' => '10','category_id' => '7','name' => 'Receiving trip requests','description' => 'Receiving trip requests','status' => '1'],
  			['id' => '11','category_id' => '7','name' => 'From pickup to dropoff','description' => 'From pickup to dropoff','status' => '1'],
  			['id' => '12','category_id' => '8','name' => 'Changing account settings','description' => 'Changing account settings','status' => '1'],
  			['id' => '13','category_id' => '8','name' => 'Promotions','description' => 'Promotions','status' => '1'],
  			['id' => '14','category_id' => '9','name' => 'Understanding gofer','description' => 'Understanding gofer','status' => '1'],
  			['id' => '15','category_id' => '9','name' => 'Downloading the app','description' => 'Downloading the app','status' => '1'],
        ]);
    }
}
