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
        Schema::table('key_peformance_indicator_kpi_child_one', function (
            Blueprint $table
        ) {
            $table
                ->foreign('kpi_child_one_id')
                ->references('id')
                ->on('kpi_child_ones')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign(
                    'key_peformance_indicator_id',
                    'foreign_alias_0000002'
                )
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
        Schema::table('key_peformance_indicator_kpi_child_one', function (
            Blueprint $table
        ) {
            $table->dropForeign(['kpi_child_one_id']);
            $table->dropForeign(['key_peformance_indicator_id']);
        });
    }
};
