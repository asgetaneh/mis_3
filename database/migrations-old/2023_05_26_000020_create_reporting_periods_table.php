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
        Schema::create('reporting_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('planing_year_id');
            $table->string('start_date');
            $table->string('end_date');
            $table->unsignedBigInteger('reporting_period_type_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporting_periods');
    }
};
