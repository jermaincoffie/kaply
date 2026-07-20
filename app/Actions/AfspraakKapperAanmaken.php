<?php

namespace App\Actions;

use App\Models\Afspraak;
use App\Models\Activiteit;
use App\Models\Dienst;
use App\Models\User;
use Carbon\Carbon;

class AfspraakKapperAanmaken
{
    public function uitvoeren(
        int $kapperId,
        string $datum,
        string $tijd,
        int $dienstId,
        string $betaalmethode,
        ?int $medewerkerId,
        bool $isWalkIn,
        string $walkInNaam,
        ?int $klantId,
        string $klantZoekterm,
        string $kapperNaam,
    ): Afspraak {
        $definitieveWalkInNaam = null;
        $definitieveKlantId    = null;

        if ($isWalkIn) {
            $definitieveWalkInNaam = trim($walkInNaam);
        } elseif ($klantId) {
            $definitieveKlantId = $klantId;
        } else {
            $definitieveWalkInNaam = trim($klantZoekterm) ?: 'Walk-in';
        }

        $dienst = Dienst::findOrFail($dienstId);
        $eind   = Carbon::parse($datum . ' ' . $tijd)->addMinutes($dienst->duur_minuten)->format('H:i');

        $afspraak = Afspraak::create([
            'klant_id'      => $definitieveKlantId,
            'walk_in_naam'  => $definitieveWalkInNaam,
            'kapper_id'     => $kapperId,
            'dienst_id'     => $dienst->id,
            'medewerker_id' => $medewerkerId ?: null,
            'datum'         => $datum,
            'start_tijd'    => $tijd,
            'eind_tijd'     => $eind,
            'status'        => 'gepland',
            'betaalmethode' => $betaalmethode,
        ]);

        $klantNaam = $definitieveWalkInNaam ?? (User::find($definitieveKlantId)?->name ?? 'Onbekend');
        Activiteit::create([
            'kapper_id'   => $kapperId,
            'afspraak_id' => $afspraak->id,
            'datum'       => $datum,
            'type'        => $definitieveWalkInNaam ? 'walk_in' : 'geboekt',
            'tekst'       => $definitieveWalkInNaam
                ? "{$kapperNaam} heeft {$klantNaam} ingeboekt als walk-in voor {$dienst->naam} om {$tijd}"
                : "{$kapperNaam} heeft een afspraak voor {$klantNaam} gemaakt voor {$dienst->naam} om {$tijd}",
        ]);

        return $afspraak;
    }
}
