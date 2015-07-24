<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->string('name', 250);
            $table->text('images');
            $table->string('price', 16);
            $table->string('old_price', 16);
            $table->text('description');
            $table->boolean('is_hide')->default(0);
            $table->boolean('is_trash')->default(0);
            $table->boolean('is_block')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
