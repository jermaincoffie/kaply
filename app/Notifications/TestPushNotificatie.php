<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class TestPushNotificatie extends Notification
{
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('Push werkt! ✅')
            ->body('Test melding van Kaply — ' . now()->format('H:i:s'))
            ->icon('/images/PWA-icon-192.png')
            ->ttl(3600)
            ->data(['url' => '/kapper/account']);
    }
}
