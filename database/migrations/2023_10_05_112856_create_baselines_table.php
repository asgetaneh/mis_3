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
        Schema::create('baselines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('baseline');
            $table->tinyInteger('plan_status')->nullable();
            $table->tinyInteger('accom_status')->nullable();
            $table->unsignedBigInteger('kpi_id');
            $table->unsignedBigInteger('kpi_one_id')->nullable();
            $table->unsignedBigInteger('kpi_two_id')->nullable();
            $table->unsignedBigInteger('kpi_three_id')->nullable();
            $table->unsignedBigInteger('planning_year_id');
            $table->unsignedBigInteger('office_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baselines');
    }
};
