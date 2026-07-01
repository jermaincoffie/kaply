<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacybeleid – Kaply</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 dark:bg-neutral-900 text-gray-800 dark:text-neutral-100 font-sans">

<div class="max-w-3xl mx-auto px-4 py-12">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-blue-600 mb-8 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Terug naar Kaply
    </a>

    <h1 class="text-3xl font-bold mb-2">Privacybeleid</h1>
    <p class="text-sm text-gray-400 mb-10">Laatst bijgewerkt: 30 juni 2026</p>

    <div class="prose prose-gray dark:prose-invert max-w-none space-y-8 text-sm leading-relaxed">

        <section>
            <h2 class="text-lg font-semibold mb-3">1. Wie zijn wij?</h2>
            <p>Kaply is een online platform waarmee kappers hun beschikbaarheid en diensten kunnen publiceren en klanten afspraken kunnen boeken.</p>
            <p class="mt-3"><strong>Verwerkingsverantwoordelijke:</strong></p>
            <ul class="list-none pl-0 mt-1 space-y-0.5 text-gray-600 dark:text-neutral-400">
                <li>Coffie Digital (handelsnaam: Kaply)</li>
                <li>Iepenrode 19, 2317BJ Leiden</li>
                <li>KVK-nummer: 42089812</li>
                <li>BTW-nummer: NL220924260B02</li>
                <li>E-mail: <a href="mailto:info@kaply.nl" class="text-blue-600 hover:underline">info@kaply.nl</a></li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">2. Welke gegevens verwerken wij?</h2>
            <p><strong>Kappers (zakelijke gebruikers):</strong></p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Naam, e-mailadres en wachtwoord (gehashed)</li>
                <li>Salonnaam, adres, stad en telefoonnummer</li>
                <li>Profielfoto en galerij-afbeeldingen</li>
                <li>Beschikbaarheid en diensteninformatie</li>
                <li>Stripe klant-ID en abonnementsstatus (geen volledige betaalgegevens)</li>
            </ul>
            <p class="mt-3"><strong>Klanten:</strong></p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Naam en e-mailadres</li>
                <li>Afspraakhistorie</li>
                <li>Beoordelingen en notities</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">3. Waarvoor gebruiken wij uw gegevens?</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li>Het aanmaken en beheren van uw account</li>
                <li>Het verwerken en bevestigen van afspraken</li>
                <li>Het versturen van herinneringen en bevestigingsemails</li>
                <li>Het verwerken van betalingen via Stripe</li>
                <li>Het verbeteren van ons platform</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">4. Grondslag voor verwerking</h2>
            <p>Wij verwerken persoonsgegevens op basis van:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li><strong>Uitvoering van een overeenkomst</strong> — voor het leveren van onze diensten</li>
                <li><strong>Gerechtvaardigd belang</strong> — voor platformverbetering en veiligheid</li>
                <li><strong>Toestemming</strong> — voor marketingcommunicatie (optioneel)</li>
            </ul>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">5. Cookies</h2>
            <p>Kaply maakt gebruik van functionele cookies die noodzakelijk zijn voor het functioneren van het platform (sessie, inlogstatus). Wij plaatsen geen tracking- of advertentiecookies zonder uw toestemming.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">6. Derde partijen</h2>
            <ul class="list-disc pl-5 space-y-1">
                <li><strong>Stripe</strong> — betalingsverwerking (eigen privacybeleid van toepassing)</li>
                <li><strong>Hostinger</strong> — serverhosting in de EU</li>
                <li><strong>Mailserver (Hostinger SMTP)</strong> — e-mailverzending</li>
            </ul>
            <p class="mt-2">Wij verkopen uw gegevens nooit aan derden.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">7. Bewaartermijnen</h2>
            <p>Wij bewaren uw gegevens zolang uw account actief is. Na verwijdering van uw account worden gegevens binnen 30 dagen verwijderd, tenzij wettelijke bewaarplicht van toepassing is (bijv. financiële gegevens: 7 jaar).</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">8. Uw rechten</h2>
            <p>U heeft het recht op:</p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li>Inzage in uw persoonsgegevens</li>
                <li>Correctie van onjuiste gegevens</li>
                <li>Verwijdering van uw account en gegevens</li>
                <li>Beperking van verwerking</li>
                <li>Gegevensoverdraagbaarheid</li>
            </ul>
            <p class="mt-2">Verzoeken kunt u sturen naar <a href="mailto:info@kaply.nl" class="text-blue-600 hover:underline">info@kaply.nl</a>. Wij reageren binnen 30 dagen.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">9. Klachten</h2>
            <p>Heeft u een klacht over de verwerking van uw persoonsgegevens? U kunt een klacht indienen bij de <a href="https://www.autoriteitpersoonsgegevens.nl" target="_blank" class="text-blue-600 hover:underline">Autoriteit Persoonsgegevens</a>.</p>
        </section>

        <section>
            <h2 class="text-lg font-semibold mb-3">10. Wijzigingen</h2>
            <p>Wij kunnen dit privacybeleid aanpassen. Bij belangrijke wijzigingen informeren wij u via e-mail of een melding op het platform.</p>
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
