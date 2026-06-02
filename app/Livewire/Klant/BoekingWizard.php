<?php

namespace App\Livewire\Klant;

use App\Models\Afspraak;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Livewire\Component;

class BoekingWizard extends Component
{
    public Kapper $kapper;
    public Dienst $dienst;
    public string $gekozenDatum = '';
    public string $gekozenTijdslot = '';
    public string $betaalmethode = 'in_zaak';

    public function mount(string $kapperSlug, int $dienstId): void
    {
        $this->kapper = Kapper::where('slug', $kapperSlug)->where('actief', true)->firstOrFail();
        $this->dienst = Dienst::where('id', $dienstId)->where('kapper_id', $this->kapper->id)->firstOrFail();
        $this->gekozenDatum = Carbon::now()->addDay()->toDateString();
    }

    public function bevestig(): void
    {
        $this->validate([
            'gekozenDatum' => 'required|date|after_or_equal:today',
            'gekozenTijdslot' => 'required|string',
            'betaalmethode' => 'required|in:online,in_zaak',
        ]);

        $eind = Carbon::parse("{$this->gekozenDatum} {$this->gekozenTijdslot}")
            ->addMinutes($this->dienst->duur_minuten)
            ->format('H:i');

        Afspraak::create([
            'klant_id' => auth()->id(),
            'kapper_id' => $this->kapper->id,
            'dienst_id' => $this->dienst->id,
            'datum' => $this->gekozenDatum,
            'start_tijd' => $this->gekozenTijdslot,
            'eind_tijd' => $eind,
            'status' => 'gepland',
            'betaalmethode' => $this->betaalmethode,
        ]);

        session()->flash('boeking_bevestigd', true);
        $this->redirect(route('klant.afspraken'));
    }

    public function render()
    {
        $vrijeslots = $this->gekozenDatum
            ? (new BeschikbaarheidsService())->getVrijeTijdslots($this->kapper, $this->dienst, $this->gekozenDatum)
            : [];

        return view('livewire.klant.boeking-wizard', compact('vrijeslots'))->layout('layouts.publiek');
    }
}
