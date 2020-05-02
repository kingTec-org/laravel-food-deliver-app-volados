<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTimeTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'menu_time';

    /**
     * Run the migrations.
     * @table menu_time
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->nullable();
            $table->foreign('store_id')->references('id')->on('store');
            $table->integer('menu_id')->unsigned()->nullable();
            $table->foreign('menu_id')->references('id')->on('menu');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->tinyInteger('day')->nullable();
            $table->tinyInteger('status')->nullable();
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
