<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemModifierTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'menu_item_modifier';

    /**
     * Run the migrations.
     * @table menu_item_modifier
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_item_id')->unsigned()->nullable();
            $table->foreign('menu_item_id')->references('id')->on('menu_item');
            $table->string('name', 100)->nullable();
            $table->integer('count_type')->nullable();
            $table->integer('min_count')->nullable();
            $table->integer('max_count')->nullable();
            $table->tinyInteger('is_required')->nullable();
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
