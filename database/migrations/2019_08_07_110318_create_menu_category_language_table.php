<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuCategoryLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_category_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_category_id')->unsigned();
            $table->string('name');             
            $table->string('locale',5)->index();
            $table->unique(['menu_category_id','locale']);            
            $table->foreign('menu_category_id')->references('id')->on('menu_category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_category_lang');
    }
}
