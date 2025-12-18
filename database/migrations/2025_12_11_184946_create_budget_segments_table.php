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
        Schema::create('budget_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->cascadeOnDelete();
            $table->foreignId('origin_city_id')->constrained('cities')->cascadeOnDelete();
            $table->foreignId('destination_city_id')->constrained('cities')->cascadeOnDelete();
            $table->unsignedInteger('stay_days')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['budget_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_segments');
    }
};
