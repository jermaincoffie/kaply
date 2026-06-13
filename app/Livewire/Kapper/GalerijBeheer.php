<?php

namespace App\Livewire\Kapper;

use App\Models\KapperGalerij;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class GalerijBeheer extends Component
{
    use WithFileUploads;

    public array $nieuwefotos = [];
    public string $succesmelding = '';

    public function uploaden(): void
    {
        $this->succesmelding = '';
        $this->resetErrorBag();

        if (empty($this->nieuwefotos)) {
            $this->addError('nieuwefotos', 'Geen foto\'s geselecteerd. Wacht tot de foto\'s geladen zijn.');
            return;
        }

        $kapper = auth()->user()->kapper;
        $aantalBestaand = $kapper->galerij()->count();
        $volgorde = $aantalBestaand;
        $geupload = 0;
        $teGroot = 0;
        $teVeel = 0;

        foreach ($this->nieuwefotos as $foto) {
            if ($volgorde >= 12) {
                $teVeel++;
                continue;
            }

            if ($foto->getSize() > 4 * 1024 * 1024) {
                $teGroot++;
                continue;
            }

            $pad = $foto->store('galerij', 'public');
            KapperGalerij::create([
                'kapper_id' => $kapper->id,
                'pad'       => $pad,
                'volgorde'  => $volgorde,
            ]);
            $volgorde++;
            $geupload++;
        }

        $this->nieuwefotos = [];
        $this->dispatch('fotos-geupload');

        $meldingen = [];
        if ($geupload > 0) $meldingen[] = $geupload . ' foto' . ($geupload > 1 ? '\'s' : '') . ' opgeslagen';
        if ($teGroot > 0) $meldingen[] = $teGroot . ' foto' . ($teGroot > 1 ? '\'s' : '') . ' overgeslagen (groter dan 4MB)';
        if ($teVeel > 0) $meldingen[] = $teVeel . ' foto' . ($teVeel > 1 ? '\'s' : '') . ' overgeslagen (max 12 bereikt)';

        $this->succesmelding = implode(' · ', $meldingen) ?: 'Niets geüpload.';
    }

    public function verwijderen(int $id): void
    {
        $foto = KapperGalerij::where('id', $id)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->firstOrFail();

        Storage::disk('public')->delete($foto->pad);
        $foto->delete();
    }

    public function render()
    {
        return view('livewire.kapper.galerij-beheer', [
            'fotos' => auth()->user()->kapper->galerij()->get(),
        ])->layout('layouts.kapper', ['title' => 'Galerij']);
    }
}
