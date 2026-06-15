<?php

namespace App\Livewire\Kapper;

use App\Models\Kortingscode;
use Livewire\Component;

class KortingscodesBeheer extends Component
{
    public string $code       = '';
    public string $type       = 'percentage';
    public string $waarde     = '';
    public string $maxGebruik = '';
    public string $geldigVan  = '';
    public string $geldigTot  = '';

    public function aanmaken(): void
    {
        $this->validate([
            'code'       => 'required|string|max:50|alpha_dash',
            'type'       => 'required|in:percentage,vast',
            'waarde'     => 'required|integer|min:1',
            'maxGebruik' => 'nullable|integer|min:1',
            'geldigVan'  => 'nullable|date',
            'geldigTot'  => 'nullable|date|after_or_equal:geldigVan',
        ]);

        $kapper = auth()->user()->kapper;

        $bestaatAl = $kapper->kortingscodes()
            ->whereRaw('UPPER(code) = ?', [strtoupper($this->code)])
            ->exists();

        if ($bestaatAl) {
            $this->addError('code', 'Deze code bestaat al voor jouw salon.');
            return;
        }

        $waarde = (int) $this->waarde;
        if ($this->type === 'percentage' && $waarde > 100) {
            $this->addError('waarde', 'Percentage mag niet hoger dan 100 zijn.');
            return;
        }
        if ($this->type === 'vast') {
            $waarde = $waarde * 100; // euros naar centen
        }

        $kapper->kortingscodes()->create([
            'code'        => strtoupper(trim($this->code)),
            'type'        => $this->type,
            'waarde'      => $waarde,
            'max_gebruik' => $this->maxGebruik !== '' ? (int) $this->maxGebruik : null,
            'geldig_van'  => $this->geldigVan ?: null,
            'geldig_tot'  => $this->geldigTot ?: null,
            'actief'      => true,
        ]);

        $this->reset(['code', 'waarde', 'maxGebruik', 'geldigVan', 'geldigTot']);
        session()->flash('message', 'Kortingscode aangemaakt.');
    }

    public function toggleActief(int $id): void
    {
        $code = Kortingscode::where('id', $id)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->firstOrFail();
        $code->update(['actief' => !$code->actief]);
    }

    public function verwijderen(int $id): void
    {
        Kortingscode::where('id', $id)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->delete();
    }

    public function render()
    {
        return view('livewire.kapper.kortingscodes-beheer', [
            'codes' => auth()->user()->kapper->kortingscodes()
                ->withCount('afspraken')
                ->latest()
                ->get(),
        ])->layout('layouts.kapper');
    }
}
