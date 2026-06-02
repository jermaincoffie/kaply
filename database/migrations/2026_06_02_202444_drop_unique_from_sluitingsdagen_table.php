<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->dropUnique('sluitingsdagen_kapper_id_datum_unique');
        });
    }

    public function down(): void
    {
        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->unique(['kapper_id', 'datum']);
        });
    }
};
