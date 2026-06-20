<?php

namespace App\Mail;

use App\Models\Kapper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WachtlijstNotificatieMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Kapper $kapper) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Er is een plek vrijgekomen bij ' . $this->kapper->salon_naam);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.wachtlijst-notificatie', text: 'emails.text.wachtlijst-notificatie');
    }
}
