<?php

namespace App\Console\Commands;

use App\Models\Wachtlijst;
use Illuminate\Console\Command;

class WachtlijstOpruimen extends Command
{
    protected $signature   = 'kaply:wachtlijst-opruimen';
    protected $description = 'Verwijder wachtlijst entries ouder dan 14 dagen of met verlopen gewenste_datum';

    public function handle(): void
    {
        $verwijderd = Wachtlijst::where('status', 'wachtend')
            ->where(function ($q) {
                // Aangemeld meer dan 14 dagen geleden
                $q->where('created_at', '<', now()->subDays(14))
                  // Of gewenste datum is al gepasseerd
                  ->orWhere(function ($q2) {
                      $q2->whereNotNull('gewenste_datum')
                         ->where('gewenste_datum', '<', today());
                  });
            })
            ->delete();

        $this->info("Wachtlijst opgeruimd: {$verwijderd} verwijderd.");
    }
}
