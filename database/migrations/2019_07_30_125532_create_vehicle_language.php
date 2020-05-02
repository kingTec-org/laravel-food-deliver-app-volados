<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleLanguage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_type_id')->unsigned();
            $table->string('name'); 
            $table->string('locale',5)->index();
            $table->unique(['vehicle_type_id','locale']);            
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_type')->onDelete('cascade');
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vehicle_lang');
    }
}
