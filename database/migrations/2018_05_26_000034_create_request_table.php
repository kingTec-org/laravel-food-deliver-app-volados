<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'request';

    /**
     * Run the migrations.
     * @table request
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
            $table->integer('vehicle_id')->unsigned()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicle_type');
            $table->integer('driver_id')->unsigned()->nullable();
            $table->foreign('driver_id')->references('id')->on('driver');
            $table->string('pickup_latitude', 100)->nullable();
            $table->string('pickup_longitude', 100)->nullable();
            $table->string('drop_latitude', 100)->nullable();
            $table->string('drop_longitude', 100)->nullable();
            $table->string('pickup_location', 100)->nullable();
            $table->string('drop_location', 100)->nullable();
            $table->string('trip_path', 100)->nullable();
            $table->string('group_id', 100)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->softDeletes();
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
