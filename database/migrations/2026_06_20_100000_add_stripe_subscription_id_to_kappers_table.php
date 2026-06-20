<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            // Slaat het Stripe subscription ID op (sub_xxx) dat aangemaakt wordt
            // via de stripe_balance flow — zodat je het later kunt ophalen of annuleren
            $table->string('stripe_subscription_id')->nullable()->after('stripe_connect_onboarded');
        });
    }

    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn('stripe_subscription_id');
        });
    }
};
