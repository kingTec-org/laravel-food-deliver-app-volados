<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverCancelHistoryTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('driver_cancel_history', function (Blueprint $table) {

			$table->increments('id');
			$table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('order');
            $table->integer('driver_id')->unsigned()->nullable();
            $table->foreign('driver_id')->references('id')->on('driver');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('driver_cancel_history');
	}
}
