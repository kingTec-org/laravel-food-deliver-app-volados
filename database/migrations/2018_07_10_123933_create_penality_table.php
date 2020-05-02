<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenalityTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {

		Schema::create('penality', function (Blueprint $table) {

			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user');
			$table->decimal('amount', 11, 2)->nullable();
			$table->decimal('paid_amount', 11, 2)->nullable();
			$table->decimal('remaining_amount', 11, 2)->nullable();
			$table->char('currency_code', 3)->nullable();
			$table->timestamps();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('penality');
	}
}
