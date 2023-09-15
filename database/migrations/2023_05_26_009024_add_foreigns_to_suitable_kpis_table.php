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
        Schema::table('suitable_kpis', function (Blueprint $table) {
            $table
                ->foreign('key_peformance_indicator_id')
                ->references('id')
                ->on('key_peformance_indicators')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('office_id')
                ->references('id')
                ->on('offices')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('planing_year_id')
                ->references('id')
                ->on('planing_years')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suitable_kpis', function (Blueprint $table) {
            $table->dropForeign(['key_peformance_indicator_id']);
            $table->dropForeign(['office_id']);
            $table->dropForeign(['planing_year_id']);
        });
    }
};
