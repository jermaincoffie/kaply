<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Push subscribe request van user ' . ($request->user()?->id ?? 'geen') . ' UA: ' . substr($request->userAgent() ?? '', 0, 80));

        $request->validate([
            'endpoint'    => ['required', 'url'],
            'keys.auth'   => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
        ]);

        $request->user()->updatePushSubscription(
            $request->endpoint,
            $request->keys['p256dh'],
            $request->keys['auth'],
        );

        return response()->json(['ok' => true]);
    }
}
