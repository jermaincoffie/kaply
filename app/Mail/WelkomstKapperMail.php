<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelkomstKapperMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $salonNaam) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welkom bij Kaply – ' . $this->salonNaam);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.welkomst-kapper');
    }
}
