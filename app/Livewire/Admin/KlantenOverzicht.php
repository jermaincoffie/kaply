<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;

class KlantenOverzicht extends Component
{
    public string $zoekterm = '';
    public int    $limite   = 30;

    public function updatingZoekterm(): void { $this->limite = 30; }

    public function laadMeer(): void { $this->limite += 30; }

    public function render()
    {
        $query = User::where('role', 'klant')
            ->when($this->zoekterm, fn($q) => $q->where(fn($q2) =>
                $q2->where('name', 'like', "%{$this->zoekterm}%")
                   ->orWhere('email', 'like', "%{$this->zoekterm}%")
            ))
            ->withCount('afspraken as totaal_afspraken')
            ->with(['afspraken' => fn($q) => $q->orderByDesc('datum')->limit(1)])
            ->orderByDesc('created_at');

        $totaal  = $query->count();
        $klanten = $query->limit($this->limite)->get();
        $heeftMeer = $totaal > $this->limite;

        return view('livewire.admin.klanten-overzicht', compact('klanten', 'totaal', 'heeftMeer'))
            ->layout('layouts.admin');
    }
}
