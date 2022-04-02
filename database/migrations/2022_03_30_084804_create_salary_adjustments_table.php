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
        Schema::create('salary_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salary_slip_id')->nullable();
            $table->foreignId('salary_adjustment_type_id')->nullable();
            $table->double('amount', 8, 2);
            $table->double('percent', 4, 2);
            $table->timestamps();
//            $table->foreign('salary_slip_id')->references('id')->on('salary_slips');
//            $table->foreign('salary_adjustment_type_id')->references('id')->on('salary_adjustment_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salary_adjustments');
    }
};
