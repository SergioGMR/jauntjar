<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('display');
            $table->string('slug');
            $table->string('code');
            $table->string('currency');
            $table->integer('pibpc');
            $table->string('visa');
            $table->string('language');
            $table->string('roaming');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
