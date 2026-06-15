<?php

namespace App\Livewire\Kapper;

use App\Models\Beschikbaarheid;
use Livewire\Component;

class OnboardingWizard extends Component
{
    public int $stap = 1;

    // Stap 2: dienst
    public string $dienstNaam  = '';
    public string $dienstDuur  = '30';
    public string $dienstPrijs = '';

    // Stap 3: beschikbaarheid
    public array $rooster      = [];
    public int   $bufferMinuten = 0;

    protected array $dagNamen = ['Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag'];

    public function mount(): void
    {
        for ($dag = 0; $dag <= 6; $dag++) {
            $this->rooster[$dag] = [
                'naam'       => $this->dagNamen[$dag],
                'actief'     => $dag <= 4,
                'start_tijd' => '09:00',
                'eind_tijd'  => '17:00',
            ];
        }
    }

    public function dienstToevoegen(): void
    {
        $this->validate([
            'dienstNaam'  => 'required|string|max:100',
            'dienstDuur'  => 'required|integer|min:5|max:480',
            'dienstPrijs' => 'required|numeric|min:0',
        ]);

        auth()->user()->kapper->diensten()->create([
            'naam'          => $this->dienstNaam,
            'duur_minuten'  => (int) $this->dienstDuur,
            'prijs'         => (int) round((float) str_replace(',', '.', $this->dienstPrijs) * 100),
        ]);

        $this->reset(['dienstNaam', 'dienstPrijs']);
        $this->dienstDuur = '30';
    }

    public function dienstVerwijderen(int $id): void
    {
        auth()->user()->kapper->diensten()->where('id', $id)->delete();
    }

    public function naarStap(int $stap): void
    {
        if ($stap === 3 && auth()->user()->kapper->diensten()->count() === 0) {
            $this->addError('dienst', 'Voeg minstens één dienst toe om door te gaan.');
            return;
        }
        $this->stap = $stap;
    }

    public function afronden(): void
    {
        $kapper = auth()->user()->kapper;
        $kapper->beschikbaarheden()->delete();

        foreach ($this->rooster as $dag => $data) {
            if ($data['actief']) {
                Beschikbaarheid::create([
                    'kapper_id'    => $kapper->id,
                    'dag_van_week' => $dag,
                    'start_tijd'   => $data['start_tijd'],
                    'eind_tijd'    => $data['eind_tijd'],
                ]);
            }
        }

        $kapper->update([
            'buffer_minuten'      => $this->bufferMinuten,
            'onboarding_voltooid' => true,
        ]);

        session()->flash('onboarding_klaar', true);
        $this->redirect(route('kapper.dashboard'));
    }

    public function render()
    {
        $diensten = auth()->user()->kapper->diensten()->get();

        return view('livewire.kapper.onboarding-wizard', compact('diensten'))
            ->layout('layouts.kapper-onboarding');
    }
}
