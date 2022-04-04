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
        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('user_id')->constrained();
            $table->integer('user_id');
            $table->foreignId('salary_term_id')->constrained();
//            $table->foreignId('salary_adjustment_id')->constrained();
            $table->double('net_salary', 8, 2);
            $table->string('period');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('salary_term_id')->references('id')->on('salary_terms');
//            $table->foreign('salary_adjustment_id')->references('id')->on('salary_adjustments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_slips');
    }
};