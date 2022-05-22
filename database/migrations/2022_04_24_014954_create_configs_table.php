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
            $table->string('specificity');
            $table->string('company_email');
            $table->string('company_phone');
            $table->string('branches');
            $table->string('location');
            $table->string('country');
            $table->string('photo');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('distance');
            $table->string('timezone');
            $table->string('fullDayAbsenceDeduction');
            $table->string('halfDayAbsenceDeduction');
            $table->string('fullDayAbsenceDeductionName');
            $table->string('halfDayAbsenceDeductionName');
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
