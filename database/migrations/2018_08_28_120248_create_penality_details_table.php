<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenalityDetailsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('penality_details', function (Blueprint $table) {

			$table->increments('id');
			$table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('order');
			$table->decimal('store_penality', 11, 2);
			$table->decimal('user_penality', 11, 2);
			$table->decimal('driver_penality', 11, 2);
			$table->tinyInteger('is_store_penality')->default(0);
			$table->tinyInteger('is_user_penality')->default(0);
			$table->tinyInteger('is_driver_penality')->default(0);
			$table->decimal('previous_store_penality', 11, 2);
			$table->decimal('previous_user_penality', 11, 2);
			$table->decimal('previous_driver_penality', 11, 2);
			$table->timestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('penality_details');
	}
}
