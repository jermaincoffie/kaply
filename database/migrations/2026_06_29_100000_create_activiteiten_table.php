<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activiteiten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained('kappers')->cascadeOnDelete();
            $table->foreignId('afspraak_id')->nullable()->nullOnDelete()->constrained('afspraken');
            $table->string('datum');
            $table->string('type'); // geboekt, walk_in, geannuleerd, no_show, voltooid, geblokkeerd
            $table->string('tekst');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activiteiten');
    }
};
