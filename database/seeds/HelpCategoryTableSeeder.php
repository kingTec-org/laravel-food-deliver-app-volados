<?php

use Illuminate\Database\Seeder;

class HelpCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('help_category')->delete();

        DB::table('help_category')->insert([
        ['id' => '1','name' => 'Account and Payment Options','description' => 'Account and Payment Options','status' => '1','type'=>'0'],
  			['id' => '2','name' => 'Guide to Gofer Eats','description' => 'Guide to Gofer Eats','status' => '1','type'=>'0'],
  			['id' => '3','name' => 'Managing Order','description' => 'Managing Order','status' => '1','type'=>'1'],
  			['id' => '4','name' => 'Technical Support','description' => 'Technical Support','status' => '1','type'=>'1'],
  			['id' => '5','name' => 'Signing Up for Uber Eats','description' => 'Signing Up for Uber Eats','status' => '1','type'=>'1'],
        ['id' => '6','name' => 'Account and Payment Support','description' => 'Account and Payment Support','status' => '1','type'=>'1'],
        ['id' => '7','name' => 'Using the app','description' => 'Using the app','status' => '1','type'=>'2'],
        ['id' => '8','name' => 'Account and Payment','description' => 'Account and Payment','status' => '1','type'=>'2'],
        ['id' => '9','name' => 'Signing Up','description' => 'Signing Up','status' => '1','type'=>'2'],
        ]);
    }
}
