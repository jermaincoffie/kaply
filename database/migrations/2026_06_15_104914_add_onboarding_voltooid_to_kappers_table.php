<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->boolean('onboarding_voltooid')->default(false)->after('actief');
        });

        // Bestaande kappers zijn al ingesteld — markeer als voltooid
        DB::table('kappers')->update(['onboarding_voltooid' => true]);
    }

    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn('onboarding_voltooid');
        });
    }
};
