<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressTable extends Migration {
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $set_schema_table = 'user_address';

	/**
	 * Run the migrations.
	 * @table user_address
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable($this->set_schema_table)) {
			return;
		}

		Schema::create($this->set_schema_table, function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('user');
			$table->string('address', 100)->nullable();
			$table->string('street', 50)->nullable();
			$table->string('first_address', 100)->nullable();
			$table->string('second_address', 100)->nullable();
			$table->string('city', 50)->nullable();
			$table->string('state', 50)->nullable();
			$table->string('postal_code', 10)->nullable();
			$table->string('country', 20)->nullable();
			$table->string('country_code', 2)->nullable();
			$table->string('latitude', 50)->nullable();
			$table->string('longitude', 50)->nullable();
			$table->tinyInteger('default')->nullable();
			$table->tinyInteger('delivery_options')->nullable();
			$table->tinyInteger('order_type')->nullable();
			$table->timestamp('delivery_time')->nullable();
			$table->string('apartment', 50)->nullable();
			$table->string('delivery_note', 100)->nullable();
			$table->tinyInteger('type')->nullable();
			
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
