<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class KlantenOverzicht extends Component
{
    use WithPagination;

    public string $zoekterm = '';

    public function updatingZoekterm(): void { $this->resetPage(); }

    public function render()
    {
        $klanten = User::where('role', 'klant')
            ->when($this->zoekterm, fn($q) => $q->where(function ($q2) {
                $q2->where('name', 'like', "%{$this->zoekterm}%")
                   ->orWhere('email', 'like', "%{$this->zoekterm}%");
            }))
            ->withCount('afspraken as totaal_afspraken')
            ->with(['afspraken' => fn($q) => $q->orderByDesc('datum')->limit(1)])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('livewire.admin.klanten-overzicht', compact('klanten'))
            ->layout('layouts.admin');
    }
}
