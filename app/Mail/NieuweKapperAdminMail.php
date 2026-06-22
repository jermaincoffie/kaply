<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NieuweKapperAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $naam,
        public string $salonNaam,
        public string $stad,
        public string $email,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Nieuwe kapper aangemeld: ' . $this->salonNaam);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.nieuwe-kapper-admin');
    }
}
