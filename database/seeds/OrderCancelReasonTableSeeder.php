<?php

use Illuminate\Database\Seeder;

class OrderCancelReasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_cancel_reason')->delete();
        
        DB::table('order_cancel_reason')->insert(array(
            array('id' => '1','type' => '0','name' => 'Accidently ordered','status' => '1'),
            array('id' => '2','type' => '0','name' => 'Wrongly ordered','status' => '1'),
            array('id' => '3','type' => '1','name' => 'Accidently ordered','status' => '1'),
            array('id' => '4','type' => '1','name' => 'Item is not available','status' => '1'),
            array('id' => '5','type' => '1','name' => 'Shop is closed','status' => '1'),
            array('id' => '6','type' => '1','name' => 'Many orders in queue','status' => '1'),
            array('id' => '7','type' => '2','name' => 'Don\'t charge rider','status' => '1'),
            array('id' => '8','type' => '2','name' => 'Rider isn\'t here','status' => '1'),
            array('id' => '9','type' => '2','name' => 'Wrong address shown','status' => '1'),
            array('id' => '10','type' => '2','name' => 'Too many riders','status' => '1'),
            array('id' => '11','type' => '2','name' => 'Too much luggage','status' => '1'),
            array('id' => '12','type' => '3','name' => 'Rider request to cancel this order','status' => '1'),
            array('id' => '13','type' => '3','name' => 'Store request to cancel this order','status' => '1'),
            array('id' => '14','type' => '3','name' => 'Driver request to cancel this order','status' => '1'),
            array('id' => '15','type' => '3','name' => 'Other','status' => '1'),
        ));
    }
}
