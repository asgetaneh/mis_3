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
            $table->double('plan_value');
            $table->double('accom_value')->default(0);
            $table->tinyInteger('plan_status')->default(0);
            $table->tinyInteger('accom_status')->default(0);
            $table->unsignedBigInteger('kpi_child_one_id')->default(0);
            $table->unsignedBigInteger('kpi_child_two_id')->default(0);
            $table->unsignedBigInteger('kpi_child_three_id')->default(0);
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
