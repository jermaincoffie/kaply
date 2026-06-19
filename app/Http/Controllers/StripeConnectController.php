<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\LoginLink;
use Stripe\Stripe;

class StripeConnectController extends Controller
{
    public function onboard(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));

        $kapper = auth()->user()->kapper;

        if (!$kapper->stripe_connect_id) {
            $account = Account::create([
                'type'         => 'express',
                'country'      => 'NL',
                'email'        => auth()->user()->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers'     => ['requested' => true],
                ],
                'metadata' => ['kapper_id' => $kapper->id],
            ]);

            $kapper->update(['stripe_connect_id' => $account->id]);
            $kapper->refresh();
        }

        $from = $request->get('from') ?? session('stripe_connect_from', 'profiel');
        session(['stripe_connect_from' => $from]);

        $accountLink = AccountLink::create([
            'account'     => $kapper->stripe_connect_id,
            'refresh_url' => route('kapper.stripe.refresh'),
            'return_url'  => route('kapper.stripe.return'),
            'type'        => 'account_onboarding',
        ]);

        return redirect($accountLink->url);
    }

    public function return(Request $request)
    {
        Stripe::setApiKey(config('cashier.secret'));

        $kapper = auth()->user()->kapper;

        if ($kapper->stripe_connect_id) {
            $account = Account::retrieve($kapper->stripe_connect_id);

            if ($account->details_submitted) {
                $kapper->update(['stripe_connect_onboarded' => true]);
            }
        }

        $from = session()->pull('stripe_connect_from', 'profiel');

        if ($from === 'onboarding') {
            session()->flash('onboarding_klaar', true);
            session()->flash('stripe_gekoppeld', true);
            return redirect()->route('kapper.dashboard');
        }

        session()->flash('success', 'Stripe account succesvol gekoppeld! Je kunt nu online betalingen accepteren.');
        return redirect()->route('kapper.profiel-beheer');
    }

    public function refresh()
    {
        return $this->onboard(request());
    }

    public function dashboard()
    {
        Stripe::setApiKey(config('cashier.secret'));

        $kapper = auth()->user()->kapper;

        if (!$kapper->stripe_connect_id) {
            return redirect()->route('kapper.profiel-beheer');
        }

        $loginLink = LoginLink::create($kapper->stripe_connect_id, [
            'redirect_url' => route('kapper.profiel-beheer'),
        ]);

        return redirect($loginLink->url);
    }
}
