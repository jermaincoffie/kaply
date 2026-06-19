<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wachtlijsten', function (Blueprint $table) {
            $table->date('gewenste_datum')->nullable()->after('telefoonnummer');
        });
    }

    public function down(): void
    {
        Schema::table('wachtlijsten', function (Blueprint $table) {
            $table->dropColumn('gewenste_datum');
        });
    }
};
