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
        Schema::table('goal_translations', function (Blueprint $table) {
            $table
                ->foreign('translation_id')
                ->references('id')
                ->on('goals')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('goal_translations', function (Blueprint $table) {
            $table->dropForeign(['translation_id']);
        });
    }
};