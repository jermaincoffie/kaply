<?php

namespace App\Console\Commands;

use App\Mail\AfspraakHerinneringMail;
use App\Models\Afspraak;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class StuurHerinneringen extends Command
{
    protected $signature = 'herinneringen:stuur';
    protected $description = 'Stuur email herinneringen 24u en 1u voor afspraken';

    public function handle(): void
    {
        $nu = now();

        // 24-uurs herinneringen: afspraken tussen 23u50 en 24u10 vanaf nu
        Afspraak::where('status', 'gepland')
            ->where('herinnering_24u_verstuurd', false)
            ->whereNotNull('klant_id')
            ->whereBetween(
                \DB::raw("TIMESTAMP(datum, start_tijd)"),
                [$nu->copy()->addHours(23)->addMinutes(50), $nu->copy()->addHours(24)->addMinutes(10)]
            )
            ->with(['kapper', 'dienst', 'klant'])
            ->each(function (Afspraak $afspraak) {
                Mail::to($afspraak->klant->email)->send(new AfspraakHerinneringMail($afspraak, '24 uur'));
                $afspraak->update(['herinnering_24u_verstuurd' => true]);
                $this->info("24u herinnering → {$afspraak->klant->email}");
            });

        // 1-uurs herinneringen: afspraken tussen 50 min en 70 min vanaf nu
        Afspraak::where('status', 'gepland')
            ->where('herinnering_1u_verstuurd', false)
            ->whereNotNull('klant_id')
            ->whereBetween(
                \DB::raw("TIMESTAMP(datum, start_tijd)"),
                [$nu->copy()->addMinutes(50), $nu->copy()->addMinutes(70)]
            )
            ->with(['kapper', 'dienst', 'klant'])
            ->each(function (Afspraak $afspraak) {
                Mail::to($afspraak->klant->email)->send(new AfspraakHerinneringMail($afspraak, '1 uur'));
                $afspraak->update(['herinnering_1u_verstuurd' => true]);
                $this->info("1u herinnering → {$afspraak->klant->email}");
            });
    }
}
