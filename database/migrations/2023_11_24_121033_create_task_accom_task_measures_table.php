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
        Schema::create('task_accom_task_measures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_accom_id')->constrained('task_accomplishments');
            $table->foreignId('task_measurement_id')->constrained('task_measurements');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_accom_task_measures');
    }
};
