<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kortingscodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapper_id')->constrained('kappers')->cascadeOnDelete();
            $table->string('code', 50);
            $table->enum('type', ['percentage', 'vast']);
            $table->unsignedInteger('waarde');
            $table->unsignedSmallInteger('max_gebruik')->nullable();
            $table->unsignedInteger('gebruik_teller')->default(0);
            $table->date('geldig_van')->nullable();
            $table->date('geldig_tot')->nullable();
            $table->boolean('actief')->default(true);
            $table->timestamps();

            $table->unique(['kapper_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kortingscodes');
    }
};
