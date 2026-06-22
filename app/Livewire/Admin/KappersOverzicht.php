<?php

namespace App\Livewire\Admin;

use App\Models\Kapper;
use Livewire\Component;

class KappersOverzicht extends Component
{
    public string $zoekterm = '';

    public function updatingZoekterm(): void {}

    public function goedkeuren(int $id): void
    {
        Kapper::findOrFail($id)->update(['actief' => true, 'abonnement_status' => 'actief']);
    }

    public function afwijzen(int $id): void
    {
        $kapper = Kapper::with('user')->findOrFail($id);
        $user = $kapper->user;
        $kapper->delete();
        $user?->delete();
    }

    public function activeer(int $id): void
    {
        Kapper::findOrFail($id)->update(['actief' => true, 'abonnement_status' => 'actief']);
    }

    public function deactiveer(int $id): void
    {
        Kapper::findOrFail($id)->update(['actief' => false, 'abonnement_status' => 'gepauzeerd']);
    }

    public function render()
    {
        $wachtend = Kapper::with('user')
            ->where('actief', false)
            ->where('abonnement_status', 'geen')
            ->orderByDesc('created_at')
            ->get();

        $kappers = Kapper::with('user')
            ->where(fn($q) => $q->where('actief', true)->orWhere('abonnement_status', '!=', 'geen'))
            ->when($this->zoekterm, fn($q) => $q->where(fn($q2) =>
                $q2->where('salon_naam', 'like', "%{$this->zoekterm}%")
                   ->orWhere('stad', 'like', "%{$this->zoekterm}%")
                   ->orWhereHas('user', fn($q3) => $q3->where('email', 'like', "%{$this->zoekterm}%")
                       ->orWhere('name', 'like', "%{$this->zoekterm}%"))
            ))
            ->withCount([
                'afspraken as totaal_afspraken' => fn($q) => $q->whereIn('status', ['voltooid', 'no_show']),
                'afspraken as no_show_count'    => fn($q) => $q->where('status', 'no_show'),
            ])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($k) {
                $k->no_show_rate = $k->totaal_afspraken > 0
                    ? round(($k->no_show_count / $k->totaal_afspraken) * 100)
                    : null;
                return $k;
            });

        return view('livewire.admin.kappers-overzicht', compact('kappers', 'wachtend'))
            ->layout('layouts.admin');
    }
}
