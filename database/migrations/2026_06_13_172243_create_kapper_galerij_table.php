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
        Schema::create('kapper_galerij', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained('kappers')->cascadeOnDelete();
            $table->string('pad');
            $table->unsignedSmallInteger('volgorde')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapper_galerij');
    }
};
