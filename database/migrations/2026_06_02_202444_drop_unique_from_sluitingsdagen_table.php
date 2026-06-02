<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Voeg gewone index toe zodat FK deze kan gebruiken ipv de unique index
        DB::statement('ALTER TABLE sluitingsdagen ADD INDEX sluitingsdagen_kapper_id_index (kapper_id)');
        DB::statement('ALTER TABLE sluitingsdagen DROP INDEX sluitingsdagen_kapper_id_datum_unique');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE sluitingsdagen DROP INDEX sluitingsdagen_kapper_id_index');
        DB::statement('ALTER TABLE sluitingsdagen ADD UNIQUE INDEX sluitingsdagen_kapper_id_datum_unique (kapper_id, datum)');
    }
};
