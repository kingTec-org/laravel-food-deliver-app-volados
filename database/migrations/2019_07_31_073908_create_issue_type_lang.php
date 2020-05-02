<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueTypeLang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('issue_type_lang', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('issue_type_id')->unsigned();
            $table->string('name'); 
            $table->string('locale',5)->index();
            $table->unique(['issue_type_id','locale']);            
            $table->foreign('issue_type_id')->references('id')->on('issue_type')->onDelete('cascade');
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('issue_type_lang');
    }
}
