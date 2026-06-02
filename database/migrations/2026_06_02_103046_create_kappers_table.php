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
        Schema::create('kappers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('salon_naam');
            $table->string('slug')->unique();
            $table->string('adres')->nullable();
            $table->string('stad');
            $table->string('telefoon')->nullable();
            $table->text('bio')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('abonnement_status')->default('geen'); // geen/actief/gepauzeerd
            $table->boolean('actief')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kappers');
    }
};
