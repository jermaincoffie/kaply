<?php

namespace App\Livewire\Kapper;

use Livewire\Component;

class FacturatieOverzicht extends Component
{
    public function render()
    {
        $user = auth()->user();
        $invoices = [];
        $stripeError = false;

        try {
            if ($user->hasStripeId()) {
                $invoices = $user->invoices();
            }
        } catch (\Exception $e) {
            $stripeError = true;
        }

        return view('livewire.kapper.facturatie-overzicht', compact('invoices', 'stripeError'))
            ->layout('layouts.kapper', ['title' => 'Facturatie']);
    }
}
