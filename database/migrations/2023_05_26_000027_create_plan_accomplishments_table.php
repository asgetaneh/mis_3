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
            $table->unsignedBigInteger('suitable_kpi_id');
            $table->unsignedBigInteger('reporting_period_id');
            $table->double('plan_value');
            $table->double('accom_value');
            $table->tinyInteger('plan_status');
            $table->tinyInteger('accom_status');
            $table->unsignedBigInteger('kpi_child_one_id');
            $table->unsignedBigInteger('kpi_child_two_id');
            $table->unsignedBigInteger('kpi_child_three_id');
            $table->unsignedBigInteger('office_id');

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
