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
        Schema::create('key_peformance_indicators', function (
            Blueprint $table
        ) {
            $table->bigIncrements('id');
            $table->double('weight');
            $table->unsignedBigInteger('objective_id');
            $table->unsignedBigInteger('strategy_id');
            $table->unsignedBigInteger('created_by_id');
            $table->unsignedBigInteger('reporting_period_type_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_peformance_indicators');
    }
};
