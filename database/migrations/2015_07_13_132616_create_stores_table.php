<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('name', 250);
            $table->integer('category_id')->unsigned();
            $table->string('street', 250)->nullable();
            $table->integer('city_id')->unsigned();
            $table->integer('district_id')->unsigned();
            $table->integer('ward_id')->unsigned();
            $table->string('phone_number', 32)->nullable();;
            $table->string('cover_original', 250)->nullable();
            $table->string('cover_big', 250)->nullable();
            $table->string('cover_medium', 250)->nullable();
            $table->string('cover_small', 250)->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
