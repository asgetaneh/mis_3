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
        Schema::table('kpi_office', function (Blueprint $table) {
            $table
                ->foreign('office_id')
                ->references('id')
                ->on('offices')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('kpi_id')
                ->references('id')
                ->on('key_peformance_indicators')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpi_office', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
            $table->dropForeign(['kpi_id']);
        });
    }
};
