<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run() {
		$this->call([
			AdminTableSeeder::class,
			TimezoneTableSeeder::class,
			SiteSettingTableSeeder::class,
			VehicleTypeTableSeeder::class,
			FoodReceiverTableSeeder::class,
			OrderCancelReasonTableSeeder::class,
			IssueTypeTableSeeder::class,
			HomeSliderTableSeeder::class,
			CategoryTableSeeder::class,
			LanguageTableSeeder::class,
			CountryTableSeeder::class,
			StaticPagesTableSeeder::class,
			CurrencyTableSeeder::class,
			FileTypeTableSeeder::class,
			FileTableSeeder::class,
			HelpCategoryTableSeeder::class,
			HelpSubCategoryTableSeeder::class,
			HelpTableSeeder::class,
		]);
	}
}
