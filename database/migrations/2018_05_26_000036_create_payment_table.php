<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'payment';

    /**
     * Run the migrations.
     * @table payment
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user');

            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('order');

            $table->string('transaction_id', 100)->nullable();
            $table->decimal('amount', 7, 2)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->set_schema_table);
     }
}
