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
        Schema::create('report_comments', function (Blueprint $table) {
            $table->id();

            $table->text('report_comment')->nullable();
            $table->unsignedBigInteger('kpi_id');
            $table->unsignedBigInteger('reporting_period_id');
            $table->unsignedBigInteger('planning_year_id');
            $table->unsignedBigInteger('office_id');
            $table->boolean('status')->default(1);
            $table->text('reply_comment')->nullable();
            $table->integer('commented_by')->nullable();
            $table->boolean('replied_active')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_comments');
    }
};
