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
        Schema::create('salary_adjustment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('percent', 4, 2)->nullable();
            $table->double('amount')->nullable();
            $table->string('isAll')->default('0');
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
        Schema::dropIfExists('salary_adjustment_types');
    }
};
