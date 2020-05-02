<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'order_item';

    /**
     * Run the migrations.
     * @table order_item
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->foreign('order_id')->references('id')->on('order');
            $table->integer('menu_item_id')->unsigned()->nullable();
            $table->foreign('menu_item_id')->references('id')->on('menu_item');
            $table->decimal('price', 11, 2)->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('modifier_price', 11, 2)->nullable();
            $table->decimal('total_amount', 11, 2)->nullable();
            $table->decimal('tax', 11, 2)->nullable();
            $table->text('notes')->nullable();
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
