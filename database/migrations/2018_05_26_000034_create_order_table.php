<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration {
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $set_schema_table = 'order';

	/**
	 * Run the migrations.
	 * @table order
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable($this->set_schema_table)) {
			return;
		}

		Schema::create($this->set_schema_table, function (Blueprint $table) {
			$table->increments('id');
			$table->integer('store_id')->unsigned()->nullable();
			$table->foreign('store_id')->references('id')->on('store');
			$table->integer('user_id')->unsigned()->nullable();
			$table->foreign('user_id')->references('id')->on('user');
			$table->integer('driver_id')->nullable()->default(null);
			$table->tinyInteger('recipient')->nullable()->default(null);
			$table->decimal('subtotal', 11, 2)->nullable();
			$table->decimal('offer_percentage', 11, 2)->nullable();
			$table->decimal('offer_amount', 11, 2)->nullable();
			$table->integer('promo_id')->nullable();
			$table->decimal('promo_amount', 11, 2)->nullable();
			$table->decimal('delivery_fee', 11, 2)->nullable();
			$table->decimal('booking_fee', 11, 2)->nullable();
			$table->decimal('store_commision_fee', 11, 2)->nullable();
			$table->decimal('driver_commision_fee', 11, 2)->nullable();
			$table->decimal('tax', 11, 2)->nullable();
			$table->decimal('total_amount', 11, 2)->nullable();
			$table->decimal('wallet_amount', 11, 2)->nullable();
			$table->tinyInteger('payment_type')->nullable();
			$table->decimal('owe_amount', 11, 2)->nullable();
			$table->decimal('applied_owe', 11, 2)->nullable();
			$table->tinyInteger('status')->nullable();
			$table->tinyInteger('payout_status')->nullable();
			$table->string('currency_code', 3)->nullable();
			$table->time('est_preparation_time')->nullable();
			$table->time('est_travel_time')->nullable();
			$table->time('est_delivery_time')->nullable();
			$table->tinyInteger('cancelled_by')->nullable();
			$table->unsignedInteger('cancelled_reason')->nullable();
			$table->text('cancelled_message')->nullable();
			$table->time('delay_min')->nullable();
			$table->text('delay_message')->nullable();
			$table->tinyInteger('schedule_status')->nullable()->default(0);
			$table->tinyInteger('payout_is_create')->nullable()->default(0);
			$table->timestamp('schedule_time')->nullable();
			$table->string('notes', 200)->nullable();
			$table->text('user_notes')->nullable();
			$table->text('store_notes')->nullable();
			$table->text('driver_notes')->nullable();
			$table->timestamp('declined_at')->nullable();
			$table->timestamp('accepted_at')->nullable();
			$table->timestamp('cancelled_at')->nullable();
			$table->timestamp('delivery_at')->nullable();
			$table->timestamp('completed_at')->nullable();
			$table->nullableTimestamps();

		});

		$statement = "ALTER TABLE `order` AUTO_INCREMENT = 10001;";

		DB::unprepared($statement);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		// Schema::dropIfExists($this->set_schema_table);
	}
}
