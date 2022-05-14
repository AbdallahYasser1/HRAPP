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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('specifity');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('branches');
            $table->string('location');
            $table->string('country');
            $table->string('photo');
            $table->string('latiude');
            $table->string('longtiude');
            $table->string('distance');
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
        Schema::dropIfExists('configs');
    }
};
