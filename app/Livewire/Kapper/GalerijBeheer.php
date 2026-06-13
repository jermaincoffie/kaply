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

    protected function rules(): array
    {
        return [
            'nieuwefotos.*' => 'image|max:4096',
        ];
    }

    public function uploaden(): void
    {
        if (empty($this->nieuwefotos)) {
            $this->addError('nieuwefotos', 'Geen foto\'s geselecteerd.');
            return;
        }

        $this->validate();

        $kapper = auth()->user()->kapper;
        $aantalBestaand = $kapper->galerij()->count();

        if ($aantalBestaand + count($this->nieuwefotos) > 12) {
            $this->addError('nieuwefotos', 'Maximaal 12 foto\'s toegestaan.');
            return;
        }

        foreach ($this->nieuwefotos as $i => $foto) {
            $pad = $foto->store('galerij', 'public');
            KapperGalerij::create([
                'kapper_id' => $kapper->id,
                'pad'       => $pad,
                'volgorde'  => $aantalBestaand + $i,
            ]);
        }

        $this->nieuwefotos = [];
        $this->succesmelding = 'Foto\'s opgeslagen!';
        $this->dispatch('fotos-geupload');
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
