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
            $table->unsignedTinyInteger('buffer_minuten')->default(0)->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn('buffer_minuten');
        });
    }
};
