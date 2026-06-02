<?php

namespace App\Livewire\Kapper;

use App\Models\Dienst;
use Livewire\Component;

class DienstenBeheer extends Component
{
    public string $naam = '';
    public int $duur_minuten = 30;
    public string $prijs = '';
    public string $no_show_bedrag = '0.00';
    public ?int $bewerkenId = null;

    protected function rules(): array
    {
        return [
            'naam' => 'required|string|max:255',
            'duur_minuten' => 'required|integer|min:5|max:480',
            'prijs' => 'required|numeric|min:0',
            'no_show_bedrag' => 'required|numeric|min:0',
        ];
    }

    public function opslaan(): void
    {
        $this->validate();
        $kapper = auth()->user()->kapper;
        $data = [
            'naam' => $this->naam,
            'duur_minuten' => $this->duur_minuten,
            'prijs' => (int) round((float) $this->prijs * 100),
            'no_show_bedrag' => (int) round((float) $this->no_show_bedrag * 100),
        ];

        if ($this->bewerkenId) {
            Dienst::where('id', $this->bewerkenId)->where('kapper_id', $kapper->id)->update($data);
        } else {
            $kapper->diensten()->create($data);
        }

        $this->reset(['naam', 'duur_minuten', 'prijs', 'no_show_bedrag', 'bewerkenId']);
        $this->duur_minuten = 30;
        $this->no_show_bedrag = '0.00';
    }

    public function bewerk(int $id): void
    {
        $dienst = Dienst::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->firstOrFail();
        $this->bewerkenId = $dienst->id;
        $this->naam = $dienst->naam;
        $this->duur_minuten = $dienst->duur_minuten;
        $this->prijs = number_format($dienst->prijs / 100, 2, '.', '');
        $this->no_show_bedrag = number_format($dienst->no_show_bedrag / 100, 2, '.', '');
    }

    public function verwijder(int $id): void
    {
        Dienst::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->delete();
    }

    public function render()
    {
        return view('livewire.kapper.diensten-beheer', [
            'diensten' => auth()->user()->kapper->diensten()->orderBy('naam')->get(),
        ])->layout('layouts.kapper');
    }
}
