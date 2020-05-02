<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PromoCodeLang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('promo_code_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('promo_code_id')->unsigned();
            $table->string('code'); 
            $table->string('locale',5)->index();
            $table->unique(['promo_code_id','locale']);            
            $table->foreign('promo_code_id')->references('id')->on('promo_code')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::drop('promo_code_lang');
    }
}
