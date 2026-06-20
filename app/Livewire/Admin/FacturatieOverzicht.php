<?php

namespace App\Livewire\Admin;

use App\Models\Kapper;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FacturatieOverzicht extends Component
{
    public string $zoekterm = '';
    public string $statusFilter = '';

    public function render()
    {
        $kappers = Kapper::with('user')
            ->when($this->zoekterm, fn($q) => $q->where(function ($q) {
                $q->where('salon_naam', 'like', '%' . $this->zoekterm . '%')
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $this->zoekterm . '%')
                  ->orWhere('email', 'like', '%' . $this->zoekterm . '%'));
            }))
            ->when($this->statusFilter, fn($q) => $q->where('abonnement_status', $this->statusFilter))
            ->orderByDesc('created_at')
            ->get();

        $subscriptions = DB::table('subscriptions')
            ->whereIn('user_id', $kappers->pluck('user_id'))
            ->get()
            ->keyBy('user_id');

        $totaalActief  = Kapper::where('abonnement_status', 'actief')->count();
        $totaalTrial   = Kapper::where('abonnement_status', 'trial')->count();
        $totaalInactief = Kapper::whereIn('abonnement_status', ['inactief', 'geen', null])->count();
        $maandOmzet    = $totaalActief * 2500;

        return view('livewire.admin.facturatie-overzicht', compact(
            'kappers', 'subscriptions', 'totaalActief', 'totaalTrial', 'totaalInactief', 'maandOmzet'
        ))->layout('layouts.admin', ['title' => 'Facturatie']);
    }
}
