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
            $table->tinyInteger('vooruitboeken_maanden')->default(2)->after('buffer_minuten');
        });
    }

    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn('vooruitboeken_maanden');
        });
    }
};
