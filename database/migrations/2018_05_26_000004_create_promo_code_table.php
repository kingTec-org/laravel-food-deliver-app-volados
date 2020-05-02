<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodeTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_schema_table = 'promo_code';

    /**
     * Run the migrations.
     * @table promo_code
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->set_schema_table)) return;
        Schema::create($this->set_schema_table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50)->nullable();
            $table->decimal('price', 7, 2)->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->tinyInteger('promo_type')->nullable();
            $table->decimal('min_price', 7, 2)->nullable();
            $table->decimal('promo_max_price', 7, 2)->nullable();
            $table->tinyInteger('status')->default('1');
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
