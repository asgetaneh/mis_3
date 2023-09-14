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
        Schema::table('reporting_periods', function (Blueprint $table) {
            // $table
            //     ->foreign('planing_year_id')
            //     ->references('id')
            //     ->on('planing_years')
            //     ->onUpdate('CASCADE')
            //     ->onDelete('CASCADE');

            $table
                ->foreign('reporting_period_type_id')
                ->references('id')
                ->on('reporting_period_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reporting_periods', function (Blueprint $table) {
            $table->dropForeign(['planing_year_id']);
            $table->dropForeign(['reporting_period_type_id']);
        });
    }
};
