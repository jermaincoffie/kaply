<?php

namespace App\Mail;

use App\Models\Afspraak;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NoShowMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Afspraak $afspraak,
        public ?string $checkoutUrl = null
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->checkoutUrl
            ? 'No-show fee – ' . $this->afspraak->kapper->salon_naam
            : 'Je was er niet bij | ' . $this->afspraak->kapper->salon_naam;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.no-show', text: 'emails.text.no-show');
    }
}
