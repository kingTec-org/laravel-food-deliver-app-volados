<?php

use Illuminate\Database\Seeder;

class VehicleTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicle_type')->delete();
    	
        DB::table('vehicle_type')->insert(array(
          array('id' => '1','fees_percentage' => '5','name' => 'Bicycle','status' => '1'),
          array('id' => '2','fees_percentage' => '10','name' => 'Bike','status' => '1'),
          array('id' => '3','fees_percentage' => '15','name' => 'Car','status' => '1')
        ));
    }
}

