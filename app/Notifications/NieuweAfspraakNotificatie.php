<?php

namespace App\Notifications;

use App\Models\Afspraak;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NieuweAfspraakNotificatie extends Notification
{
    use Queueable;

    public function __construct(public Afspraak $afspraak) {}

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        $klant  = $this->afspraak->klant?->name ?? $this->afspraak->walk_in_naam ?? 'Klant';
        $dienst = $this->afspraak->dienst?->naam ?? '';
        $tijd   = $this->afspraak->start_tijd;

        return WebPushMessage::create()
            ->title('Nieuwe afspraak! 📅')
            ->body("{$klant} · {$dienst} om {$tijd}")
            ->icon('/images/PWA-icon-192.png')
            ->badge('/images/PWA-icon-192.png')
            ->data(['url' => '/agenda']);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'nieuwe_afspraak',
            'afspraak_id'  => $this->afspraak->id,
            'klant_naam'   => $this->afspraak->klant?->name ?? $this->afspraak->walk_in_naam ?? 'Onbekend',
            'dienst_naam'  => $this->afspraak->dienst?->naam ?? '—',
            'datum'        => $this->afspraak->datum->format('d-m-Y'),
            'start_tijd'   => $this->afspraak->start_tijd,
        ];
    }
}
