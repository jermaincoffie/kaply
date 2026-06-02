# KapperPlatform — Project Context

## Regels & Stijlgids
Lees altijd `.claude/rules.md` vóór je iets bouwt of aanpast. Alle UI volgt de SereneShift stijl.

## Tech Stack
- Laravel 11
- Jetstream + Livewire 3
- TailwindCSS v4 (via @tailwindcss/vite)
- Alpine.js + Preline UI
- MySQL (via Laragon)
- Figtree font (fonts.bunny.net)

## Rollen
- `admin` — platform beheer
- `kapper` — kapperszaak eigenaar
- `klant` — klant die afspraken boekt

## Wat is al gebouwd
- ✅ Laravel + Jetstream setup
- ✅ Auth + rollen systeem (admin/kapper/klant)
- ✅ Database migrations (kappers, diensten, beschikbaarheden, sluitingsdagen, afspraken, klanten)
- ✅ Kapper registratie + profielbeheer
- ✅ Diensten beheer (CRUD, prijzen in centen)
- ✅ Beschikbaarheid + sluitingsdagen
- ✅ Publieke zoekpagina (homepage)
- ✅ Kapper profielpagina
- ✅ Boekingswizard (datum → tijdslot → betaalmethode)
- ✅ BeschikbaarheidsService (vrije tijdslots berekenen)
- ✅ Kapper agenda dashboard
- ✅ Klant afsprakenoverzicht
- ✅ Admin kappers overzicht
- ✅ SereneShift design systeem toegepast

## Nog te bouwen
- Plan 2: Stripe (kapper abonnement, online betaling, no-show pre-auth)
- Plan 3: Notificaties (email + SMS) + PWA + deploy naar Hostinger

## Lokale setup
- Server: `php artisan serve --port=8001` → http://localhost:8001
- CSS/JS: `npm run dev` (apart venster)
- Database: Laragon + MySQL (`kapper_systeem`)
- Admin: admin@kapperplatform.nl / password

## Route volgorde (belangrijk)
Specifieke `/kapper/*` routes MOETEN vóór de wildcard `/kapper/{slug}` staan.
