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
        Schema::create('suitable_kpis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('key_peformance_indicator_id');
            $table->unsignedBigInteger('office_id');
            $table->unsignedBigInteger('planing_year_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suitable_kpis');
    }
};
