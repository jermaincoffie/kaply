<?php

namespace App\Services;

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\MedewerkerBeschikbaarheid;
use Carbon\Carbon;

class BeschikbaarheidsService
{
    public function getSluitingsdag(Kapper $kapper, string $datum): ?object
    {
        return $kapper->sluitingsdagen()
            ->where(fn($q) => $q
                ->where(fn($q2) => $q2->whereNull('datum_tot')->whereDate('datum', $datum))
                ->orWhere(fn($q2) => $q2->whereNotNull('datum_tot')
                    ->where('datum', '<=', $datum)
                    ->where('datum_tot', '>=', $datum))
            )->first();
    }

    public function heeftBeschikbaarheid(Kapper $kapper, string $datum): bool
    {
        $date = Carbon::parse($datum);
        $dagVanWeek = $date->dayOfWeekIso - 1;

        if (!Beschikbaarheid::where('kapper_id', $kapper->id)->where('dag_van_week', $dagVanWeek)->exists()) {
            return false;
        }

        return !$kapper->sluitingsdagen()
            ->where(fn($q) => $q
                ->where(fn($q2) => $q2->whereNull('datum_tot')->whereDate('datum', $datum))
                ->orWhere(fn($q2) => $q2->whereNotNull('datum_tot')
                    ->where('datum', '<=', $datum)
                    ->where('datum_tot', '>=', $datum))
            )->exists();
    }

    public function getVrijeTijdslots(
        Kapper $kapper,
        Dienst $dienst,
        string $datum,
        ?int $medewerkerId = null,
        ?int $excludeAfspraakId = null
    ): array {
        $date = Carbon::parse($datum);
        $dagVanWeek = $date->dayOfWeekIso - 1;

        $beschikbaarheid = Beschikbaarheid::where('kapper_id', $kapper->id)
            ->where('dag_van_week', $dagVanWeek)
            ->first();

        if (!$beschikbaarheid) return [];

        // Medewerker met eigen rooster → gebruik dat; ongevinkte dagen = geen slots
        if ($medewerkerId) {
            $heeftEigenRooster = MedewerkerBeschikbaarheid::where('medewerker_id', $medewerkerId)->exists();

            if ($heeftEigenRooster) {
                $medewerkerSchema = MedewerkerBeschikbaarheid::where('medewerker_id', $medewerkerId)
                    ->where('dag_van_week', $dagVanWeek)
                    ->first();

                if (!$medewerkerSchema) return []; // dag niet aangevinkt = vrij

                $beschikbaarheid->start_tijd = $medewerkerSchema->start_tijd;
                $beschikbaarheid->eind_tijd  = $medewerkerSchema->eind_tijd;
            }
            // Geen eigen rooster → salonrooster als fallback (geen wijziging)
        }
        $isGesloten = $kapper->sluitingsdagen()
            ->where(fn($q) => $q
                // Losse dag: exacte datum match
                ->where(fn($q2) => $q2->whereNull('datum_tot')->whereDate('datum', $datum))
                // Range: datum valt binnen van–tot
                ->orWhere(fn($q2) => $q2->whereNotNull('datum_tot')
                    ->where('datum', '<=', $datum)
                    ->where('datum_tot', '>=', $datum))
            )->exists();
        if ($isGesloten) return [];

        // Capaciteit = aantal actieve medewerkers, minimaal 1
        $aantalMedewerkers = $kapper->medewerkers()->where('actief', true)->count();
        $capaciteit = max(1, $aantalMedewerkers);

        $alleGeboekteAfspraken = Afspraak::where('kapper_id', $kapper->id)
            ->whereDate('datum', $datum)
            ->whereIn('status', ['gepland', 'voltooid', 'wacht_op_betaling'])
            ->when($excludeAfspraakId, fn($q) => $q->where('id', '!=', $excludeAfspraakId))
            ->get(['start_tijd', 'eind_tijd', 'medewerker_id']);

        $bufferMinuten = (int) ($kapper->buffer_minuten ?? 0);

        // Geen slots voor datums die al voorbij zijn
        if ($date->toDateString() < now()->toDateString()) return [];

        $slots = [];
        $current = Carbon::parse("{$datum} {$beschikbaarheid->start_tijd}");
        $eind    = Carbon::parse("{$datum} {$beschikbaarheid->eind_tijd}");

        while ($current->copy()->addMinutes($dienst->duur_minuten)->lte($eind)) {
            $slotStart = $current->format('H:i');
            $slotEind  = $current->copy()->addMinutes($dienst->duur_minuten)->format('H:i');

            $overlappend = $alleGeboekteAfspraken->filter(function ($afspraak) use ($slotStart, $slotEind, $bufferMinuten, $datum) {
                $effectiefEind = $bufferMinuten > 0
                    ? Carbon::parse("{$datum} {$afspraak->eind_tijd}")->addMinutes($bufferMinuten)->format('H:i')
                    : (string) $afspraak->eind_tijd;
                return $afspraak->start_tijd < $slotEind && $effectiefEind > $slotStart;
            });

            if ($medewerkerId) {
                // Specifieke medewerker: geblokkeerd als deze medewerker bezet is OF totale capaciteit vol is
                $medewerkerBezet = $overlappend->where('medewerker_id', $medewerkerId)->isNotEmpty();
                $capaciteitVol   = $overlappend->count() >= $capaciteit;
                if (!$medewerkerBezet && !$capaciteitVol) $slots[] = $slotStart;
            } else {
                if ($overlappend->count() < $capaciteit) $slots[] = $slotStart;
            }

            $current->addMinutes(30);
        }

        // Filter slots die vandaag al voorbij zijn
        if ($date->isToday()) {
            $slots = array_values(array_filter(
                $slots,
                fn($slot) => Carbon::parse("{$datum} {$slot}")->gt(now())
            ));
        }

        return $slots;
    }
}
