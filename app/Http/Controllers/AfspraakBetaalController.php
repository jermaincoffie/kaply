<?php

namespace App\Http\Controllers;

use App\Mail\AfspraakBevestigingMail;
use App\Mail\AfspraakGeannuleerdMail;
use App\Models\Afspraak;
use App\Models\Wachtlijst;
use App\Notifications\AfspraakGeannuleerdNotificatie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class AfspraakBetaalController extends Controller
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));

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

        if ($kapper->stripe_connect_onboarded) {
            $params['transfer_data'] = ['destination' => $kapper->stripe_connect_id];
        }

        $session = StripeSession::create($params, [
            'idempotency_key' => 'afspraak_checkout_' . $afspraak->id,
        ]);

        return redirect($session->url);
    }

    public function succes(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));

        $session = StripeSession::retrieve($request->session_id);

        if ($session->payment_status !== 'paid') {
            abort(400, 'Betaling niet bevestigd.');
        }

        $afspraakId = $session->metadata->afspraak_id ?? null;
        $afspraak   = Afspraak::with(['dienst', 'kapper'])->findOrFail($afspraakId);

        if ($afspraak->klant_id !== auth()->id()) abort(403);

        $user = auth()->user();

        // Stripe customer opslaan voor toekomstige auto-charge
        if (!$user->stripe_id && $session->customer) {
            $user->update(['stripe_id' => $session->customer]);
        }

        if ($afspraak->status === 'wacht_op_betaling') {
            $afspraak->update([
                'status'                   => 'gepland',
                'stripe_payment_intent_id' => $session->payment_intent,
            ]);

            Mail::to($afspraak->klant->email)->send(new AfspraakBevestigingMail($afspraak));
        }

        return view('afspraak.betaling-succes', compact('afspraak'));
    }

    public function annuleringCheckout(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));

        $afspraak = Afspraak::with(['dienst', 'kapper', 'klant'])->findOrFail($request->afspraak_id);

        if ($afspraak->klant_id !== auth()->id()) abort(403);
        if ($afspraak->status !== 'gepland') abort(400);
        if (!$afspraak->kapper->annulering_kosten) abort(400);

        $user   = auth()->user();
        $kapper = $afspraak->kapper;

        // Auto-charge via opgeslagen betaalmethode
        if ($user->stripe_id && $user->stripe_payment_method_id) {
            try {
                $intentParams = [
                    'amount'         => $kapper->annulering_kosten,
                    'currency'       => 'eur',
                    'customer'       => $user->stripe_id,
                    'payment_method' => $user->stripe_payment_method_id,
                    'confirm'        => true,
                    'off_session'    => true,
                    'description'    => 'Annuleringskosten - ' . $kapper->salon_naam,
                    'metadata'       => ['type' => 'annulering_fee', 'afspraak_id' => $afspraak->id],
                ];
                if ($kapper->stripe_connect_onboarded) {
                    $intentParams['transfer_data'] = ['destination' => $kapper->stripe_connect_id];
                }
                $intent = PaymentIntent::create($intentParams, [
                    'idempotency_key' => 'annulering_intent_' . $afspraak->id,
                ]);

                if ($intent->status === 'succeeded') {
                    $this->verwerkAnnulering($afspraak);
                    return view('afspraak.annulering-succes', compact('afspraak'));
                }
            } catch (\Exception $e) {
                // Kaart geweigerd of SCA vereist — val terug op Checkout
            }
        }

        // Stripe Checkout (eerste keer of auto-charge mislukt)
        $params = [
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'eur',
                    'unit_amount'  => $kapper->annulering_kosten,
                    'product_data' => [
                        'name'        => 'Annuleringskosten - ' . $kapper->salon_naam,
                        'description' => 'Afspraak ' . $afspraak->datum->format('d M Y') . ' om ' . $afspraak->start_tijd,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode'                 => 'payment',
            'payment_method_types' => ['card', 'ideal'],
            'setup_future_usage'   => 'off_session',
            'metadata'             => [
                'type'        => 'annulering_fee',
                'afspraak_id' => $afspraak->id,
            ],
            'success_url' => route('afspraak.annulering.succes') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('klant.afspraken'),
        ];

        if ($user->stripe_id) {
            $params['customer'] = $user->stripe_id;
        } else {
            $params['customer_email'] = $user->email;
        }

        if ($kapper->stripe_connect_onboarded) {
            $params['transfer_data'] = ['destination' => $kapper->stripe_connect_id];
        }

        $session = StripeSession::create($params, [
            'idempotency_key' => 'annulering_checkout_' . $afspraak->id . '_' . $afspraak->updated_at->timestamp,
        ]);

        return redirect($session->url);
    }

    public function annuleringSucces(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));

        $session = StripeSession::retrieve($request->session_id, [
            'expand' => ['payment_intent.payment_method'],
        ]);

        if ($session->payment_status !== 'paid') {
            abort(400, 'Betaling niet bevestigd.');
        }

        $afspraakId = $session->metadata->afspraak_id ?? null;
        $afspraak   = Afspraak::with(['dienst', 'kapper', 'klant'])->findOrFail($afspraakId);

        if ($afspraak->klant_id !== auth()->id()) abort(403);

        $user = auth()->user();

        // Stripe customer + betaalmethode opslaan voor volgende auto-charge
        if (!$user->stripe_id && $session->customer) {
            $user->update(['stripe_id' => $session->customer]);
            $user->refresh();
        }

        if (!$user->stripe_payment_method_id && $session->payment_intent) {
            $pm = $session->payment_intent->payment_method ?? null;
            $pmId = is_string($pm) ? $pm : ($pm->id ?? null);
            if ($pmId) {
                $user->update(['stripe_payment_method_id' => $pmId]);
            }
        }

        $this->verwerkAnnulering($afspraak);

        return view('afspraak.annulering-succes', compact('afspraak'));
    }

    private function verwerkAnnulering(Afspraak $afspraak): void
    {
        if ($afspraak->status !== 'gepland') return;

        $afspraak->update(['status' => 'geannuleerd']);

        Mail::to($afspraak->klant->email)->send(new AfspraakGeannuleerdMail($afspraak));
        $afspraak->kapper->user->notify(new AfspraakGeannuleerdNotificatie($afspraak));

        if ($afspraak->datum->isAfter(today())) {
            $wachtenden = Wachtlijst::where('kapper_id', $afspraak->kapper_id)
                ->where('status', 'wachtend')
                ->get();

            foreach ($wachtenden as $wachtende) {
                Mail::to($wachtende->email)->send(new \App\Mail\WachtlijstNotificatieMail($afspraak->kapper));
                $wachtende->update(['status' => 'genotificeerd']);
            }
        }
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
