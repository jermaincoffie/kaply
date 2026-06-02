<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->foreignId('medewerker_id')->nullable()->after('dienst_id')->constrained('medewerkers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->dropForeign(['medewerker_id']);
            $table->dropColumn('medewerker_id');
        });
    }
};
