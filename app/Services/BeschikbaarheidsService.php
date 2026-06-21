<?php

namespace App\Services;

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
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

        $geboekteAfspraken = Afspraak::where('kapper_id', $kapper->id)
            ->whereDate('datum', $datum)
            ->whereIn('status', ['gepland', 'voltooid', 'wacht_op_betaling'])
            ->when($medewerkerId, fn($q) => $q->where('medewerker_id', $medewerkerId))
            ->when($excludeAfspraakId, fn($q) => $q->where('id', '!=', $excludeAfspraakId))
            ->get(['start_tijd', 'eind_tijd']);

        $bufferMinuten = (int) ($kapper->buffer_minuten ?? 0);

        // Geen slots voor datums die al voorbij zijn
        if ($date->toDateString() < now()->toDateString()) return [];

        $slots = [];
        $current = Carbon::parse("{$datum} {$beschikbaarheid->start_tijd}");
        $eind    = Carbon::parse("{$datum} {$beschikbaarheid->eind_tijd}");

        while ($current->copy()->addMinutes($dienst->duur_minuten)->lte($eind)) {
            $slotStart = $current->format('H:i');
            $slotEind  = $current->copy()->addMinutes($dienst->duur_minuten)->format('H:i');

            $bezettingen = $geboekteAfspraken->filter(function ($afspraak) use ($slotStart, $slotEind, $bufferMinuten, $datum) {
                $effectiefEind = $bufferMinuten > 0
                    ? Carbon::parse("{$datum} {$afspraak->eind_tijd}")->addMinutes($bufferMinuten)->format('H:i')
                    : (string) $afspraak->eind_tijd;
                return $afspraak->start_tijd < $slotEind && $effectiefEind > $slotStart;
            })->count();

            $maxCapaciteit = $medewerkerId ? 1 : $capaciteit;
            if ($bezettingen < $maxCapaciteit) $slots[] = $slotStart;

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
