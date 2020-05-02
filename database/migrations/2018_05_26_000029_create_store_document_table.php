<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreDocumentTable extends Migration {
	/**
	 * Schema table name to migrate
	 * @var string
	 */
	public $set_schema_table = 'store_document';

	/**
	 * Run the migrations.
	 * @table store_document
	 *
	 * @return void
	 */
	public function up() {
		if (Schema::hasTable($this->set_schema_table))
			return;

		Schema::create($this->set_schema_table, function (Blueprint $table) {
			$table->increments('id');
			$table->integer('store_id')->unsigned();
			// $table->foreign('store_id')->references('id')->on('store');
			$table->string('name', 50)->nullable();
			$table->integer('document_id')->nullable();

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
