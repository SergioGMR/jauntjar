<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('city_id');
            $table->integer('cost');
            $table->integer('culture');
            $table->integer('weather');
            $table->integer('food');
            $table->integer('total');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classifications');
    }
};
