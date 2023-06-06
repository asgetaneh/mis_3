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
        Schema::create('kpi_child_one_translations', function (
            Blueprint $table
        ) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kpiChildOne_id');
            $table->string('name');
            $table->text('description');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_child_one_translations');
    }
};
