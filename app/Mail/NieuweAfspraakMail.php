<?php

namespace App\Mail;

use App\Models\Afspraak;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NieuweAfspraakMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $uitschrijfUrl;

    public function __construct(public Afspraak $afspraak, public User $kapper)
    {
        $this->uitschrijfUrl = URL::signedRoute('notificaties.uitschrijven', ['user' => $kapper->id]);
    }

    public function envelope(): Envelope
    {
        $klant = $this->afspraak->klant?->name ?? $this->afspraak->walk_in_naam ?? 'Klant';
        return new Envelope(
            subject: "Nieuwe afspraak: {$klant}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.kapper.nieuwe-afspraak',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
