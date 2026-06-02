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
        Schema::create('diensten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
            $table->string('naam');
            $table->unsignedInteger('duur_minuten');
            $table->unsignedInteger('prijs'); // in centen
            $table->unsignedInteger('no_show_bedrag')->default(0); // in centen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diensten');
    }
};
