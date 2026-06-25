<?php

namespace App\Mail;

use App\Models\Afspraak;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewUitnodigingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Afspraak $afspraak) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Hoe was je bezoek bij ' . $this->afspraak->kapper->salon_naam . '? ⭐');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.review-uitnodiging');
    }
}
