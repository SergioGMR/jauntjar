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
        Schema::table('budgets', function (Blueprint $table) {
            $table->boolean('is_open_jaw')->default(false)->after('slug');
            $table->foreignId('origin_city_id')->nullable()->after('is_open_jaw')->constrained('cities')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropForeign(['origin_city_id']);
            $table->dropColumn(['is_open_jaw', 'origin_city_id']);
        });
    }
};
