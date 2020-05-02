<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'menu_item';

    /**
     * Run the migrations.
     * @table menu_item
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->nullable();
            $table->integer('menu_category_id')->unsigned()->nullable();
            $table->foreign('menu_category_id')->references('id')->on('menu_category');
            $table->string('name', 100)->nullable();
            $table->decimal('price', 11, 2)->nullable();
            $table->text('description')->nullable();
            $table->integer('tax_percentage')->nullable();
            $table->tinyInteger('is_visible')->default(1);
            $table->tinyInteger('type')->nullable(); // veg or non veg
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
