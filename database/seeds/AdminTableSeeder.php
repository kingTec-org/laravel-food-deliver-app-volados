<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		DB::table('admin')->delete();

		DB::table('admin')->insert([
			['id' => 1, 'username' => 'admin', 'email' => 'admin@trioangle.com', 'password' => Hash::make('gofergrocery'), 'status' => '1', 'created_at' => date('Y-m-d H:i:s')],
			['id' => 2, 'username' => 'subadmin', 'email' => 'subadmin@trioangle.com', 'password' => Hash::make('subadmin123'), 'status' => '1', 'created_at' => date('Y-m-d H:i:s')],
			['id' => 3, 'username' => 'accountant', 'email' => 'accountant@trioangle.com', 'password' => Hash::make('accountant123'), 'status' => '1', 'created_at' => date('Y-m-d H:i:s')],
		]);

	}
}
