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
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('Contractor_name');
            $table->string('consultant_name');
            $table->string('date_started');
            $table->string('date_completed');
            $table->string('total_cost');
            $table->string('building_purpose');
            $table->string('building_type');
            $table->string('building_owner');
            $table->string('meets_standard');
            $table->string('meets_psn_standard');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
