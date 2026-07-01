<?php

namespace App\Livewire\Kapper;

use Livewire\Component;

class NotificatieBel extends Component
{
    public int $vorigeOngelezen = -1;

    public function markAlsGelezen(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function pollen(): void
    {
        $ongelezen = auth()->user()->unreadNotifications()->count();

        if ($this->vorigeOngelezen !== -1 && $ongelezen > $this->vorigeOngelezen) {
            $nieuwste = auth()->user()->unreadNotifications()->latest()->first();
            $this->dispatch('nieuwe-notificatie',
                naam:   $nieuwste?->data['klant_naam']  ?? 'Klant',
                dienst: $nieuwste?->data['dienst_naam'] ?? '',
                tijd:   $nieuwste?->data['start_tijd']  ?? '',
            );
        }

        $this->vorigeOngelezen = $ongelezen;
    }

    public function render()
    {
        $notificaties = auth()->user()->notifications()->latest()->limit(15)->get();
        $ongelezen    = auth()->user()->unreadNotifications()->count();

        if ($this->vorigeOngelezen === -1) {
            $this->vorigeOngelezen = $ongelezen;
        }

        return view('livewire.kapper.notificatie-bel', compact('notificaties', 'ongelezen'));
    }
}
