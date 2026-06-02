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
        Schema::create('afspraken', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dienst_id')->constrained('diensten')->cascadeOnDelete();
            $table->date('datum');
            $table->time('start_tijd');
            $table->time('eind_tijd');
            $table->string('status')->default('gepland'); // gepland/voltooid/geannuleerd/no_show
            $table->string('betaalmethode')->default('in_zaak'); // online/in_zaak
            $table->string('stripe_payment_intent_id')->nullable();
            $table->string('stripe_setup_intent_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('afspraken');
    }
};
