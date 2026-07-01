<x-mail::message>
# Welkom bij Kaply, {{ $naam }}!

Je abonnement is actief. Je salon **{{ $salonNaam }}** staat nu live op Kaply.

Klanten kunnen nu bij jou boeken via jouw persoonlijke boekpagina.

<x-mail::button :url="$dashboardUrl" color="success">
Naar mijn dashboard
</x-mail::button>

**Wat kun je nu doen?**

- Controleer je openingstijden en diensten
- Deel je boeklink met klanten
- Stel je agenda in

Vragen? Mail ons op support@kaply.nl

Met vriendelijke groet,
Het Kaply team
</x-mail::message>
