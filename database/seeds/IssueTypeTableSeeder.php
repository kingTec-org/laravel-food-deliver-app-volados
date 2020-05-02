<?php

use Illuminate\Database\Seeder;

class IssueTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('issue_type')->delete();
        
        DB::table('issue_type')->insert(array(
          array('id' => '1','type_id' => '0','name' => 'Taste','status' => '1'),
          array('id' => '2','type_id' => '0','name' => 'Portion size','status' => '1'),
          array('id' => '3','type_id' => '0','name' => 'Temperature','status' => '1'),
          array('id' => '4','type_id' => '0','name' => 'Presentation','status' => '1'),
          array('id' => '5','type_id' => '1','name' => 'Did\'nt come to door','status' => '1'),
          array('id' => '6','type_id' => '1','name' => 'Package Handling','status' => '1'),
          array('id' => '7','type_id' => '1','name' => 'profesionalism','status' => '1'),
          array('id' => '8','type_id' => '1','name' => 'Late to dropoff','status' => '1'),
          array('id' => '9','type_id' => '2','name' => 'Late for pickup','status' => '1'),
          array('id' => '10','type_id' => '2','name' => 'Delivery item proper','status' => '1'),
          array('id' => '11','type_id' => '2','name' => 'Very quick delivery','status' => '1'),
          array('id' => '12','type_id' => '3','name' => 'No waiting','status' => '1'),
          array('id' => '13','type_id' => '4','name' => 'Inaccurate ETA','status' => '1'),
          array('id' => '14','type_id' => '4','name' => 'Did\'nt receive item','status' => '1'),
          array('id' => '15','type_id' => '4','name' => 'Over waiting time','status' => '1'),
          array('id' => '16','type_id' => '4','name' => 'No proper response','status' => '1')
        ));
    }
}
