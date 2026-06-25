<?php

namespace App\Console\Commands;

use App\Mail\ReviewUitnodigingMail;
use App\Models\Afspraak;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class StuurReviewUitnodigingen extends Command
{
    protected $signature = 'reviews:uitnodigingen-stuur';
    protected $description = 'Stuur review-uitnodigingen 2 uur na voltooide afspraken';

    public function handle(): void
    {
        $nu = now();

        // Afspraken die ~2 uur geleden klaar waren (venster: 1u50m tot 2u10m geleden)
        Afspraak::whereIn('status', ['gepland', 'voltooid'])
            ->where('review_uitnodiging_verstuurd', false)
            ->whereNotNull('klant_id')
            ->whereBetween(
                DB::raw("TIMESTAMP(datum, eind_tijd)"),
                [$nu->copy()->subHours(2)->subMinutes(10), $nu->copy()->subHours(1)->subMinutes(50)]
            )
            ->whereDoesntHave('review')
            ->with(['kapper', 'dienst', 'klant'])
            ->each(function (Afspraak $afspraak) {
                if (!$afspraak->klant) return;
                Mail::to($afspraak->klant->email)->send(new ReviewUitnodigingMail($afspraak));
                $afspraak->update(['review_uitnodiging_verstuurd' => true]);
                $this->info("Review-uitnodiging → {$afspraak->klant->email} (afspraak #{$afspraak->id})");
            });
    }
}
