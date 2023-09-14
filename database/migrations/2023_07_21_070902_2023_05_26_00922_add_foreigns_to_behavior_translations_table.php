<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('behavior_translations', function (Blueprint $table) {
            $table
                ->foreign('translation_id')
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
        Schema::table('behavior_translations', function (Blueprint $table) {
            $table->dropForeign(['translation_id']);
        });
    }
};
