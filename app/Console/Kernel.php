<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule) {
		// $schedule->command('inspire')
		//          ->hourly();

		$schedule->call('App\Http\Controllers\Api\PaymentController@cron_refund')->everyMinute();
		$schedule->call('App\Http\Controllers\Api\StoreController@remainScheduleOrder')->everyMinute();
		$schedule->call('App\Http\Controllers\Api\StoreController@beforeSevenMin')->everyMinute();
		$schedule->command('queue:work --tries=3 --once')->cron('* * * * * ');

	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands() {
		$this->load(__DIR__ . '/Commands');

		require base_path('routes/console.php');
	}
}
