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
        Schema::create('key_peformance_indicator_kpi_child_one', function (
            Blueprint $table
        ) {
            $table->unsignedBigInteger('kpi_child_one_id');
            $table->unsignedBigInteger('key_peformance_indicator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_peformance_indicator_kpi_child_one');
    }
};
