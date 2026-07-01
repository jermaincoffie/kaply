<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelkomstMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welkom bij Kaply! 🎉');
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.kapper.welkomst', with: [
            'naam'      => $this->user->name,
            'salonNaam' => $this->user->kapper?->salon_naam ?? $this->user->name,
            'dashboardUrl' => route('kapper.dashboard'),
        ]);
    }

    public function attachments(): array
    {
        return [];
    }
}
