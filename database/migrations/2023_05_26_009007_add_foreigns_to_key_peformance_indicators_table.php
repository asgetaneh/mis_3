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
        Schema::table('key_peformance_indicators', function (Blueprint $table) {
            $table
                ->foreign('objective_id')
                ->references('id')
                ->on('objectives')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('strategy_id')
                ->references('id')
                ->on('strategies')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('created_by_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table
                ->foreign('reporting_period_type_id')
                ->references('id')
                ->on('reporting_period_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table
                ->foreign('behavior_id')
                ->references('id')
                ->on('behaviors')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
                
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('key_peformance_indicators', function (Blueprint $table) {
            $table->dropForeign(['objective_id']);
            $table->dropForeign(['strategy_id']);
            $table->dropForeign(['created_by_id']);
            $table->dropForeign(['reporting_period_type_id']);
        });
    }
};
