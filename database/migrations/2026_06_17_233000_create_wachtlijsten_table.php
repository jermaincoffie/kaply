<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wachtlijsten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained('kappers')->cascadeOnDelete();
            $table->foreignId('klant_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('naam');
            $table->string('email');
            $table->string('telefoonnummer')->nullable();
            $table->enum('status', ['wachtend', 'genotificeerd'])->default('wachtend');
            $table->timestamps();

            $table->unique(['kapper_id', 'email']); // één inschrijving per kapper per email
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wachtlijsten');
    }
};
