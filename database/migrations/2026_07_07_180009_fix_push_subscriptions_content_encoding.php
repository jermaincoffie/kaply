<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('push_subscriptions')
            ->where(function ($q) {
                $q->whereNull('content_encoding')
                  ->orWhere('content_encoding', 'aesgcm');
            })
            ->update(['content_encoding' => 'aes128gcm']);
    }

    public function down(): void {}
};
