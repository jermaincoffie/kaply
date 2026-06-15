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
            $table->boolean('trial_dag3_verstuurd')->default(false);
            $table->boolean('trial_dag10_verstuurd')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('kappers', function (Blueprint $table) {
            $table->dropColumn(['trial_dag3_verstuurd', 'trial_dag10_verstuurd']);
        });
    }
};
