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
        Schema::table('planing_year_translations', function (Blueprint $table) {
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
        Schema::table('planing_year_translations', function (Blueprint $table) {
            $table->dropForeign(['planing_year_id']);
        });
    }
};
