# Mobile & UX Refresh — Design Spec
**Datum:** 2026-06-10  
**Project:** kaply (kapper-systeem)  
**Status:** Goedgekeurd, klaar voor implementatie

---

## Scope

Volledige mobile + UX refresh voor zowel kapper-dashboard als klant-zijde. Bestaande SereneShift design stijl (dark neutral palette) blijft intact — geen visuele redesign. Aanpak: layouts eerst, daarna views.

---

## Aanpak: Layouts eerst

1. Overhaul `layouts/kapper.blade.php` → bottom nav op mobiel
2. Overhaul `layouts/klant.blade.php` → bottom nav op mobiel + kleinere header
3. Kritieke bug fixes in views
4. UX verbeteringen per pagina

---

## Sectie 1: Kapper layout — bottom nav

### Desktop (lg:)
Ongewijzigd: vaste sidebar links, main content met `lg:ml-64`.

### Mobiel (onder lg:)
- Hamburger knop + sidebar overlay: **verwijderd**
- Bottom navigation bar toegevoegd: `fixed bottom-0 inset-x-0 h-16 bg-neutral-900 border-t border-neutral-700 z-40`
- Safe-area padding voor iPhone notch: `pb-safe` (of `pb-[env(safe-area-inset-bottom)]`)
- Main content: geen `lg:ml-64` op mobiel, wel `pb-16` zodat content niet achter bottom nav valt

### Bottom nav tabs

| Tab | Icon | Gedrag |
|-----|------|--------|
| Agenda | Calendar | Navigeer naar `kapper.agenda` |
| Afspraken | Clipboard | Navigeer naar `kapper.afspraken` |
| Beheer | Pencil | Opent bottom sheet met 3 opties: Diensten · Medewerkers · Beschikbaarheid |
| Profiel | User circle | Navigeer naar `kapper.profiel` |
| Meer | Dots horizontal | Opent bottom sheet: Klanten · Reviews |

### Styling bottom nav tabs
- Actieve tab: `text-blue-400` (detecteer via huidige route)
- Inactieve tabs: `text-neutral-500`
- Tap-target: minimaal 44×44px per tab (`flex-1 flex flex-col items-center justify-center`)
- Label: `text-[10px] font-medium mt-0.5`

### Bottom sheets (Beheer + Meer)
- Alpine.js `x-show` / `x-data` modal vanuit onderaan
- Backdrop: `fixed inset-0 bg-black/50 z-30`
- Sheet: `fixed bottom-0 inset-x-0 bg-neutral-800 rounded-t-2xl p-4 z-40`
- Items: grote touch-targets `py-3 px-4 text-sm font-medium text-neutral-100`

---

## Sectie 2: Klant layout — bottom nav

### Desktop
Ongewijzigd: sticky header met nav-links.

### Mobiel
- Sticky header inkrimpen: alleen logo + account-icon (nav-links hidden op mobiel)
- Bottom navigation bar (zelfde stijl als kapper):

| Tab | Icon | Route |
|-----|------|-------|
| Zoeken | Search/home | `home` |
| Afspraken | Calendar | `klant.afspraken` |
| Account | User | `klant.account` |

- Main content: `pb-16` op mobiel voor clearance
- Actieve tab detectie via huidige route

---

## Sectie 3: Kritieke bug fixes

### 3a — Hero titel te groot op mobiel
**Bestand:** `resources/views/livewire/klant/kapper-zoeken.blade.php`  
**Huidig:** `text-6xl`  
**Fix:** `text-3xl sm:text-5xl lg:text-6xl`

### 3b — Tijdslot grid krap op mobiel
**Bestanden:** `kapper-profiel.blade.php`, `boeking-wizard.blade.php`  
**Huidig:** `grid-cols-4`  
**Fix:** `grid-cols-3 sm:grid-cols-4 md:grid-cols-6`

### 3c — Naam-veld grid breekt niet op mobiel
**Bestand:** `resources/views/livewire/klant/account-beheer.blade.php`  
**Huidig:** `grid grid-cols-2`  
**Fix:** `grid grid-cols-1 sm:grid-cols-2`

---

## Sectie 4: UX verbeteringen per pagina

### Kapper agenda (`agenda-overzicht.blade.php`)
- FullCalendar default view: `timeGridDay` op mobiel, `timeGridWeek` op desktop
- Detectie: `initialView: window.innerWidth < 640 ? 'timeGridDay' : 'timeGridWeek'` in de FullCalendar JS config op de blade pagina
- Week-view is te krap op 390px scherm — dag-view toont één dag volledig
- Navigatie knoppen (prev/next/today) blijven zichtbaar, alleen de view verandert

### Kapper afspraken (`afspraken-overzicht.blade.php`)
- Mobiel: kaart-layout i.p.v. tabel
- Elke afspraak = card `bg-neutral-800 rounded-xl p-4 border border-neutral-700`
- Tabel alleen zichtbaar op `sm:` en groter (`hidden sm:table` / `sm:hidden` voor cards)
- Card toont: naam klant, dienst, datum+tijd, status badge, acties

### Klant kapper-profiel (`kapper-profiel.blade.php`)
- Tijdslot grid: fix naar `grid-cols-3 sm:grid-cols-4 md:grid-cols-6` (zie 3b)
- Grid behouden (geen horizontale scroll)

### Klant mijn-afspraken
- Al goed geïmplementeerd, geen wijzigingen nodig

---

## Technische constraints

- Geen nieuwe npm packages — pure Tailwind + Alpine.js
- `.gitignore` bevat `public/build` maar productie vereist gecommitte build → na elke CSS wijziging: `npm run build && git add public/build/ -f`
- Livewire 3 + Alpine.js: sidebar/sheet state via `x-data` in layout file
- `env(safe-area-inset-bottom)` voor iPhone notch support via Tailwind plugin of inline style

---

## Niet in scope

- Stripe integratie
- Push notificaties
- Klant boeking-wizard flow herstructurering
- Admin dashboard mobiel (laag gebruik op mobiel)
- Design systeem wijzigingen (SereneShift stijl blijft)
