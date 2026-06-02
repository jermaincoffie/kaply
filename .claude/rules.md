# Stijlgids — KapperPlatform

## Design systeem: SereneShift stijl

Alles wat gebouwd wordt volgt de SereneShift design stijl (`c:\Users\jerma\medisch-systeem`).

### Kleurenpalet
- **Achtergrond pagina:** `bg-gray-100 dark:bg-neutral-900`
- **Cards / containers:** `bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl`
- **Tekst primair:** `text-gray-800 dark:text-neutral-100`
- **Tekst secundair:** `text-gray-500 dark:text-neutral-400`
- **Tekst muted:** `text-gray-400 dark:text-neutral-500`
- **Borders:** `border-gray-200 dark:border-neutral-700`
- **Hover rij:** `hover:bg-gray-50/50 dark:hover:bg-neutral-700/20`
- **Actief nav item:** `bg-blue-50 text-blue-900 dark:bg-neutral-700 dark:text-neutral-200`
- **Primaire knop:** `bg-blue-600 text-white hover:bg-blue-700`

### Typografie
- **Font:** Figtree (fonts.bunny.net)
- **Paginatitel:** `text-base font-semibold text-gray-800 dark:text-neutral-100`
- **Subtitel:** `text-xs text-gray-400 dark:text-neutral-500`
- **Label:** `text-sm font-medium text-gray-700 dark:text-neutral-300`

### Tabellen
- Container: `bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden`
- `thead tr`: `border-b border-gray-100 dark:border-neutral-700`
- `th`: `px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide`
- `tbody`: `divide-y divide-gray-50 dark:divide-neutral-700`
- `tr`: `hover:bg-gray-50/50 dark:hover:bg-neutral-700/20`
- `td` primair: `px-6 py-3.5 font-medium text-gray-800 dark:text-neutral-100`
- `td` secundair: `px-6 py-3.5 text-gray-500 dark:text-neutral-400`

### Badges / status
- **Groen (actief):** `bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400`
- **Blauw (info):** `bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400`
- **Grijs (inactief):** `bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400`
- **Rood (fout/waarschuwing):** `bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400`
- Vorm: `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium`

### Formulieren
- Input: `rounded-lg border border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900 text-sm focus:ring-2 focus:ring-blue-500`
- Label: `block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-1`

### Dark mode
- Altijd dark mode klassen toevoegen naast light mode
- Palette: `neutral-900` (pagina) → `neutral-800` (cards) → `neutral-700` (hover/active)
- Tekst: `neutral-100` (primair) → `neutral-400` (secundair) → `neutral-500` (muted)

### Referentie
Kijk bij twijfel naar `c:\Users\jerma\medisch-systeem\resources\views\admin\tenants\index.blade.php` als tabelvoorbeeld of `resources\views\layouts\admin.blade.php` voor layout.
