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
        Schema::table('key_peformance_indicator_kpi_child_two', function (
            Blueprint $table
        ) {
            $table
                ->foreign('key_peformance_indicator_id', 'kpi_two')
                ->references('id')
                ->on('key_peformance_indicators')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('kpi_child_two_id')
                ->references('id')
                ->on('kpi_child_twos')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpi_child_one_kpi_child_two', function (
            Blueprint $table
        ) {
            $table->dropForeign(['kpi_child_one_id']);
            $table->dropForeign(['kpi_child_two_id']);
        });
    }
};
