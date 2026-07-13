<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KapperGoedgekeurdMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $salonNaam) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Je salon staat live op Kaply! 🎉');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.kapper-goedgekeurd');
    }
}
