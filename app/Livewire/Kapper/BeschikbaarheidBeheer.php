<?php

namespace App\Livewire\Kapper;

use App\Models\Beschikbaarheid;
use App\Models\Sluitingsdag;
use Livewire\Component;

class BeschikbaarheidBeheer extends Component
{
    public array $rooster = [];
    public string $sluitingsDatum = '';
    public string $sluitingsDatumTot = '';
    public string $sluitingsReden = '';

    protected array $dagNamen = ['Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag'];

    public function mount(): void
    {
        $kapper = auth()->user()->kapper;
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
        $kapper = auth()->user()->kapper;
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
