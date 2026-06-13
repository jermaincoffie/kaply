<?php

namespace App\Http\Controllers;

use App\Models\Afspraak;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as StripeSession;

class AfspraakBetaalController extends Controller
{
    public function checkout(Request $request)
    {
        $afspraak = Afspraak::with(['dienst', 'kapper'])->findOrFail($request->afspraak_id);

        if ($afspraak->klant_id !== auth()->id()) abort(403);
        if ($afspraak->status !== 'wacht_op_betaling') abort(400);

        $user   = auth()->user();
        $dienst = $afspraak->dienst;
        $kapper = $afspraak->kapper;

        $params = [
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => $dienst->prijs,
                    'product_data' => [
                        'name'        => $dienst->naam . ' bij ' . $kapper->salon_naam,
                        'description' => $afspraak->datum->format('d M Y') . ' om ' . $afspraak->start_tijd . ' · ' . $dienst->duur_minuten . ' min',
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode'                 => 'payment',
            'payment_method_types' => ['card', 'ideal'],
            'metadata'             => [
                'type'        => 'afspraak',
                'afspraak_id' => $afspraak->id,
            ],
            'success_url' => route('afspraak.betaling.succes') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('afspraak.betaling.annuleren', ['afspraak_id' => $afspraak->id]),
        ];

        if ($user->stripe_id) {
            $params['customer'] = $user->stripe_id;
        } else {
            $params['customer_email'] = $user->email;
        }

        $session = StripeSession::create($params);

        return redirect($session->url);
    }

    public function succes(Request $request)
    {
        $session = StripeSession::retrieve($request->session_id);

        if ($session->payment_status !== 'paid') {
            abort(400, 'Betaling niet bevestigd.');
        }

        $afspraakId = $session->metadata->afspraak_id ?? null;
        $afspraak   = Afspraak::with(['dienst', 'kapper'])->findOrFail($afspraakId);

        if ($afspraak->klant_id !== auth()->id()) abort(403);

        if ($afspraak->status === 'wacht_op_betaling') {
            $afspraak->update([
                'status'                   => 'gepland',
                'stripe_payment_intent_id' => $session->payment_intent,
            ]);
        }

        return view('afspraak.betaling-succes', compact('afspraak'));
    }

    public function annuleren(Request $request)
    {
        $afspraak = Afspraak::with('kapper')->findOrFail($request->afspraak_id);
        $slug     = $afspraak->kapper?->slug ?? '';

        if ($afspraak->klant_id === auth()->id() && $afspraak->status === 'wacht_op_betaling') {
            $afspraak->delete();
        }

        return redirect()->route('kapper.profiel', $slug);
    }
}
