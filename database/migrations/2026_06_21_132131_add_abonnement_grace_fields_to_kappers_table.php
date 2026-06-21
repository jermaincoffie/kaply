<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->string('abonnement_betaalmethode')->nullable()->after('abonnement_status');
            $table->timestamp('abonnement_past_due_since')->nullable()->after('abonnement_betaalmethode');
        });
    }

    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn(['abonnement_betaalmethode', 'abonnement_past_due_since']);
        });
    }
};
