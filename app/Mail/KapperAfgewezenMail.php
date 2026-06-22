<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KapperAfgewezenMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $naam, public string $salonNaam) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Je aanvraag voor Kaply is niet goedgekeurd');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.kapper-afgewezen');
    }
}
