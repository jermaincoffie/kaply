<?php

namespace App\Notifications;

use App\Models\Afspraak;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AfspraakGeannuleerdNotificatie extends Notification
{
    use Queueable;

    public function __construct(public Afspraak $afspraak) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'afspraak_geannuleerd',
            'afspraak_id' => $this->afspraak->id,
            'klant_naam'  => $this->afspraak->klant?->name ?? $this->afspraak->walk_in_naam ?? 'Onbekend',
            'dienst_naam' => $this->afspraak->dienst?->naam ?? '—',
            'datum'       => $this->afspraak->datum->format('d-m-Y'),
            'start_tijd'  => $this->afspraak->start_tijd,
        ];
    }
}
