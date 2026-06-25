<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medewerker_beschikbaarheden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medewerker_id')->constrained('medewerkers')->cascadeOnDelete();
            $table->unsignedTinyInteger('dag_van_week'); // 0=ma … 6=zo
            $table->time('start_tijd');
            $table->time('eind_tijd');
            $table->timestamps();

            $table->unique(['medewerker_id', 'dag_van_week']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medewerker_beschikbaarheden');
    }
};
