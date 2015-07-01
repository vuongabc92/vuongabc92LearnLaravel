<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWardsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('wards', function($table) {
            $table->increments('id');
            $table->integer('district_id')->unsigned();
            $table->string('name', 250);
            
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('wards');
    }

}
