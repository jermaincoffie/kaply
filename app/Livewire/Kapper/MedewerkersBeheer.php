<?php

namespace App\Livewire\Kapper;

use App\Models\Medewerker;
use Livewire\Component;
use Livewire\WithFileUploads;

class MedewerkersBeheer extends Component
{
    use WithFileUploads;

    public string $naam = '';
    public $foto = null;
    public bool $toonFormulier = false;

    public function openFormulier(): void { $this->toonFormulier = true; }
    public function sluitFormulier(): void { $this->toonFormulier = false; $this->reset(['naam', 'foto']); }

    public function updatedFoto(): void
    {
        $this->validateOnly('foto', ['foto' => 'nullable|image|max:2048']);
    }

    public function toevoegen(): void
    {
        $this->validate(['naam' => 'required|string|max:100']);

        $data = ['kapper_id' => auth()->user()->kapper->id, 'naam' => $this->naam];

        if ($this->foto) {
            $data['foto'] = $this->foto->store('medewerker-fotos', 'public');
        }

        Medewerker::create($data);

        $this->naam = '';
        $this->foto = null;
        $this->toonFormulier = false;
    }

    public function toggleActief(int $id): void
    {
        $medewerker = Medewerker::where('id', $id)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->firstOrFail();
        $medewerker->update(['actief' => !$medewerker->actief]);
    }

    public function verwijder(int $id): void
    {
        Medewerker::where('id', $id)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->delete();
    }

    public function render()
    {
        return view('livewire.kapper.medewerkers-beheer', [
            'medewerkers' => Medewerker::where('kapper_id', auth()->user()->kapper->id)
                ->orderBy('naam')
                ->get(),
        ])->layout('layouts.kapper', ['title' => 'Medewerkers']);
    }
}
