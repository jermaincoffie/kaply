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
        Schema::table('kappers', function (Blueprint $table) {
            $table->unsignedSmallInteger('annulering_uren')->nullable()->after('vooruitboeken_maanden');
        });
    }

    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn('annulering_uren');
        });
    }
};
