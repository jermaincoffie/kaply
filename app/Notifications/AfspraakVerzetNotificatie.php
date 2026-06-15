<?php

namespace App\Notifications;

use App\Models\Afspraak;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AfspraakVerzetNotificatie extends Notification
{
    use Queueable;

    public function __construct(
        public Afspraak $afspraak,
        public string $oudeDatum,
        public string $oudeTijd,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'afspraak_verzet',
            'afspraak_id' => $this->afspraak->id,
            'klant_naam'  => $this->afspraak->klant?->name ?? 'Onbekend',
            'dienst_naam' => $this->afspraak->dienst?->naam ?? '—',
            'datum'       => $this->afspraak->datum->format('d-m-Y'),
            'start_tijd'  => $this->afspraak->start_tijd,
            'oude_datum'  => $this->oudeDatum,
            'oude_tijd'   => $this->oudeTijd,
        ];
    }
}
