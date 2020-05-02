<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeSliderTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_slider_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('home_slider_id')->unsigned();
            $table->string('title');   
            $table->string('description');   
            $table->string('locale',5)->index();

            $table->unique(['home_slider_id','locale']);
            $table->foreign('home_slider_id')->references('id')->on('home_slider')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_slider_translations');
    }
}
