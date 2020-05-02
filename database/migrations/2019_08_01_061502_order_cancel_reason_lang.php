<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrderCancelReasonLang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('order_cancel_reason_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_cancel_reason_id')->unsigned();
            $table->string('name'); 
            $table->string('locale',5)->index();
            $table->unique(['order_cancel_reason_id','locale']);            
            $table->foreign('order_cancel_reason_id')->references('id')->on('order_cancel_reason')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_cancel_reason_lang');
    }
}
