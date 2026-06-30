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
            $table->dropForeign(['klant_id']);
            $table->unsignedBigInteger('klant_id')->nullable()->change();
            $table->foreign('klant_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->dropForeign(['klant_id']);
            $table->unsignedBigInteger('klant_id')->nullable(false)->change();
            $table->foreign('klant_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
