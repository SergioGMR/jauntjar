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
        Schema::table('budget_segments', function (Blueprint $table) {
            $table->string('transport_kind')->nullable()->after('destination_city_id');
            $table->unsignedInteger('transport_price')->default(0)->after('transport_kind');
            $table->unsignedInteger('stay_price')->default(0)->after('stay_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_segments', function (Blueprint $table) {
            $table->dropColumn(['transport_kind', 'transport_price', 'stay_price']);
        });
    }
};
