<?php

namespace App\Console\Commands;

use App\Mail\TrialDag3Mail;
use App\Mail\TrialDag10Mail;
use App\Models\Kapper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TrialOpvolgingVersturen extends Command
{
    protected $signature = 'kaply:trial-opvolging';
    protected $description = 'Stuurt dag-3 en dag-10 opvolgmails naar kappers in proefperiode';

    public function handle(): void
    {
        Kapper::with('user')
            ->where('trial_dag3_verstuurd', false)
            ->whereHas('user', fn($q) => $q->whereBetween('created_at', [
                now()->subDays(3)->startOfDay(),
                now()->subDays(3)->endOfDay(),
            ]))
            ->each(function (Kapper $kapper) {
                Mail::to($kapper->user->email)->send(new TrialDag3Mail($kapper));
                $kapper->update(['trial_dag3_verstuurd' => true]);
            });

        Kapper::with('user')
            ->where('trial_dag10_verstuurd', false)
            ->whereHas('user', fn($q) => $q->whereBetween('created_at', [
                now()->subDays(10)->startOfDay(),
                now()->subDays(10)->endOfDay(),
            ]))
            ->each(function (Kapper $kapper) {
                Mail::to($kapper->user->email)->send(new TrialDag10Mail($kapper));
                $kapper->update(['trial_dag10_verstuurd' => true]);
            });

        $this->info('Trial opvolgmails verstuurd.');
    }
}
