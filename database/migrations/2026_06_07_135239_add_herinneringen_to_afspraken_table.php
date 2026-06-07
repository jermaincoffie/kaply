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
            $table->boolean('herinnering_24u_verstuurd')->default(false)->after('betaalmethode');
            $table->boolean('herinnering_1u_verstuurd')->default(false)->after('herinnering_24u_verstuurd');
        });
    }

    public function down(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->dropColumn(['herinnering_24u_verstuurd', 'herinnering_1u_verstuurd']);
        });
    }
};
