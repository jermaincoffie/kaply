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
        Schema::create('beschikbaarheden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('dag_van_week'); // 0=maandag, 6=zondag
            $table->time('start_tijd');
            $table->time('eind_tijd');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beschikbaarheden');
    }
};
