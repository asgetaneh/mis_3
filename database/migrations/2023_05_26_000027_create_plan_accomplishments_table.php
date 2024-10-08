<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_accomplishments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kpi_id');
            $table->unsignedBigInteger('reporting_period_id');
            $table->unsignedBigInteger('planning_year_id');
            $table->double('plan_value');
            $table->double('accom_value')->nullable();
            $table->tinyInteger('plan_status')->nullable();
            $table->tinyInteger('accom_status')->nullable();
            $table->unsignedBigInteger('kpi_child_one_id') ->nullable();
            $table->unsignedBigInteger('kpi_child_two_id') ->nullable();
            $table->unsignedBigInteger('kpi_child_three_id') ->nullable();
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('approved_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_accomplishments');
    }
};
