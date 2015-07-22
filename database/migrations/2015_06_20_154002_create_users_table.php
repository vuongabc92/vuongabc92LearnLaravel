<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 128)->unique();
            $table->string('user_name', 32)->unique();
            $table->string('password', 60);
            $table->string('avatar_original', 250)->nullable();
            $table->string('avatar_big', 250)->nullable();
            $table->string('avatar_medium', 250)->nullable();
            $table->string('avatar_small', 250)->nullable();
            $table->integer('role_id');
            $table->string('first_name', 16)->nullable();
            $table->string('last_name', 32)->nullable();
            $table->boolean('is_blocked')->default(0);
            $table->boolean('has_store')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
