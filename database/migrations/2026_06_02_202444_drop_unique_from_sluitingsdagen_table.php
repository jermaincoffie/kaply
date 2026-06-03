<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->index('kapper_id', 'sluitingsdagen_kapper_id_index');
        });
        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->dropUnique('sluitingsdagen_kapper_id_datum_unique');
        });
    }

    public function down(): void
    {
        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->dropIndex('sluitingsdagen_kapper_id_index');
            $table->unique(['kapper_id', 'datum']);
        });
    }
};
