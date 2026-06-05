<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('klant_notities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
            $table->foreignId('klant_id')->constrained('users')->cascadeOnDelete();
            $table->text('notities')->nullable();
            $table->timestamps();
            $table->unique(['kapper_id', 'klant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('klant_notities');
    }
};
