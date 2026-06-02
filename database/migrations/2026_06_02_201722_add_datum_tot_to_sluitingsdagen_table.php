<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->date('datum_tot')->nullable()->after('datum');
        });
    }

    public function down(): void
    {
        Schema::table('sluitingsdagen', function (Blueprint $table) {
            $table->dropColumn('datum_tot');
        });
    }
};
