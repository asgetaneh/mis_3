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
        Schema::create('key_peformance_indicator_ts', function (
            Blueprint $table
        ) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->text('out_put');
            $table->text('out_come');
            $table->unsignedBigInteger('translation_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('key_peformance_indicator_ts');
    }
};
