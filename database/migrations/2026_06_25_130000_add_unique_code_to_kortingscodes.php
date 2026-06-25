<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kortingscodes', function (Blueprint $table) {
            $table->unique(['kapper_id', 'code'], 'kortingscodes_kapper_code_unique');
        });
    }

    public function down(): void
    {
        Schema::table('kortingscodes', function (Blueprint $table) {
            $table->dropUnique('kortingscodes_kapper_code_unique');
        });
    }
};
