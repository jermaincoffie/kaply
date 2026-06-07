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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
            $table->foreignId('klant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('afspraak_id')->unique()->constrained('afspraken')->cascadeOnDelete();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->text('tekst')->nullable();
            $table->boolean('zichtbaar')->default(true);
            $table->timestamps();
            $table->index(['kapper_id', 'zichtbaar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
