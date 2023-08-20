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
        Schema::table('report_comments', function (Blueprint $table) {
            $table
                ->foreign('kpi_id')
                ->references('kpi_id')
                ->on('plan_accomplishments')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('reporting_period_id')
                ->references('id')
                ->on('plan_accomplishments')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('planning_year_id')
                ->references('planning_year_id')
                ->on('plan_accomplishments')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('office_id')
                ->references('office_id')
                ->on('plan_accomplishments')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_comments', function (Blueprint $table) {
            $table->dropForeign(['kpi_id']);
            $table->dropForeign(['reporting_period_id']);
            $table->dropForeign(['planning_year_id']);
            $table->dropForeign(['office_id']);
        });
    }
};
