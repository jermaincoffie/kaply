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
        Schema::table('afspraken', function (Blueprint $table) {
            $table->index(['kapper_id', 'datum']);
            $table->index('klant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->dropIndex(['kapper_id', 'datum']);
            $table->dropIndex(['klant_id']);
        });
    }
};
