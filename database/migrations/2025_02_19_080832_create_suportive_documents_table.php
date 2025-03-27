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
        Schema::create('suportive_documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('aproval_status')->default(100);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('report_naration_report_id');
            $table
                ->foreign('report_naration_report_id')
                ->references('id')
                ->on('report_narration_reports')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suportive_documents');
    }
};
