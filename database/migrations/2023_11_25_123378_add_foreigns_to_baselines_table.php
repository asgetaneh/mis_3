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
        Schema::table('baselines', function (Blueprint $table) {
            $table
                ->foreign('kpi_id')
                ->references('id')
                ->on('key_peformance_indicators')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('planning_year_id')
                ->references('id')
                ->on('planing_years')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('kpi_one_id')
                ->references('id')
                ->on('kpi_child_ones')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('kpi_two_id')
                ->references('id')
                ->on('kpi_child_twos')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('kpi_three_id')
                ->references('id')
                ->on('kpi_child_threes')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('office_id')
                ->references('id')
                ->on('offices')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('baselines', function (Blueprint $table) {
            $table->dropForeign(['kpi_one_id']);
            $table->dropForeign(['kpi_two_id']);
            $table->dropForeign(['kpi_three_id']);
            $table->dropForeign(['planning_year_id']);
            $table->dropForeign(['office_id']);
        });
    }
};
