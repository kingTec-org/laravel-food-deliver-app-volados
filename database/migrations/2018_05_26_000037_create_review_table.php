<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewTable extends Migration {
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $set_schema_table = 'review';

	/**
	 * Run the migrations.
	 * @table review
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
			$table->tinyInteger('type')->nullable();
			$table->unsignedInteger('reviewer_id')->nullable();
			$table->unsignedInteger('reviewee_id')->nullable();
			$table->tinyInteger('is_thumbs')->nullable();
			$table->text('comments')->nullable();
			$table->decimal('rating', 11, 2)->nullable();
			$table->integer('order_item_id')->nullable();
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
