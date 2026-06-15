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
        Schema::table('kappers', function (Blueprint $table) {
            $table->string('ical_token', 40)->nullable()->unique()->after('onboarding_voltooid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn('ical_token');
        });
    }
};
