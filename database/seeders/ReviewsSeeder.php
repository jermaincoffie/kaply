<?php

namespace Database\Seeders;

use App\Models\Afspraak;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            ['rating' => 5, 'tekst' => 'Geweldige kapper! Echt vakwerk, precies wat ik wilde. Ga zeker terug.'],
            ['rating' => 4, 'tekst' => 'Goede ervaring, vriendelijk personeel. Alleen even wachten maar het was het waard.'],
            ['rating' => 5, 'tekst' => 'Top fade, zit perfect. Al jaren mijn vaste kapper.'],
            ['rating' => 3, 'tekst' => 'Redelijk, maar de afwerking had iets beter gekund. Wel vriendelijk.'],
            ['rating' => 5, 'tekst' => 'Fantastisch! Heeft precies gedaan wat ik vroeg. Zeer tevreden.'],
            ['rating' => 4, 'tekst' => null],
            ['rating' => 5, 'tekst' => 'Beste kapper van de buurt, altijd scherp resultaat.'],
            ['rating' => 2, 'tekst' => 'Viel wat tegen, verwachtte meer voor de prijs.'],
        ];

        $afspraken = Afspraak::where('status', 'voltooid')
            ->whereNull('walk_in_naam')
            ->whereNotNull('klant_id')
            ->doesntHave('review')
            ->with(['kapper', 'klant'])
            ->limit(count($reviews))
            ->get();

        foreach ($afspraken as $index => $afspraak) {
            if (!isset($reviews[$index])) break;

            Review::create([
                'kapper_id'   => $afspraak->kapper_id,
                'klant_id'    => $afspraak->klant_id,
                'afspraak_id' => $afspraak->id,
                'rating'      => $reviews[$index]['rating'],
                'tekst'       => $reviews[$index]['tekst'],
                'zichtbaar'   => true,
            ]);
        }

        echo 'Seeded ' . min($afspraken->count(), count($reviews)) . ' reviews.' . PHP_EOL;
    }
}
