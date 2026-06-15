<?php

namespace App\Http\Controllers;

use App\Models\Kapper;
use Carbon\Carbon;
use Illuminate\Http\Response;

class IcalController extends Controller
{
    public function feed(string $token): Response
    {
        $kapper = Kapper::where('ical_token', $token)->firstOrFail();

        $afspraken = $kapper->afspraken()
            ->whereIn('status', ['gepland', 'voltooid'])
            ->where('datum', '>=', today()->subMonths(2))
            ->with(['klant', 'dienst'])
            ->orderBy('datum')
            ->orderBy('start_tijd')
            ->get();

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Kaply//Afspraken//NL',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'X-WR-CALNAME:' . $this->esc($kapper->salon_naam . ' — Afspraken'),
            'X-WR-TIMEZONE:Europe/Amsterdam',
            'X-WR-CALDESC:Afspraken via Kaply',
        ];

        foreach ($afspraken as $afspraak) {
            $start = Carbon::parse($afspraak->datum->format('Y-m-d') . ' ' . $afspraak->start_tijd, 'Europe/Amsterdam');
            $end   = Carbon::parse($afspraak->datum->format('Y-m-d') . ' ' . $afspraak->eind_tijd, 'Europe/Amsterdam');

            $beschrijving = 'Dienst: ' . $afspraak->dienst->naam
                . '\nPrijs: €' . $afspraak->dienst->prijs_in_euros
                . '\nBetaling: ' . ($afspraak->betaalmethode === 'online' ? 'Online' : 'In zaak');

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:kaply-' . $afspraak->id . '@kaply.nl';
            $lines[] = 'DTSTAMP:' . now()->utc()->format('Ymd\THis\Z');
            $lines[] = 'DTSTART;TZID=Europe/Amsterdam:' . $start->format('Ymd\THis');
            $lines[] = 'DTEND;TZID=Europe/Amsterdam:' . $end->format('Ymd\THis');
            $lines[] = 'SUMMARY:' . $this->esc($afspraak->klant->name . ' — ' . $afspraak->dienst->naam);
            $lines[] = 'DESCRIPTION:' . $this->esc($beschrijving);
            $lines[] = 'STATUS:' . ($afspraak->status === 'gepland' ? 'CONFIRMED' : 'COMPLETED');
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return response(implode("\r\n", $lines) . "\r\n", 200, [
            'Content-Type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'inline; filename="kaply-afspraken.ics"',
            'Cache-Control'       => 'no-cache, must-revalidate',
        ]);
    }

    private function esc(string $value): string
    {
        return str_replace(
            ['\\', ',', ';', "\r\n", "\n", "\r"],
            ['\\\\', '\\,', '\\;', '\\n', '\\n', '\\n'],
            $value
        );
    }
}
