<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class FoodReceiverTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('food_receiver')->delete();
      
      DB::table('food_receiver')->insert(array(
        array('id' => '1','created_at' => NULL,'updated_at' => NULL,'name' => 'Spouse / Housemate'),
        array('id' => '2','created_at' => NULL,'updated_at' => NULL,'name' => 'Doorman'),
        array('id' => '3','created_at' => NULL,'updated_at' => NULL,'name' => 'Concierge'),
        array('id' => '4','created_at' => NULL,'updated_at' => NULL,'name' => 'Someone Else'),
        array('id' => '5','created_at' => NULL,'updated_at' => NULL,'name' => 'Left package in a safe location'),
        array('id' => '6','created_at' => NULL,'updated_at' => NULL,'name' => 'Unable to deliver'),
        array('id' => '7','created_at' => NULL,'updated_at' => NULL,'name' => 'werwerwe')
      ));   
    }
}
