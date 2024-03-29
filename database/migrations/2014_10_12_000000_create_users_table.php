<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**php
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer("id")->primary()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->date('birthdate');
            $table->string('password');
            $table->boolean('active')->default(true);
            $table->boolean('first_time_login')->default(true);
            $table->enum('status',['active','wfh','mission','vacation','leave','available'])->default('available');
            $table->boolean('can_wfh')->default(true);

            $table->rememberToken();
            $table->timestamps();
        });
        Schema::table('users',function(Blueprint $table){
            $table->integer('supervisor')->nullable();
            $table->foreign('supervisor')->references('id')->on('users');

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
};
