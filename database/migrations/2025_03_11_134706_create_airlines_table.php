<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('airlines', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('display');
            $table->string('slug');
            $table->longText('logo');
            $table->boolean('is_low_cost')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('airlines');
    }
};
