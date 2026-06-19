<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->string('stripe_connect_id')->nullable()->after('stripe_customer_id');
            $table->boolean('stripe_connect_onboarded')->default(false)->after('stripe_connect_id');
        });
    }

    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn(['stripe_connect_id', 'stripe_connect_onboarded']);
        });
    }
};
