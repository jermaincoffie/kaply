<?php

namespace App\Livewire\Kapper;

use App\Models\Beschikbaarheid;
use App\Models\Sluitingsdag;
use Illuminate\Support\Str;
use Livewire\Component;

class BeschikbaarheidBeheer extends Component
{
    public array $rooster = [];
    public int $bufferMinuten = 0;
    public int $vooruitboekenMaanden = 2;
    public string $annuleringUren = '';
    public string $annuleringKosten = '';
    public string $sluitingsDatum = '';
    public string $sluitingsDatumTot = '';
    public string $sluitingsReden = '';
    public string $icalUrl = '';

    protected array $dagNamen = ['Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag'];

    public function mount(): void
    {
        $kapper = auth()->user()->kapper;

        if (!$kapper->ical_token) {
            $kapper->update(['ical_token' => Str::random(40)]);
        }
        $this->icalUrl = route('kapper.ical', $kapper->ical_token);

        $this->bufferMinuten = (int) ($kapper->buffer_minuten ?? 0);
        $this->vooruitboekenMaanden = (int) ($kapper->vooruitboeken_maanden ?? 2);
        $this->annuleringUren = $kapper->annulering_uren !== null ? (string) $kapper->annulering_uren : '';
        $this->annuleringKosten = $kapper->annulering_kosten ? number_format($kapper->annulering_kosten / 100, 2, '.', '') : '';
        $bestaand = $kapper->beschikbaarheden()->get()->keyBy('dag_van_week');

        for ($dag = 0; $dag <= 6; $dag++) {
            $this->rooster[$dag] = [
                'naam' => $this->dagNamen[$dag],
                'actief' => isset($bestaand[$dag]),
                'start_tijd' => $bestaand[$dag]->start_tijd ?? '09:00',
                'eind_tijd' => $bestaand[$dag]->eind_tijd ?? '17:00',
            ];
        }
    }

    public function opslaan(): void
    {
        $this->validate([
            'bufferMinuten'        => 'integer|min:0|max:60',
            'vooruitboekenMaanden' => 'integer|in:1,2,3,6',
            'annuleringUren'       => 'nullable|integer|in:1,2,4,8,12,24,48',
            'annuleringKosten'     => 'nullable|numeric|min:0|max:999',
        ]);

        $kapper = auth()->user()->kapper;
        $kapper->update([
            'buffer_minuten'        => $this->bufferMinuten,
            'vooruitboeken_maanden' => $this->vooruitboekenMaanden,
            'annulering_uren'       => $this->annuleringUren !== '' ? (int) $this->annuleringUren : null,
            'annulering_kosten'     => $this->annuleringKosten !== '' ? (int) round((float) $this->annuleringKosten * 100) : null,
        ]);
        $kapper->beschikbaarheden()->delete();

        foreach ($this->rooster as $dag => $data) {
            if ($data['actief']) {
                Beschikbaarheid::create([
                    'kapper_id' => $kapper->id,
                    'dag_van_week' => $dag,
                    'start_tijd' => $data['start_tijd'],
                    'eind_tijd' => $data['eind_tijd'],
                ]);
            }
        }

        session()->flash('message', 'Beschikbaarheid opgeslagen.');
    }

    public function sluitingsdagToevoegen(): void
    {
        $this->validate([
            'sluitingsDatum'    => 'required|date|after_or_equal:today',
            'sluitingsDatumTot' => 'nullable|date|after_or_equal:sluitingsDatum',
        ]);

        auth()->user()->kapper->sluitingsdagen()->create([
            'datum'     => $this->sluitingsDatum,
            'datum_tot' => $this->sluitingsDatumTot ?: null,
            'reden'     => $this->sluitingsReden ?: null,
        ]);

        $this->reset(['sluitingsDatum', 'sluitingsDatumTot', 'sluitingsReden']);
    }

    public function sluitingsdagVerwijderen(int $id): void
    {
        Sluitingsdag::where('id', $id)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->delete();
    }

    public function render()
    {
        return view('livewire.kapper.beschikbaarheid-beheer', [
            'sluitingsdagen' => auth()->user()->kapper->sluitingsdagen()
                ->where(fn($q) => $q->where('datum', '>=', today())->orWhere('datum_tot', '>=', today()))
                ->orderBy('datum')
                ->get(),
        ])->layout('layouts.kapper');
    }
}
