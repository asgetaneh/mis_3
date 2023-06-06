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
        Schema::table('gender_translations', function (Blueprint $table) {
            $table
                ->foreign('gender_id')
                ->references('id')
                ->on('genders')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gender_translations', function (Blueprint $table) {
            $table->dropForeign(['gender_id']);
        });
    }
};
