<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_measurement_acomplishments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_accomplishment_id')->constrained('task_accomplishments');
            $table->foreignId('task_measurement_id')->constrained('task_measurements');
             $table->double('accomplishment_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_measurement_acomplishments');
    }
};
