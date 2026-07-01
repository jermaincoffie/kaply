<?php

namespace App\Livewire\Kapper;

use App\Models\Beschikbaarheid;
use App\Models\KapperGalerij;
use Livewire\Component;
use Livewire\WithFileUploads;

class OnboardingWizard extends Component
{
    use WithFileUploads;

    public int $stap = 1;

    // Stap 2: profiel + locatie
    public string $salonNaam = '';
    public string $stad      = '';
    public string $adres     = '';
    public string $telefoon  = '';
    public string $bio       = '';

    // Stap 3: dienst
    public string $dienstNaam        = '';
    public string $dienstDuur        = '30';
    public string $dienstPrijs       = '';
    public string $dienstNoShowBedrag = '0.00';

    // Stap 4: beschikbaarheid
    public array  $rooster              = [];
    public int    $bufferMinuten        = 0;
    public int    $vooruitboekenMaanden = 2;
    public string $annuleringUren       = '';
    public string $annuleringKosten     = '';

    // Stap 5: media (optioneel)
    public $foto             = null;
    public array $galerijFotos = [];

    protected array $dagNamen = ['Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag'];

    public function mount(): void
    {
        $kapper = auth()->user()->kapper;
        $this->salonNaam = $kapper->salon_naam ?? '';
        $this->stad      = $kapper->stad ?? '';
        $this->adres     = $kapper->adres ?? '';
        $this->telefoon  = $kapper->telefoon ?? '';
        $this->bio       = $kapper->bio ?? '';

        for ($dag = 0; $dag <= 6; $dag++) {
            $this->rooster[$dag] = [
                'naam'       => $this->dagNamen[$dag],
                'actief'     => $dag <= 4,
                'start_tijd' => '09:00',
                'eind_tijd'  => '17:00',
            ];
        }
    }

    public function naarStap(int $stap): void
    {
        if ($stap === 3) {
            $this->validate([
                'salonNaam' => 'required|string|max:255',
                'stad'      => 'required|string|max:255',
                'adres'     => 'nullable|string|max:255',
                'telefoon'  => 'nullable|string|max:20',
                'bio'       => 'nullable|string|max:1000',
            ]);
            auth()->user()->kapper->update([
                'salon_naam' => $this->salonNaam,
                'stad'       => $this->stad,
                'adres'      => $this->adres ?: null,
                'telefoon'   => $this->telefoon ?: null,
                'bio'        => $this->bio ?: null,
            ]);
        }

        if ($stap === 4 && auth()->user()->kapper->diensten()->count() === 0) {
            $this->addError('dienst', 'Voeg minstens één dienst toe om door te gaan.');
            return;
        }

        $this->stap = $stap;
    }

    public function dienstToevoegen(): void
    {
        $this->validate([
            'dienstNaam'         => 'required|string|max:100',
            'dienstDuur'         => 'required|integer|min:5|max:480',
            'dienstPrijs'        => 'required|numeric|min:0',
            'dienstNoShowBedrag' => 'required|numeric|min:0',
        ]);

        auth()->user()->kapper->diensten()->create([
            'naam'           => $this->dienstNaam,
            'duur_minuten'   => (int) $this->dienstDuur,
            'prijs'          => (int) round((float) str_replace(',', '.', $this->dienstPrijs) * 100),
            'no_show_bedrag' => (int) round((float) str_replace(',', '.', $this->dienstNoShowBedrag) * 100),
        ]);

        $this->reset(['dienstNaam', 'dienstPrijs', 'dienstNoShowBedrag']);
        $this->dienstDuur = '30';
        $this->dienstNoShowBedrag = '0.00';
    }

    public function dienstVerwijderen(int $id): void
    {
        auth()->user()->kapper->diensten()->where('id', $id)->delete();
    }

    public function slaMediaOpEnNaarStap6(): void
    {
        $this->validate([
            'foto'           => 'nullable|image|max:2048',
            'galerijFotos'   => 'nullable|array',
            'galerijFotos.*' => 'image|max:4096',
        ]);

        $kapper = auth()->user()->kapper;

        if ($this->foto) {
            $pad = $this->foto->store('kapper-fotos', 'public');
            $kapper->update(['foto' => $pad]);
            $this->foto = null;
        }

        if (!empty($this->galerijFotos)) {
            $volgorde = $kapper->galerij()->count();
            foreach ($this->galerijFotos as $gFoto) {
                if ($volgorde >= 12) break;
                $pad = $gFoto->store('galerij', 'public');
                KapperGalerij::create([
                    'kapper_id' => $kapper->id,
                    'pad'       => $pad,
                    'volgorde'  => $volgorde,
                ]);
                $volgorde++;
            }
        }

        $this->stap = 6;
    }

    public function afronden(): void
    {
        $this->voltooien();
    }

    public function voltooienEnStripe(): void
    {
        $this->slaOnboardingOp();
        $this->redirect(route('kapper.stripe.onboard', ['from' => 'onboarding']));
    }

    private function slaOnboardingOp(): void
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
            'buffer_minuten'        => $this->bufferMinuten,
            'vooruitboeken_maanden' => $this->vooruitboekenMaanden,
            'annulering_uren'       => $this->annuleringUren !== '' ? (int) $this->annuleringUren : null,
            'annulering_kosten'     => $this->annuleringKosten !== '' ? (int) round((float) $this->annuleringKosten * 100) : null,
            'onboarding_voltooid'   => true,
        ]);
    }

    private function voltooien(): void
    {
        $this->slaOnboardingOp();
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
