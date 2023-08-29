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
        Schema::table('kpi_type_translations', function (
            Blueprint $table
        ) {
            $table
                ->foreign('type_id')
                ->references('id')
                ->on('kpi_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE')
                ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpi_type_translations', function (
            Blueprint $table
        ) {
            $table->dropForeign(['type_id']);
        });
    }
};
