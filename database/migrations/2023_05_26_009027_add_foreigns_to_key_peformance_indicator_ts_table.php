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
        Schema::table('key_peformance_indicator_ts', function (
            Blueprint $table
        ) {
            $table
                ->foreign('translation_id')
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
        Schema::table('key_peformance_indicator_ts', function (
            Blueprint $table
        ) {
            $table->dropForeign(['translation_id']);
        });
    }
};
