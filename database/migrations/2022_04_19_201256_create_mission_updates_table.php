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
        Schema::create('mission_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mission_id')->constrained('missions');
            $table->string('description');
            $table->date('date');
            $table->float('extra_cost', 8, 2);
            $table->string('approved_file');
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
        Schema::dropIfExists('mission_updates');
    }
};
