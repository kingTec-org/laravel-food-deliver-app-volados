<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration {
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $set_schema_table = 'user';

	/**
	 * Run the migrations.
	 * @table user
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable($this->set_schema_table)) {
			return;
		}

		Schema::create($this->set_schema_table, function (Blueprint $table) {
			$table->increments('id')->nullable();
			$table->tinyInteger('type')->nullable();
			$table->string('name', 50);
			$table->string('user_first_name', 50)->nullable();
            $table->string('user_last_name', 50)->nullable();
			$table->string('email', 50)->nullable();
			$table->string('password', 100)->nullable();
			$table->date('date_of_birth')->nullable();
			$table->string('mobile_number', 15)->nullable();
			$table->string('country_code')->nullable();
			$table->tinyInteger('device_type')->nullable();
			$table->text('device_id')->nullable();
			$table->string('language',50)->nullable();
			$table->tinyInteger('status')->nullable();
			$table->tinyInteger('mobile_no_verify')->nullable();
			$table->string('otp', 10)->nullable();
			$table->string('remember_token')->nullable();
			$table->nullableTimestamps();
		});
		
		$statement = "ALTER TABLE `user` AUTO_INCREMENT = 10001;";

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
