<?php

namespace App\Livewire\Kapper;

use App\Models\Medewerker;
use App\Models\MedewerkerBeschikbaarheid;
use Livewire\Component;
use Livewire\WithFileUploads;

class MedewerkersBeheer extends Component
{
    use WithFileUploads;

    public string $naam = '';
    public $foto = null;
    public bool $toonFormulier = false;

    public ?int $openRoosterId = null;
    public array $medewerkerRooster = [];

    protected array $dagNamen = ['Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag'];

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

        if ($this->openRoosterId === $id) {
            $this->openRoosterId    = null;
            $this->medewerkerRooster = [];
        }
    }

    public function openRooster(int $id): void
    {
        if ($this->openRoosterId === $id) {
            $this->openRoosterId    = null;
            $this->medewerkerRooster = [];
            return;
        }

        $medewerker = Medewerker::where('id', $id)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->firstOrFail();

        $bestaand = $medewerker->beschikbaarheden()->get()->keyBy('dag_van_week');

        $this->medewerkerRooster = [];
        for ($dag = 0; $dag <= 6; $dag++) {
            $this->medewerkerRooster[$dag] = [
                'naam'       => $this->dagNamen[$dag],
                'actief'     => isset($bestaand[$dag]),
                'start_tijd' => $bestaand[$dag]->start_tijd ?? '09:00',
                'eind_tijd'  => $bestaand[$dag]->eind_tijd  ?? '17:00',
            ];
        }

        $this->openRoosterId = $id;
    }

    public function slaRoosterOp(): void
    {
        if (!$this->openRoosterId) return;

        $medewerker = Medewerker::where('id', $this->openRoosterId)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->firstOrFail();

        $medewerker->beschikbaarheden()->delete();

        foreach ($this->medewerkerRooster as $dag => $data) {
            if ($data['actief']) {
                MedewerkerBeschikbaarheid::create([
                    'medewerker_id' => $medewerker->id,
                    'dag_van_week'  => $dag,
                    'start_tijd'    => $data['start_tijd'],
                    'eind_tijd'     => $data['eind_tijd'],
                ]);
            }
        }

        session()->flash('rooster_opgeslagen', $medewerker->naam);
        $this->openRoosterId    = null;
        $this->medewerkerRooster = [];
    }

    public function render()
    {
        return view('livewire.kapper.medewerkers-beheer', [
            'medewerkers' => Medewerker::where('kapper_id', auth()->user()->kapper->id)
                ->withCount('beschikbaarheden')
                ->orderBy('naam')
                ->get(),
        ])->layout('layouts.kapper', ['title' => 'Medewerkers']);
    }
}
