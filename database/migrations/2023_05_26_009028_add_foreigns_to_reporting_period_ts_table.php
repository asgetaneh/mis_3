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
        Schema::table('reporting_period_ts', function (Blueprint $table) {
            $table
                ->foreign('reporting_period_id')
                ->references('id')
                ->on('reporting_periods')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reporting_period_ts', function (Blueprint $table) {
            $table->dropForeign(['reporting_period_id']);
        });
    }
};
