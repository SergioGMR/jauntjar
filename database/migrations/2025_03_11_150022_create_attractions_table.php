<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attractions', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('budget_id');
            $table->string('name');
            $table->string('display');
            $table->string('slug');
            $table->string('description');
            $table->string('url');
            $table->integer('price');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attractions');
    }
};
