<?php

namespace App\Livewire\Kapper;

use Livewire\Component;

class NotificatieBel extends Component
{
    public bool $open = false;

    public function toggle(): void
    {
        $this->open = !$this->open;

        if ($this->open) {
            auth()->user()->unreadNotifications->markAsRead();
        }
    }

    public function render()
    {
        $notificaties = auth()->user()
            ->notifications()
            ->latest()
            ->limit(15)
            ->get();

        $ongelezen = auth()->user()->unreadNotifications()->count();

        return view('livewire.kapper.notificatie-bel', compact('notificaties', 'ongelezen'));
    }
}
