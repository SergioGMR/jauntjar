<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignIdFor(Country::class);
            $table->string('name');
            $table->string('display');
            $table->string('slug');
            $table->boolean('visited')->default(false);
            $table->timestamp('visited_at')->nullable();
            $table->json('coordinates')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
