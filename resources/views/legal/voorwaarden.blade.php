<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Algemene Voorwaarden – Kaply</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 dark:bg-neutral-900 text-gray-800 dark:text-neutral-100 font-sans">

<div class="max-w-3xl mx-auto px-4 py-12">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-blue-600 mb-8 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Terug naar Kaply
    </a>

    <h1 class="text-3xl font-bold mb-2">Algemene Voorwaarden</h1>
    <p class="text-sm text-gray-400 mb-10">Laatst bijgewerkt: {{ now()->format('d F Y') }}</p>

    <div class="prose prose-gray dark:prose-invert max-w-none space-y-8 text-sm leading-relaxed">

        <section>
            <h2 class="text-lg font-semibold mb-3">1. Definities</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li><strong>Kaply:</strong> het online platform op kaply.nl</li>
                <li><strong>Kapper:</strong> een zakelijke gebruiker die diensten aanbiedt via Kaply</li>
                <li><strong>Klant:</strong> een persoon die via Kaply een afspraak maakt</li>
                <li><strong>Abonnement:</strong> de maandelijkse betaalde toegang voor kappers</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">2. Toepasselijkheid</h2>
            <p>Deze voorwaarden zijn van toepassing op alle gebruik van het Kaply platform, zowel door kappers als klanten. Door gebruik te maken van Kaply gaat u akkoord met deze voorwaarden.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">3. Het platform</h2>
            <p>Kaply biedt een online boekingsplatform waarop kappers hun diensten kunnen aanbieden en klanten afspraken kunnen maken. Kaply is niet verantwoordelijk voor de kwaliteit van de dienstverlening door kappers.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">4. Abonnement voor kappers</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Nieuwe kappers ontvangen een gratis proefperiode van 14 dagen</li>
                <li>Na de proefperiode bedraagt het abonnement €25 per maand (excl. BTW)</li>
                <li>Het abonnement wordt maandelijks automatisch verlengd</li>
                <li>Opzegging kan op elk moment, werkt per het einde van de lopende maand</li>
                <li>Bij niet-betaling wordt toegang tot het platform opgeschort</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">5. Verplichtingen kapper</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Correcte en actuele informatie opgeven op het profiel</li>
                <li>Beschikbaarheid tijdig bijhouden om no-shows te voorkomen</li>
                <li>Geboekte afspraken nakomen of tijdig annuleren</li>
                <li>Geen misleidende informatie of nepreserves aanmaken</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">6. Verplichtingen klant</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Correcte contactgegevens opgeven bij registratie</li>
                <li>Afspraken tijdig (minimaal 2 uur van tevoren) annuleren indien nodig</li>
                <li>Geen misbruik maken van het boekingssysteem</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">7. Betalingen</h2>
            <p>Betalingen verlopen via Stripe. Kaply heeft geen toegang tot volledige betaalgegevens. Prijzen zijn inclusief BTW tenzij anders vermeld.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">8. Aansprakelijkheid</h2>
            <p>Kaply is niet aansprakelijk voor schade als gevolg van:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Niet-nagekomen afspraken door kappers of klanten</li>
                <li>Tijdelijke onbeschikbaarheid van het platform</li>
                <li>Onjuiste informatie op kapperprofielen</li>
            </ul>
            <p class="mt-2">De totale aansprakelijkheid van Kaply is beperkt tot het bedrag dat in de betreffende maand is betaald.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">9. Beëindiging</h2>
            <p>Kaply behoudt het recht om accounts te verwijderen bij schending van deze voorwaarden, zonder restitutie van betaald abonnementsgeld.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">10. Toepasselijk recht</h2>
            <p>Op deze voorwaarden is Nederlands recht van toepassing. Geschillen worden voorgelegd aan de bevoegde rechter in Nederland.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">11. Contact</h2>
            <p>Voor vragen over deze voorwaarden: <a href="mailto:info@kaply.nl" class="text-blue-600 hover:underline">info@kaply.nl</a></p>
        </section>

    </div>
</div>

<footer class="border-t border-gray-200 dark:border-neutral-800 mt-16 py-6 text-center text-xs text-gray-400">
    <div class="flex items-center justify-center gap-4">
        <a href="{{ route('privacy') }}" class="hover:text-blue-600">Privacybeleid</a>
        <a href="{{ route('voorwaarden') }}" class="hover:text-blue-600">Algemene voorwaarden</a>
        <span>© {{ date('Y') }} Kaply</span>
    </div>
</footer>

</body>
</html>
