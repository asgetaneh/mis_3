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
        Schema::create('measurement_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('slug');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('translation_id');
            $table
            ->foreign('translation_id')
            ->references('id')
            ->on('measurements')
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
            $table->string('locale', 8)->default('en');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measurement_translations');
    }
};
