<?php

namespace App\Livewire\Kapper;

use App\Mail\WelkomstMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\Attributes\Url;
use Stripe\BillingPortal\Session as PortalSession;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class AbonnementSucces extends Component
{
    #[Url]
    public string $session_id = '';

    public ?string $salonNaam = null;
    public ?string $planNaam  = null;

    public function mount(): void
    {
        if (!$this->session_id) return;

        try {
            Stripe::setApiKey(config('cashier.secret'));
            $session = StripeSession::retrieve([
                'id'     => $this->session_id,
                'expand' => ['subscription.items.data.price.product'],
            ]);

            $this->planNaam = $session->subscription?->items?->data[0]?->price?->product?->name ?? 'Kaply Abonnement';
        } catch (\Exception $e) {
            // toon succes zonder details
        }

        $this->salonNaam = auth()->user()->kapper?->salon_naam;

        $user = auth()->user();
        if (!$user->welcomed_at) {
            $user->update(['welcomed_at' => now()]);
            Mail::to($user->email)->send(new WelkomstMail($user));
        }
    }

    public function naarPortal(): void
    {
        try {
            Stripe::setApiKey(config('cashier.secret'));

            if ($this->session_id) {
                $checkoutSession = StripeSession::retrieve($this->session_id);
                $customerId = $checkoutSession->customer;
            } else {
                $customerId = auth()->user()->stripe_id;
            }

            if (!$customerId) {
                $this->redirect(route('kapper.abonnement'));
                return;
            }

            $portalSession = PortalSession::create([
                'customer'   => $customerId,
                'return_url' => route('kapper.abonnement'),
            ]);

            $this->redirect($portalSession->url);

        } catch (\Exception $e) {
            $this->redirect(route('kapper.abonnement'));
        }
    }

    public function render()
    {
        return view('livewire.kapper.abonnement-succes')
            ->layout('layouts.kapper', ['title' => 'Abonnement geactiveerd']);
    }
}
