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
        Schema::create('task_assigns', function (Blueprint $table) {
           $table->id();
            $table->foreignId('task_id')->constrained();
            $table->foreignId('assigned_by_id')->constrained('users');
            $table->foreignId('assigned_to_id')->constrained('users');
            $table->integer('status');
            $table->date('assigned_at');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('time_gap');
            $table->string('expected_value');
            $table->text('reject_reason')->nullable();
            $table->text('challenge')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_assigns');
    }
};
