<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorePreparationTimeTable extends Migration {
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $set_schema_table = 'store_preparation_time';

	/**
	 * Run the migrations.
	 * @table store_preparation_time
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
			$table->time('min_time')->nullable();
			$table->time('max_time')->nullable();
			$table->tinyInteger('day')->nullable();
			$table->time('from_time')->nullable();
			$table->time('to_time')->nullable();
			$table->tinyInteger('status')->nullable();
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
