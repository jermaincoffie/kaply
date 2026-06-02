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
        Schema::create('sluitingsdagen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
            $table->date('datum');
            $table->string('reden')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sluitingsdagen');
    }
};
