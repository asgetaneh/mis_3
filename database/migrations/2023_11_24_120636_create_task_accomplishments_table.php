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
        Schema::create('task_accomplishments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_assign_id')->constrained('task_assigns');
            $table->double('reported_value');
            $table->date('reported_at');
            $table->text('task_done_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_accomplishments');
    }
};
