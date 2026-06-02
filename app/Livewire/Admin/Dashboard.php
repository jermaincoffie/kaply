<?php

namespace App\Livewire\Admin;

use App\Models\Afspraak;
use App\Models\Kapper;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'kappers_actief'     => Kapper::where('actief', true)->count(),
            'kappers_totaal'     => Kapper::count(),
            'afspraken_vandaag'  => Afspraak::whereDate('datum', today())->count(),
            'afspraken_week'     => Afspraak::whereBetween('datum', [today()->startOfWeek(), today()->endOfWeek()])->count(),
            'klanten_totaal'     => User::where('role', 'klant')->count(),
            'recente_afspraken'  => Afspraak::with(['kapper', 'dienst', 'klant'])
                ->orderByDesc('created_at')
                ->limit(8)
                ->get(),
        ])->layout('layouts.admin');
    }
}
