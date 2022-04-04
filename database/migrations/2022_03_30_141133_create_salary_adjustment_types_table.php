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
            $table->double('percent', 4, 2);
            $table->boolean('is_working_hours');
            $table->boolean('is_other');
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