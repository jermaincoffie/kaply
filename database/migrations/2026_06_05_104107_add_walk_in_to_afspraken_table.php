<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->string('walk_in_naam')->nullable()->after('klant_id');
            $table->text('notitie')->nullable()->after('betaalmethode');
            // klant_id nullable maken zodat walk-ins geen account nodig hebben
            $table->foreignId('klant_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->dropColumn(['walk_in_naam', 'notitie']);
        });
    }
};
