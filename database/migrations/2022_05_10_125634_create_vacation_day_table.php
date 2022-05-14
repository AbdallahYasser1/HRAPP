<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacation_day', function (Blueprint $table) {
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer("scheduled");
            $table->integer("unscheduled");
            $table->primary('user_id');
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
        Schema::dropIfExists('vacation_day');
    }
};
