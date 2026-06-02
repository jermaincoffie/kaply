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
        Schema::table('beschikbaarheden', function (Blueprint $table) {
            $table->unique(['kapper_id', 'dag_van_week']);
        });

        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->unique(['kapper_id', 'datum']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beschikbaarheden', function (Blueprint $table) {
            $table->dropUnique(['kapper_id', 'dag_van_week']);
        });

        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->dropUnique(['kapper_id', 'datum']);
        });
    }
};
