<?php

use Illuminate\Database\Seeder;

class FileTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_type')->delete();
    	
        DB::table('file_type')->insert([

            ['id' => '1','name' => 'site_setting'],
            ['id' => '2','name' => 'eater_image'],
            ['id' => '3','name' => 'store_banner'],
            ['id' => '4','name' => 'store_logo'],
            ['id' => '5','name' => 'driver_image'],
            ['id' => '6','name' => 'menu_item_image'],
            ['id' => '7','name' => 'category_image'],
            ['id' => '8','name' => 'driver_licence_front'],
            ['id' => '9','name' => 'driver_licence_back'],
            ['id' => '10','name' => 'driver_registeration_certificate'],
            ['id' => '11','name' => 'driver_insurance'],
            ['id' => '12','name' => 'driver_motor_certiticate'],
            ['id' => '13','name' => 'store_document'],
            ['id' => '14','name' => 'stripe_document'],
            ['id' => '15','name' => 'trip_image'],
            ['id' => '16','name' => 'map_image'],
            ['id' => '17','name' => 'store_home_slider'],
            ['id' => '18','name' => 'vehicle_image'],
            ['id' => '19','name' => 'dietary_icon'],
            ['id' => '20','name' => 'eater_home_slider'],

        	]);
    }
}
