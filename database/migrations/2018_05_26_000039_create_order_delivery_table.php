<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDeliveryTable extends Migration {
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $set_schema_table = 'order_delivery';

	/**
	 * Run the migrations.
	 * @table order_delivery
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable($this->set_schema_table)) {
			return;
		}

		Schema::create($this->set_schema_table, function (Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id')->unsigned()->nullable();
			$table->foreign('order_id')->references('id')->on('order');
			$table->integer('request_id')->unsigned()->nullable();
			$table->foreign('request_id')->references('id')->on('request');
			$table->integer('vehicle_id')->unsigned()->nullable();
			$table->foreign('vehicle_id')->references('id')->on('vehicle_type');
			$table->integer('driver_id')->unsigned()->nullable();
			$table->foreign('driver_id')->references('id')->on('driver');
			$table->string('pickup_latitude', 100)->nullable();
			$table->string('pickup_longitude', 100)->nullable();
			$table->string('drop_latitude', 100)->nullable();
			$table->string('drop_longitude', 100)->nullable();
			$table->string('pickup_location', 200)->nullable();
			$table->string('drop_location', 200)->nullable();
			$table->string('pickup_distance', 10)->nullable();
			$table->string('drop_distance', 10)->nullable();
			$table->string('est_distance', 10)->nullable();
			$table->string('trip_path', 100)->nullable();
			$table->tinyInteger('fee_type')->nullable();
			$table->decimal('pickup_fare', 11, 2)->nullable();
			$table->decimal('drop_fare', 11, 2)->nullable();
			$table->decimal('distance_fare', 11, 2)->nullable();
			$table->decimal('total_fare', 11, 2)->nullable();
			$table->string('duration', 50)->nullable();
			$table->tinyInteger('status')->nullable();
			$table->timestamp('confirmed_at')->nullable();
			$table->timestamp('declined_at')->nullable();
			$table->timestamp('started_at')->nullable();
			$table->timestamp('delivery_at')->nullable();
			$table->timestamp('cancelled_at')->nullable();
			$table->nullableTimestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists($this->set_schema_table);
	}
}
