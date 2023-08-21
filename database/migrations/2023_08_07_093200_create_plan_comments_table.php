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
        Schema::create('plan_comments', function (Blueprint $table) {
            $table->id();

            $table->text('plan_comment');
            $table->unsignedBigInteger('kpi_id');
            $table->unsignedBigInteger('reporting_period_id');
            $table->unsignedBigInteger('planning_year_id');
            $table->unsignedBigInteger('office_id');
            $table->boolean('status')->default(1);
            $table->text('reply_comment')->nullable();
            $table->integer('commented_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_comments');
    }
};
