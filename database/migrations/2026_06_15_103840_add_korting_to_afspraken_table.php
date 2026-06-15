<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->foreignId('kortingscode_id')->nullable()->after('medewerker_id')
                ->constrained('kortingscodes')->nullOnDelete();
            $table->unsignedInteger('korting_bedrag')->nullable()->after('kortingscode_id');
        });
    }

    public function down(): void
    {
        Schema::table('afspraken', function (Blueprint $table) {
            $table->dropForeign(['kortingscode_id']);
            $table->dropColumn(['kortingscode_id', 'korting_bedrag']);
        });
    }
};
