<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('push_subscriptions', function (Blueprint $table) {
            $table->string('content_encoding')->default('aes128gcm')->change();
        });

        DB::table('push_subscriptions')
            ->whereNull('content_encoding')
            ->orWhere('content_encoding', 'aesgcm')
            ->update(['content_encoding' => 'aes128gcm']);
    }

    public function down(): void
    {
        Schema::table('push_subscriptions', function (Blueprint $table) {
            $table->string('content_encoding')->nullable()->default(null)->change();
        });
    }
};
