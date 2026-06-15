<?php

namespace App\Mail;

use App\Models\Afspraak;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AfspraakVerzetKapperMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Afspraak $afspraak,
        public string $oudeDatum,
        public string $oudeTijd,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Afspraak verzet door klant – ' . $this->afspraak->kapper->salon_naam);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.afspraak-verzet-kapper');
    }
}
