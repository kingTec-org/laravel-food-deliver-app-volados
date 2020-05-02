<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreOfferTable extends Migration {
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $set_schema_table = 'store_offer';

	/**
	 * Run the migrations.
	 * @table store_offer
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
			$table->string('offer_title', 100)->nullable();
			$table->text('offer_description')->nullable();
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->integer('percentage')->nullable();
			$table->decimal('min_price', 7, 2)->nullable();
			$table->decimal('offer_max_price', 7, 2)->nullable();
			$table->string('currency_code', 3)->nullable();
			$table->tinyInteger('status');
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
