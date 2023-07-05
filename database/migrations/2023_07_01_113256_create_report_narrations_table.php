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
        Schema::create('report_narrations', function (Blueprint $table) {
            $table->id();
            $table->text('plan_naration') ->nullable();
            $table->text('report_naration') ->nullable();
            $table->text('approval_text') ->nullable();
            $table->unsignedBigInteger('key_peformance_indicator_id');
            $table
                ->foreign('key_peformance_indicator_id')
                ->references('id')
                ->on('key_peformance_indicators')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->unsignedBigInteger('office_id');
            $table
                ->foreign('office_id')
                ->references('id')
                ->on('offices')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->unsignedBigInteger('reporting_period_id')->nullable();
            $table
                ->foreign('reporting_period_id')
                ->references('id')
                ->on('reporting_periods')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->unsignedBigInteger('planing_year_id');
            $table
                ->foreign('planing_year_id')
                ->references('id')
                ->on('planing_years')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_narrations');
    }
};
