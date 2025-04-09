<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id');
            $table->foreignId('airline_id');
            $table->foreignId('insurance_id');
            $table->string('name');
            $table->string('display');
            $table->string('slug');
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->integer('flight_ticket_price');
            $table->integer('insurance_price');
            $table->integer('accommodation_stars');
            $table->integer('accommodation_price');
            $table->string('transport_type');
            $table->integer('transport_price');
            $table->integer('total_price');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
