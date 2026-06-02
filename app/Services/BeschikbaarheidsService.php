<?php

namespace App\Services;

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
use Carbon\Carbon;

class BeschikbaarheidsService
{
    public function getVrijeTijdslots(Kapper $kapper, Dienst $dienst, string $datum): array
    {
        $date = Carbon::parse($datum);
        $dagVanWeek = $date->dayOfWeekIso - 1; // Carbon: 1=Monday → 0=maandag

        $beschikbaarheid = Beschikbaarheid::where('kapper_id', $kapper->id)
            ->where('dag_van_week', $dagVanWeek)
            ->first();

        if (!$beschikbaarheid) return [];

        if ($kapper->sluitingsdagen()->whereDate('datum', $datum)->exists()) return [];

        $geboekteAfspraken = Afspraak::where('kapper_id', $kapper->id)
            ->whereDate('datum', $datum)
            ->whereIn('status', ['gepland', 'voltooid'])
            ->get(['start_tijd', 'eind_tijd']);

        $slots = [];
        $current = Carbon::parse("{$datum} {$beschikbaarheid->start_tijd}");
        $eind = Carbon::parse("{$datum} {$beschikbaarheid->eind_tijd}");

        while ($current->copy()->addMinutes($dienst->duur_minuten)->lte($eind)) {
            $slotStart = $current->format('H:i');
            $slotEind = $current->copy()->addMinutes($dienst->duur_minuten)->format('H:i');

            $bezet = $geboekteAfspraken->first(function ($afspraak) use ($slotStart, $slotEind) {
                return $afspraak->start_tijd < $slotEind && $afspraak->eind_tijd > $slotStart;
            });

            if (!$bezet) $slots[] = $slotStart;

            $current->addMinutes(30);
        }

        return $slots;
    }
}
