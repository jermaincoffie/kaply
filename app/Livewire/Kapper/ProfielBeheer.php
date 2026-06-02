<?php

namespace App\Livewire\Kapper;

use Livewire\Component;

class ProfielBeheer extends Component
{
    public string $salon_naam = '';
    public string $adres = '';
    public string $stad = '';
    public string $telefoon = '';
    public string $bio = '';

    public function mount(): void
    {
        $kapper = auth()->user()->kapper;
        $this->salon_naam = $kapper->salon_naam;
        $this->adres = $kapper->adres ?? '';
        $this->stad = $kapper->stad;
        $this->telefoon = $kapper->telefoon ?? '';
        $this->bio = $kapper->bio ?? '';
    }

    protected function rules(): array
    {
        return [
            'salon_naam' => 'required|string|max:255',
            'adres' => 'nullable|string|max:255',
            'stad' => 'required|string|max:255',
            'telefoon' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ];
    }

    public function opslaan(): void
    {
        $this->validate();
        auth()->user()->kapper->update([
            'salon_naam' => $this->salon_naam,
            'adres' => $this->adres ?: null,
            'stad' => $this->stad,
            'telefoon' => $this->telefoon ?: null,
            'bio' => $this->bio ?: null,
        ]);
        session()->flash('message', 'Profiel opgeslagen.');
    }

    public function render()
    {
        return view('livewire.kapper.profiel-beheer')->layout('layouts.kapper');
    }
}
