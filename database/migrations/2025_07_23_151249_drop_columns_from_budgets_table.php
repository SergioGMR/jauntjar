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
            $table->dropColumn('city_id');
            $table->dropColumn('accommodation_stars');
            $table->dropColumn('accommodation_price');
            $table->dropColumn('transport_type');
            $table->dropColumn('transport_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budgets', function (Blueprint $table) {
            $table->foreignId('city_id');
            $table->integer('accommodation_stars');
            $table->integer('accommodation_price');
            $table->string('transport_type');
            $table->integer('transport_price');
        });
    }
};
