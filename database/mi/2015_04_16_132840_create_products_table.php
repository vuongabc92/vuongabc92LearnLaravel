<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('products', function($table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned();
            $table->string('name', 250);
            $table->string('image1', 250);
            $table->string('image2', 250);
            $table->string('image3', 250);
            $table->string('image4', 250);
            $table->string('price', 16);
            $table->string('old_price', 16);
            $table->text('description');
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('products');
    }

}
