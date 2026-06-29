<?php

namespace App\Mail;

use App\Models\Kapper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbonnementBetalingMisluktMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Kapper $kapper) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Betaling van je Kaply abonnement is mislukt');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.abonnement-betaling-mislukt');
    }
}
