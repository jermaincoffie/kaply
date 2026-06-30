<?php

namespace App\Http\Controllers;

use App\Models\Afspraak;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KlantDataController extends Controller
{
    public function download(Request $request): Response
    {
        $user = $request->user();

        $afspraken = Afspraak::where('klant_id', $user->id)
            ->with(['kapper:id,salon_naam,stad', 'dienst:id,naam,prijs'])
            ->orderByDesc('datum')
            ->get()
            ->map(fn($a) => [
                'datum'       => $a->datum->format('Y-m-d'),
                'tijd'        => $a->start_tijd . ' – ' . $a->eind_tijd,
                'salon'       => $a->kapper->salon_naam ?? '—',
                'stad'        => $a->kapper->stad ?? '—',
                'dienst'      => $a->dienst->naam ?? '—',
                'status'      => $a->status,
            ]);

        $reviews = Review::where('klant_id', $user->id)
            ->with('kapper:id,salon_naam')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($r) => [
                'salon'  => $r->kapper->salon_naam ?? '—',
                'rating' => $r->rating,
                'tekst'  => $r->tekst,
                'datum'  => $r->created_at->format('Y-m-d'),
            ]);

        $export = [
            'exportdatum' => now()->format('Y-m-d H:i'),
            'account' => [
                'naam'      => $user->name,
                'email'     => $user->email,
                'telefoon'  => $user->telefoon,
                'aangemeld' => $user->created_at->format('Y-m-d'),
            ],
            'afspraken' => $afspraken,
            'beoordelingen' => $reviews,
        ];

        $json = json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $bestandsnaam = 'kaply-mijn-gegevens-' . now()->format('Y-m-d') . '.json';

        return response($json, 200, [
            'Content-Type'        => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $bestandsnaam . '"',
        ]);
    }
}
