<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDriverTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'driver';

    /**
     * Run the migrations.
     * @table driver
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user');
            $table->integer('vehicle_type')->unsigned()->nullable();
            $table->foreign('vehicle_type')->references('id')->on('vehicle_type');
            $table->string('vehicle_name', 100)->nullable();
            $table->string('vehicle_number', 100)->nullable();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->tinyInteger('status')->nullable();
            
           
        });
        
        $statement = "ALTER TABLE `driver` AUTO_INCREMENT = 10001;";

        DB::unprepared($statement);
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
