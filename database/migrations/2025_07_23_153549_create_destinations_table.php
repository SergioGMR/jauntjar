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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->integer('accommodation_stars');
            $table->integer('accommodation_price');
            $table->string('transport_type');
            $table->integer('transport_price');
            $table->timestamp('arrival_date')->nullable();
            $table->integer('duration_days')->nullable();
            $table->string('displacement')->nullable();
            $table->integer('displacement_price')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
