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
        Schema::create('kpi_child_three_kpi_child_two', function (
            Blueprint $table
        ) {
            $table->unsignedBigInteger('kpi_child_three_id');
            $table->unsignedBigInteger('kpi_child_two_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_child_three_kpi_child_two');
    }
};
