# [APPNAAM] Kapperplatform — Plan 1: Foundation

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Nieuw standalone Laravel-project met auth, kapper-profielen, diensten, beschikbaarheid en een werkende boekingswizard (zonder betaling).

**Architecture:** Standalone Laravel 11 + Jetstream + Livewire app op `c:\Users\jerma\kapper-systeem`. Marketplace-model: kappers staan op `/kapper/[slug]`, klanten zoeken en boeken via het platform. Drie rollen: admin, kapper, klant.

**Tech Stack:** Laravel 11, Jetstream (Livewire stack), Livewire 3, TailwindCSS, MySQL, Pest (tests)

---

## Bestandsstructuur

```
app/
  Models/
    User.php              — auth + rol (admin/kapper/klant)
    Kapper.php            — kapper profiel, slug, actief
    Dienst.php            — dienst van een kapper (naam, duur, prijs)
    Beschikbaarheid.php   — weekrooster per kapper
    Sluitingsdag.php      — eenmalige uitzondering (vakantie)
    Afspraak.php          — geboekte afspraak
    Klant.php             — extra klantgegevens (telefoon)
  Http/Livewire/
    Kapper/
      ProfielBeheer.php   — kapper bewerkt eigen profiel
      DienstenBeheer.php  — CRUD diensten
      BeschikbaarheidBeheer.php — weekrooster + sluitingsdagen
      AgendaOverzicht.php — dag/week view afspraken
    Klant/
      KapperZoeken.php    — zoek kappers op stad/naam
      BoekingWizard.php   — stap 1-4 wizard
      MijnAfspraken.php   — aankomend + geschiedenis
  Policies/
    AfspraakPolicy.php    — klant mag eigen afspraken annuleren
    KapperPolicy.php      — kapper mag eigen profiel/diensten beheren
  Services/
    BeschikbaarheidsService.php — vrije tijdslots berekenen
database/migrations/
  *_create_kappers_table.php
  *_create_diensten_table.php
  *_create_beschikbaarheden_table.php
  *_create_sluitingsdagen_table.php
  *_create_afspraken_table.php
  *_create_klanten_table.php
resources/views/
  layouts/
    kapper.blade.php      — kapper dashboard layout (sidebar)
    klant.blade.php       — klant dashboard layout
    publiek.blade.php     — publieke pagina's layout
  livewire/kapper/        — blade views voor kapper Livewire components
  livewire/klant/         — blade views voor klant Livewire components
  pages/
    kapper-profiel.blade.php
routes/web.php
tests/Feature/
  KapperRegistratieTest.php
  KapperProfielTest.php
  DienstenTest.php
  BeschikbaarheidTest.php
  BoekingTest.php
  KapperZoekenTest.php
```

---

## Task 1: Nieuw Laravel project aanmaken

**Files:**
- Create: `c:\Users\jerma\kapper-systeem\` (nieuw project)
- Modify: `.env`

- [ ] **Stap 1: Maak nieuw Laravel project aan**

```bash
cd c:\Users\jerma
composer create-project laravel/laravel kapper-systeem
cd kapper-systeem
```

- [ ] **Stap 2: Installeer Jetstream met Livewire stack**

```bash
composer require laravel/jetstream
php artisan jetstream:install livewire --pest
npm install
npm run build
```

- [ ] **Stap 3: Maak database aan in Laragon**

Open HeidiSQL → nieuwe database `kapper_systeem` → charset `utf8mb4_unicode_ci`.

- [ ] **Stap 4: Configureer `.env`**

```env
APP_NAME="[APPNAAM]"
APP_URL=http://localhost:8001
DB_DATABASE=kapper_systeem
DB_USERNAME=root
DB_PASSWORD=
```

- [ ] **Stap 5: Draai migrations + verifieer**

```bash
php artisan migrate
```

Verwacht: alle standaard Jetstream-tabellen aangemaakt zonder errors.

- [ ] **Stap 6: Start dev server op andere poort (SereneShift draait op 8000)**

```bash
php artisan serve --port=8001
```

Open http://localhost:8001 — Laravel welkomstpagina zichtbaar.

- [ ] **Stap 7: Commit**

```bash
git add -A
git commit -m "chore: initial Laravel Jetstream setup"
```

---

## Task 2: Rollen systeem + role-kolom

**Files:**
- Create: `database/migrations/*_add_role_to_users_table.php`
- Modify: `app/Models/User.php`
- Create: `tests/Feature/RollenTest.php`

- [ ] **Stap 1: Schrijf failing test**

Maak `tests/Feature/RollenTest.php`:

```php
<?php

use App\Models\User;

it('user heeft standaard rol klant', function () {
    $user = User::factory()->create();
    expect($user->role)->toBe('klant');
});

it('user kan kapper rol hebben', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    expect($user->isKapper())->toBeTrue();
});

it('user kan admin rol hebben', function () {
    $user = User::factory()->create(['role' => 'admin']);
    expect($user->isAdmin())->toBeTrue();
});
```

- [ ] **Stap 2: Draai tests — verwacht FAIL**

```bash
php artisan test tests/Feature/RollenTest.php
```

Verwacht: FAIL — `role` kolom bestaat niet.

- [ ] **Stap 3: Maak migration aan**

```bash
php artisan make:migration add_role_to_users_table --table=users
```

Inhoud van de migration:

```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('klant')->after('email');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}
```

- [ ] **Stap 4: Voeg methoden toe aan `app/Models/User.php`**

```php
protected $fillable = [
    'name', 'email', 'password', 'role',
];

public function isAdmin(): bool
{
    return $this->role === 'admin';
}

public function isKapper(): bool
{
    return $this->role === 'kapper';
}

public function isKlant(): bool
{
    return $this->role === 'klant';
}
```

- [ ] **Stap 5: Draai migration**

```bash
php artisan migrate
```

- [ ] **Stap 6: Draai tests — verwacht PASS**

```bash
php artisan test tests/Feature/RollenTest.php
```

Verwacht: 3 tests PASS.

- [ ] **Stap 7: Commit**

```bash
git add -A
git commit -m "feat: add role column to users (admin/kapper/klant)"
```

---

## Task 3: Database migrations — alle tabellen

**Files:**
- Create: `database/migrations/*_create_kappers_table.php`
- Create: `database/migrations/*_create_diensten_table.php`
- Create: `database/migrations/*_create_beschikbaarheden_table.php`
- Create: `database/migrations/*_create_sluitingsdagen_table.php`
- Create: `database/migrations/*_create_afspraken_table.php`
- Create: `database/migrations/*_create_klanten_table.php`

- [ ] **Stap 1: Maak migrations aan**

```bash
php artisan make:migration create_kappers_table
php artisan make:migration create_diensten_table
php artisan make:migration create_beschikbaarheden_table
php artisan make:migration create_sluitingsdagen_table
php artisan make:migration create_afspraken_table
php artisan make:migration create_klanten_table
```

- [ ] **Stap 2: Vul `create_kappers_table` in**

```php
public function up(): void
{
    Schema::create('kappers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('salon_naam');
        $table->string('slug')->unique();
        $table->string('adres')->nullable();
        $table->string('stad');
        $table->string('telefoon')->nullable();
        $table->text('bio')->nullable();
        $table->string('stripe_customer_id')->nullable();
        $table->string('abonnement_status')->default('geen'); // geen/actief/gepauzeerd
        $table->boolean('actief')->default(false);
        $table->timestamps();
    });
}
```

- [ ] **Stap 3: Vul `create_diensten_table` in**

```php
public function up(): void
{
    Schema::create('diensten', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
        $table->string('naam');
        $table->unsignedInteger('duur_minuten');
        $table->unsignedInteger('prijs'); // in centen
        $table->unsignedInteger('no_show_bedrag')->default(0); // in centen
        $table->timestamps();
    });
}
```

- [ ] **Stap 4: Vul `create_beschikbaarheden_table` in**

```php
public function up(): void
{
    Schema::create('beschikbaarheden', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
        $table->unsignedTinyInteger('dag_van_week'); // 0=maandag, 6=zondag
        $table->time('start_tijd');
        $table->time('eind_tijd');
        $table->timestamps();
    });
}
```

- [ ] **Stap 5: Vul `create_sluitingsdagen_table` in**

```php
public function up(): void
{
    Schema::create('sluitingsdagen', function (Blueprint $table) {
        $table->id();
        $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
        $table->date('datum');
        $table->string('reden')->nullable();
        $table->timestamps();
    });
}
```

- [ ] **Stap 6: Vul `create_afspraken_table` in**

```php
public function up(): void
{
    Schema::create('afspraken', function (Blueprint $table) {
        $table->id();
        $table->foreignId('klant_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('kapper_id')->constrained()->cascadeOnDelete();
        $table->foreignId('dienst_id')->constrained()->cascadeOnDelete();
        $table->date('datum');
        $table->time('start_tijd');
        $table->time('eind_tijd');
        $table->string('status')->default('gepland'); // gepland/voltooid/geannuleerd/no_show
        $table->string('betaalmethode')->default('in_zaak'); // online/in_zaak
        $table->string('stripe_payment_intent_id')->nullable();
        $table->string('stripe_setup_intent_id')->nullable();
        $table->timestamps();
    });
}
```

- [ ] **Stap 7: Vul `create_klanten_table` in**

```php
public function up(): void
{
    Schema::create('klanten', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('telefoon')->nullable();
        $table->timestamps();
    });
}
```

- [ ] **Stap 8: Draai alle migrations**

```bash
php artisan migrate
```

Verwacht: alle tabellen aangemaakt zonder errors.

- [ ] **Stap 9: Commit**

```bash
git add -A
git commit -m "feat: add all database migrations"
```

---

## Task 4: Eloquent Models + Factories

**Files:**
- Create: `app/Models/Kapper.php`
- Create: `app/Models/Dienst.php`
- Create: `app/Models/Beschikbaarheid.php`
- Create: `app/Models/Sluitingsdag.php`
- Create: `app/Models/Afspraak.php`
- Create: `app/Models/Klant.php`
- Create: `database/factories/KapperFactory.php`
- Create: `database/factories/DienstFactory.php`

- [ ] **Stap 1: Maak models aan**

```bash
php artisan make:model Kapper
php artisan make:model Dienst
php artisan make:model Beschikbaarheid
php artisan make:model Sluitingsdag
php artisan make:model Afspraak
php artisan make:model Klant
```

- [ ] **Stap 2: Vul `app/Models/Kapper.php` in**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kapper extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'salon_naam', 'slug', 'adres', 'stad',
        'telefoon', 'bio', 'stripe_customer_id',
        'abonnement_status', 'actief',
    ];

    protected $casts = ['actief' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }
    public function diensten() { return $this->hasMany(Dienst::class); }
    public function beschikbaarheden() { return $this->hasMany(Beschikbaarheid::class); }
    public function sluitingsdagen() { return $this->hasMany(Sluitingsdag::class); }
    public function afspraken() { return $this->hasMany(Afspraak::class); }

    public static function generateSlug(string $naam): string
    {
        $slug = Str::slug($naam);
        $count = static::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
```

- [ ] **Stap 3: Vul `app/Models/Dienst.php` in**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dienst extends Model
{
    use HasFactory;

    protected $fillable = ['kapper_id', 'naam', 'duur_minuten', 'prijs', 'no_show_bedrag'];

    public function kapper() { return $this->belongsTo(Kapper::class); }

    public function getPrijsInEurosAttribute(): string
    {
        return number_format($this->prijs / 100, 2, ',', '.');
    }

    public function getNoShowBedragInEurosAttribute(): string
    {
        return number_format($this->no_show_bedrag / 100, 2, ',', '.');
    }
}
```

- [ ] **Stap 4: Vul `app/Models/Beschikbaarheid.php` in**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Beschikbaarheid extends Model
{
    protected $fillable = ['kapper_id', 'dag_van_week', 'start_tijd', 'eind_tijd'];

    public function kapper() { return $this->belongsTo(Kapper::class); }

    public function getDagNaamAttribute(): string
    {
        return ['Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag'][$this->dag_van_week];
    }
}
```

- [ ] **Stap 5: Vul `app/Models/Sluitingsdag.php` in**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sluitingsdag extends Model
{
    protected $fillable = ['kapper_id', 'datum', 'reden'];
    protected $casts = ['datum' => 'date'];
    public function kapper() { return $this->belongsTo(Kapper::class); }
}
```

- [ ] **Stap 6: Vul `app/Models/Afspraak.php` in**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Afspraak extends Model
{
    use HasFactory;

    protected $fillable = [
        'klant_id', 'kapper_id', 'dienst_id', 'datum', 'start_tijd',
        'eind_tijd', 'status', 'betaalmethode',
        'stripe_payment_intent_id', 'stripe_setup_intent_id',
    ];

    protected $casts = ['datum' => 'date'];

    public function klant() { return $this->belongsTo(User::class, 'klant_id'); }
    public function kapper() { return $this->belongsTo(Kapper::class); }
    public function dienst() { return $this->belongsTo(Dienst::class); }
}
```

- [ ] **Stap 7: Vul `app/Models/Klant.php` in**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Klant extends Model
{
    protected $fillable = ['user_id', 'telefoon'];
    public function user() { return $this->belongsTo(User::class); }
}
```

- [ ] **Stap 8: Voeg relaties toe aan `app/Models/User.php`**

```php
public function kapper() { return $this->hasOne(Kapper::class); }
public function klantprofiel() { return $this->hasOne(Klant::class); }
```

- [ ] **Stap 9: Maak KapperFactory aan**

```bash
php artisan make:factory KapperFactory --model=Kapper
```

Inhoud:

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KapperFactory extends Factory
{
    public function definition(): array
    {
        $naam = $this->faker->company();
        return [
            'user_id' => User::factory()->create(['role' => 'kapper'])->id,
            'salon_naam' => $naam,
            'slug' => Str::slug($naam) . '-' . $this->faker->unique()->numberBetween(1, 999),
            'adres' => $this->faker->streetAddress(),
            'stad' => $this->faker->city(),
            'telefoon' => $this->faker->phoneNumber(),
            'bio' => $this->faker->paragraph(),
            'abonnement_status' => 'actief',
            'actief' => true,
        ];
    }
}
```

- [ ] **Stap 10: Maak DienstFactory aan**

```bash
php artisan make:factory DienstFactory --model=Dienst
```

Inhoud:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DienstFactory extends Factory
{
    public function definition(): array
    {
        return [
            'naam' => $this->faker->randomElement(['Knippen', 'Knippen + Wassen', 'Scheren', 'Baard trimmen', 'Highlights']),
            'duur_minuten' => $this->faker->randomElement([30, 45, 60, 90]),
            'prijs' => $this->faker->randomElement([1500, 2000, 2500, 3000, 3500]),
            'no_show_bedrag' => 500,
        ];
    }
}
```

- [ ] **Stap 11: Commit**

```bash
git add -A
git commit -m "feat: add Eloquent models and factories"
```

---

## Task 5: Kapper registratie flow

**Files:**
- Modify: `routes/web.php`
- Create: `app/Http/Livewire/Kapper/Registratie.php`
- Create: `resources/views/livewire/kapper/registratie.blade.php`
- Create: `tests/Feature/KapperRegistratieTest.php`

- [ ] **Stap 1: Schrijf failing tests**

Maak `tests/Feature/KapperRegistratieTest.php`:

```php
<?php

use App\Models\User;
use App\Models\Kapper;
use Livewire\Livewire;
use App\Http\Livewire\Kapper\Registratie;

it('kapper kan zich registreren', function () {
    Livewire::test(Registratie::class)
        ->set('name', 'Jan Jansen')
        ->set('email', 'jan@salon.nl')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('salon_naam', 'Salon Jan')
        ->set('stad', 'Amsterdam')
        ->set('telefoon', '0612345678')
        ->call('registreer')
        ->assertHasNoErrors()
        ->assertRedirect(route('kapper.dashboard'));

    $user = User::where('email', 'jan@salon.nl')->first();
    expect($user->role)->toBe('kapper');
    expect($user->kapper->salon_naam)->toBe('Salon Jan');
    expect($user->kapper->slug)->toBe('salon-jan');
});

it('kapper registratie vereist salon_naam en stad', function () {
    Livewire::test(Registratie::class)
        ->set('name', 'Jan')
        ->set('email', 'jan@salon.nl')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('registreer')
        ->assertHasErrors(['salon_naam', 'stad']);
});
```

- [ ] **Stap 2: Draai tests — verwacht FAIL**

```bash
php artisan test tests/Feature/KapperRegistratieTest.php
```

- [ ] **Stap 3: Maak Livewire component aan**

```bash
php artisan make:livewire Kapper/Registratie
```

- [ ] **Stap 4: Vul `app/Http/Livewire/Kapper/Registratie.php` in**

```php
<?php

namespace App\Http\Livewire\Kapper;

use App\Models\Kapper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Registratie extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $salon_naam = '';
    public string $stad = '';
    public string $telefoon = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'salon_naam' => 'required|string|max:255',
            'stad' => 'required|string|max:255',
            'telefoon' => 'nullable|string|max:20',
        ];
    }

    public function registreer()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'kapper',
        ]);

        Kapper::create([
            'user_id' => $user->id,
            'salon_naam' => $this->salon_naam,
            'slug' => Kapper::generateSlug($this->salon_naam),
            'stad' => $this->stad,
            'telefoon' => $this->telefoon,
            'abonnement_status' => 'geen',
            'actief' => false,
        ]);

        Auth::login($user);

        return redirect()->route('kapper.dashboard');
    }

    public function render()
    {
        return view('livewire.kapper.registratie')->layout('layouts.publiek');
    }
}
```

- [ ] **Stap 5: Maak `resources/views/livewire/kapper/registratie.blade.php`**

```blade
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="bg-white p-8 rounded-lg shadow w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6">Registreer als kapper</h1>
        <form wire:submit="registreer" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Naam</label>
                <input wire:model="name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">E-mailadres</label>
                <input wire:model="email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Wachtwoord</label>
                <input wire:model="password" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Wachtwoord bevestigen</label>
                <input wire:model="password_confirmation" type="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <hr>
            <div>
                <label class="block text-sm font-medium text-gray-700">Saloonnaam</label>
                <input wire:model="salon_naam" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('salon_naam') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Stad</label>
                <input wire:model="stad" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('stad') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Telefoonnummer</label>
                <input wire:model="telefoon" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Registreren
            </button>
        </form>
    </div>
</div>
```

- [ ] **Stap 6: Draai tests — verwacht PASS**

```bash
php artisan test tests/Feature/KapperRegistratieTest.php
```

- [ ] **Stap 7: Commit**

```bash
git add -A
git commit -m "feat: kapper registratie flow"
```

---

## Task 6: Diensten beheer (kapper)

**Files:**
- Create: `app/Http/Livewire/Kapper/DienstenBeheer.php`
- Create: `resources/views/livewire/kapper/diensten-beheer.blade.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/DienstenTest.php`

- [ ] **Stap 1: Schrijf failing tests**

```php
<?php

use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Kapper\DienstenBeheer;

it('kapper kan dienst toevoegen', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    $kapper = Kapper::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(DienstenBeheer::class)
        ->set('naam', 'Knippen')
        ->set('duur_minuten', 30)
        ->set('prijs', '15.00')
        ->set('no_show_bedrag', '5.00')
        ->call('opslaan')
        ->assertHasNoErrors();

    expect(Dienst::where('kapper_id', $kapper->id)->where('naam', 'Knippen')->exists())->toBeTrue();
});

it('kapper kan dienst verwijderen', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    $kapper = Kapper::factory()->create(['user_id' => $user->id]);
    $dienst = Dienst::factory()->create(['kapper_id' => $kapper->id]);

    Livewire::actingAs($user)
        ->test(DienstenBeheer::class)
        ->call('verwijder', $dienst->id)
        ->assertHasNoErrors();

    expect(Dienst::find($dienst->id))->toBeNull();
});
```

- [ ] **Stap 2: Draai tests — verwacht FAIL**

```bash
php artisan test tests/Feature/DienstenTest.php
```

- [ ] **Stap 3: Maak component + view aan**

```bash
php artisan make:livewire Kapper/DienstenBeheer
```

- [ ] **Stap 4: Vul `app/Http/Livewire/Kapper/DienstenBeheer.php` in**

```php
<?php

namespace App\Http\Livewire\Kapper;

use App\Models\Dienst;
use Livewire\Component;

class DienstenBeheer extends Component
{
    public string $naam = '';
    public int $duur_minuten = 30;
    public string $prijs = '';
    public string $no_show_bedrag = '0.00';
    public ?int $bewerkenId = null;

    protected function rules(): array
    {
        return [
            'naam' => 'required|string|max:255',
            'duur_minuten' => 'required|integer|min:5|max:480',
            'prijs' => 'required|numeric|min:0',
            'no_show_bedrag' => 'required|numeric|min:0',
        ];
    }

    public function opslaan(): void
    {
        $this->validate();
        $kapper = auth()->user()->kapper;
        $data = [
            'naam' => $this->naam,
            'duur_minuten' => $this->duur_minuten,
            'prijs' => (int) round((float) $this->prijs * 100),
            'no_show_bedrag' => (int) round((float) $this->no_show_bedrag * 100),
        ];

        if ($this->bewerkenId) {
            Dienst::where('id', $this->bewerkenId)->where('kapper_id', $kapper->id)->update($data);
        } else {
            $kapper->diensten()->create($data);
        }

        $this->reset(['naam', 'duur_minuten', 'prijs', 'no_show_bedrag', 'bewerkenId']);
    }

    public function bewerk(int $id): void
    {
        $dienst = Dienst::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->firstOrFail();
        $this->bewerkenId = $dienst->id;
        $this->naam = $dienst->naam;
        $this->duur_minuten = $dienst->duur_minuten;
        $this->prijs = number_format($dienst->prijs / 100, 2, '.', '');
        $this->no_show_bedrag = number_format($dienst->no_show_bedrag / 100, 2, '.', '');
    }

    public function verwijder(int $id): void
    {
        Dienst::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->delete();
    }

    public function render()
    {
        return view('livewire.kapper.diensten-beheer', [
            'diensten' => auth()->user()->kapper->diensten()->orderBy('naam')->get(),
        ])->layout('layouts.kapper');
    }
}
```

- [ ] **Stap 5: Maak `resources/views/livewire/kapper/diensten-beheer.blade.php`**

```blade
<div>
    <h2 class="text-xl font-bold mb-4">Diensten</h2>
    <form wire:submit="opslaan" class="bg-white p-4 rounded shadow mb-6 space-y-3">
        <h3 class="font-semibold">{{ $bewerkenId ? 'Dienst bewerken' : 'Nieuwe dienst' }}</h3>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="text-sm font-medium">Naam</label>
                <input wire:model="naam" type="text" class="mt-1 block w-full rounded border-gray-300">
                @error('naam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Duur (minuten)</label>
                <input wire:model="duur_minuten" type="number" min="5" class="mt-1 block w-full rounded border-gray-300">
                @error('duur_minuten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">Prijs (€)</label>
                <input wire:model="prijs" type="text" placeholder="15.00" class="mt-1 block w-full rounded border-gray-300">
                @error('prijs') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-sm font-medium">No-show bedrag (€)</label>
                <input wire:model="no_show_bedrag" type="text" placeholder="5.00" class="mt-1 block w-full rounded border-gray-300">
                @error('no_show_bedrag') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            {{ $bewerkenId ? 'Bijwerken' : 'Toevoegen' }}
        </button>
    </form>

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Naam</th>
                    <th class="px-4 py-2 text-left">Duur</th>
                    <th class="px-4 py-2 text-left">Prijs</th>
                    <th class="px-4 py-2 text-left">No-show</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($diensten as $dienst)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $dienst->naam }}</td>
                    <td class="px-4 py-2">{{ $dienst->duur_minuten }} min</td>
                    <td class="px-4 py-2">€ {{ $dienst->prijs_in_euros }}</td>
                    <td class="px-4 py-2">€ {{ $dienst->no_show_bedrag_in_euros }}</td>
                    <td class="px-4 py-2 space-x-2">
                        <button wire:click="bewerk({{ $dienst->id }})" class="text-indigo-600 hover:underline">Bewerk</button>
                        <button wire:click="verwijder({{ $dienst->id }})" wire:confirm="Weet je het zeker?" class="text-red-600 hover:underline">Verwijder</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-4 text-gray-500 text-center">Nog geen diensten.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
```

- [ ] **Stap 6: Draai tests — verwacht PASS**

```bash
php artisan test tests/Feature/DienstenTest.php
```

- [ ] **Stap 7: Commit**

```bash
git add -A
git commit -m "feat: kapper diensten beheer (CRUD)"
```

---

## Task 7: Beschikbaarheid instellen (kapper)

**Files:**
- Create: `app/Http/Livewire/Kapper/BeschikbaarheidBeheer.php`
- Create: `resources/views/livewire/kapper/beschikbaarheid-beheer.blade.php`
- Create: `tests/Feature/BeschikbaarheidTest.php`

- [ ] **Stap 1: Schrijf failing test**

```php
<?php

use App\Models\Beschikbaarheid;
use App\Models\Kapper;
use App\Models\User;
use Livewire\Livewire;
use App\Http\Livewire\Kapper\BeschikbaarheidBeheer;

it('kapper kan beschikbaarheid opslaan', function () {
    $user = User::factory()->create(['role' => 'kapper']);
    $kapper = Kapper::factory()->create(['user_id' => $user->id]);

    Livewire::actingAs($user)
        ->test(BeschikbaarheidBeheer::class)
        ->set('rooster.0.actief', true)
        ->set('rooster.0.start_tijd', '09:00')
        ->set('rooster.0.eind_tijd', '17:00')
        ->call('opslaan')
        ->assertHasNoErrors();

    expect(Beschikbaarheid::where('kapper_id', $kapper->id)->where('dag_van_week', 0)->exists())->toBeTrue();
});
```

- [ ] **Stap 2: Draai test — verwacht FAIL**

```bash
php artisan test tests/Feature/BeschikbaarheidTest.php
```

- [ ] **Stap 3: Maak component aan**

```bash
php artisan make:livewire Kapper/BeschikbaarheidBeheer
```

- [ ] **Stap 4: Vul `app/Http/Livewire/Kapper/BeschikbaarheidBeheer.php` in**

```php
<?php

namespace App\Http\Livewire\Kapper;

use App\Models\Beschikbaarheid;
use App\Models\Sluitingsdag;
use Livewire\Component;

class BeschikbaarheidBeheer extends Component
{
    public array $rooster = [];
    public string $sluitingsDatum = '';
    public string $sluitingsReden = '';

    protected $dagNamen = ['Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag','Zondag'];

    public function mount(): void
    {
        $kapper = auth()->user()->kapper;
        $bestaand = $kapper->beschikbaarheden()->get()->keyBy('dag_van_week');

        for ($dag = 0; $dag <= 6; $dag++) {
            $this->rooster[$dag] = [
                'naam' => $this->dagNamen[$dag],
                'actief' => isset($bestaand[$dag]),
                'start_tijd' => $bestaand[$dag]->start_tijd ?? '09:00',
                'eind_tijd' => $bestaand[$dag]->eind_tijd ?? '17:00',
            ];
        }
    }

    public function opslaan(): void
    {
        $kapper = auth()->user()->kapper;
        $kapper->beschikbaarheden()->delete();

        foreach ($this->rooster as $dag => $data) {
            if ($data['actief']) {
                Beschikbaarheid::create([
                    'kapper_id' => $kapper->id,
                    'dag_van_week' => $dag,
                    'start_tijd' => $data['start_tijd'],
                    'eind_tijd' => $data['eind_tijd'],
                ]);
            }
        }

        session()->flash('message', 'Beschikbaarheid opgeslagen.');
    }

    public function sluitingsdagToevoegen(): void
    {
        $this->validate(['sluitingsDatum' => 'required|date|after_or_equal:today']);

        auth()->user()->kapper->sluitingsdagen()->create([
            'datum' => $this->sluitingsDatum,
            'reden' => $this->sluitingsReden ?: null,
        ]);

        $this->reset(['sluitingsDatum', 'sluitingsReden']);
    }

    public function sluitingsdagVerwijderen(int $id): void
    {
        Sluitingsdag::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->delete();
    }

    public function render()
    {
        return view('livewire.kapper.beschikbaarheid-beheer', [
            'sluitingsdagen' => auth()->user()->kapper->sluitingsdagen()
                ->where('datum', '>=', today())
                ->orderBy('datum')
                ->get(),
        ])->layout('layouts.kapper');
    }
}
```

- [ ] **Stap 5: Maak `resources/views/livewire/kapper/beschikbaarheid-beheer.blade.php`**

```blade
<div>
    <h2 class="text-xl font-bold mb-4">Beschikbaarheid</h2>

    @if(session('message'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('message') }}</div>
    @endif

    <form wire:submit="opslaan" class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-3">Weekrooster</h3>
        @foreach($rooster as $dag => $data)
        <div class="flex items-center gap-4 py-2 border-b last:border-b-0">
            <div class="w-24">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model.live="rooster.{{ $dag }}.actief" type="checkbox" class="rounded">
                    <span class="text-sm font-medium">{{ $data['naam'] }}</span>
                </label>
            </div>
            @if($data['actief'])
            <input wire:model="rooster.{{ $dag }}.start_tijd" type="time" class="rounded border-gray-300 text-sm">
            <span class="text-gray-500">tot</span>
            <input wire:model="rooster.{{ $dag }}.eind_tijd" type="time" class="rounded border-gray-300 text-sm">
            @endif
        </div>
        @endforeach
        <button type="submit" class="mt-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Opslaan</button>
    </form>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-3">Sluitingsdagen / Vakantie</h3>
        <form wire:submit="sluitingsdagToevoegen" class="flex gap-3 mb-4">
            <input wire:model="sluitingsDatum" type="date" class="rounded border-gray-300">
            <input wire:model="sluitingsReden" type="text" placeholder="Reden (optioneel)" class="rounded border-gray-300 flex-1">
            @error('sluitingsDatum') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
            <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded">Toevoegen</button>
        </form>
        <ul class="space-y-1">
            @forelse($sluitingsdagen as $dag)
            <li class="flex justify-between text-sm py-1 border-b">
                <span>{{ $dag->datum->format('d-m-Y') }} {{ $dag->reden ? '— '.$dag->reden : '' }}</span>
                <button wire:click="sluitingsdagVerwijderen({{ $dag->id }})" class="text-red-600 hover:underline">Verwijder</button>
            </li>
            @empty
            <li class="text-gray-500 text-sm">Geen sluitingsdagen gepland.</li>
            @endforelse
        </ul>
    </div>
</div>
```

- [ ] **Stap 6: Draai tests — verwacht PASS**

```bash
php artisan test tests/Feature/BeschikbaarheidTest.php
```

- [ ] **Stap 7: Commit**

```bash
git add -A
git commit -m "feat: kapper beschikbaarheid en sluitingsdagen beheer"
```

---

## Task 8: Publieke zoekpagina + profielpagina

**Files:**
- Create: `app/Http/Livewire/Klant/KapperZoeken.php`
- Create: `resources/views/livewire/klant/kapper-zoeken.blade.php`
- Create: `resources/views/pages/kapper-profiel.blade.php`
- Modify: `routes/web.php`
- Create: `tests/Feature/KapperZoekenTest.php`

- [ ] **Stap 1: Schrijf failing tests**

```php
<?php

use App\Models\Kapper;
use Livewire\Livewire;
use App\Http\Livewire\Klant\KapperZoeken;

it('klant kan kappers zoeken op stad', function () {
    Kapper::factory()->create(['stad' => 'Amsterdam', 'actief' => true, 'abonnement_status' => 'actief']);
    Kapper::factory()->create(['stad' => 'Rotterdam', 'actief' => true, 'abonnement_status' => 'actief']);

    Livewire::test(KapperZoeken::class)
        ->set('zoekterm', 'Amsterdam')
        ->assertSee('Amsterdam')
        ->assertDontSee('Rotterdam');
});

it('inactieve kappers zijn niet zichtbaar', function () {
    $kapper = Kapper::factory()->create(['stad' => 'Amsterdam', 'actief' => false]);

    Livewire::test(KapperZoeken::class)
        ->set('zoekterm', 'Amsterdam')
        ->assertDontSee($kapper->salon_naam);
});

it('kapper profielpagina is bereikbaar via slug', function () {
    $kapper = Kapper::factory()->create(['actief' => true, 'abonnement_status' => 'actief']);

    $this->get("/kapper/{$kapper->slug}")
        ->assertOk()
        ->assertSee($kapper->salon_naam);
});
```

- [ ] **Stap 2: Draai tests — verwacht FAIL**

```bash
php artisan test tests/Feature/KapperZoekenTest.php
```

- [ ] **Stap 3: Maak component aan**

```bash
php artisan make:livewire Klant/KapperZoeken
```

- [ ] **Stap 4: Vul `app/Http/Livewire/Klant/KapperZoeken.php` in**

```php
<?php

namespace App\Http\Livewire\Klant;

use App\Models\Kapper;
use Livewire\Component;

class KapperZoeken extends Component
{
    public string $zoekterm = '';

    public function render()
    {
        $kappers = Kapper::where('actief', true)
            ->where('abonnement_status', 'actief')
            ->when($this->zoekterm, function ($query) {
                $query->where(function ($q) {
                    $q->where('stad', 'like', "%{$this->zoekterm}%")
                      ->orWhere('salon_naam', 'like', "%{$this->zoekterm}%");
                });
            })
            ->with('diensten')
            ->orderBy('salon_naam')
            ->get();

        return view('livewire.klant.kapper-zoeken', compact('kappers'))
            ->layout('layouts.publiek');
    }
}
```

- [ ] **Stap 5: Maak views**

`resources/views/livewire/klant/kapper-zoeken.blade.php`:

```blade
<div>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Vind een kapper bij jou in de buurt</h1>
        <input wire:model.live="zoekterm" type="text"
            placeholder="Zoek op stad of naam..."
            class="w-full rounded-lg border-gray-300 shadow-sm text-lg px-4 py-3 mb-8">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($kappers as $kapper)
            <a href="{{ route('kapper.profiel', $kapper->slug) }}" class="bg-white rounded-lg shadow p-4 hover:shadow-md transition">
                <h2 class="font-bold text-lg">{{ $kapper->salon_naam }}</h2>
                <p class="text-gray-500 text-sm">{{ $kapper->stad }}</p>
                <p class="text-gray-600 text-sm mt-2 line-clamp-2">{{ $kapper->bio }}</p>
                <p class="text-indigo-600 text-sm mt-2 font-medium">{{ $kapper->diensten->count() }} diensten</p>
            </a>
            @empty
            <p class="col-span-3 text-gray-500 text-center py-8">Geen kappers gevonden.</p>
            @endforelse
        </div>
    </div>
</div>
```

`resources/views/pages/kapper-profiel.blade.php`:

```blade
@extends('layouts.publiek')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold">{{ $kapper->salon_naam }}</h1>
    <p class="text-gray-500">{{ $kapper->adres }}, {{ $kapper->stad }}</p>
    <p class="mt-4 text-gray-700">{{ $kapper->bio }}</p>

    <h2 class="text-xl font-bold mt-8 mb-4">Diensten</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($kapper->diensten as $dienst)
        <div class="bg-white rounded shadow p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold">{{ $dienst->naam }}</p>
                <p class="text-gray-500 text-sm">{{ $dienst->duur_minuten }} minuten</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-indigo-600">€ {{ $dienst->prijs_in_euros }}</p>
                <a href="{{ route('boeken', ['kapperSlug' => $kapper->slug, 'dienstId' => $dienst->id]) }}"
                   class="bg-indigo-600 text-white text-sm px-3 py-1 rounded hover:bg-indigo-700 mt-1 inline-block">
                    Boek
                </a>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
```

- [ ] **Stap 6: Draai tests — verwacht PASS**

```bash
php artisan test tests/Feature/KapperZoekenTest.php
```

- [ ] **Stap 7: Commit**

```bash
git add -A
git commit -m "feat: publieke kapper zoekpagina en profielpagina"
```

---

## Task 9: BeschikbaarheidsService + Boekingswizard

**Files:**
- Create: `app/Services/BeschikbaarheidsService.php`
- Create: `app/Http/Livewire/Klant/BoekingWizard.php`
- Create: `resources/views/livewire/klant/boeking-wizard.blade.php`
- Create: `tests/Feature/BoekingTest.php`

- [ ] **Stap 1: Schrijf failing tests**

```php
<?php

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\User;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Livewire\Livewire;
use App\Http\Livewire\Klant\BoekingWizard;

it('beschikbaarheidsservice geeft vrije tijdslots', function () {
    $kapper = Kapper::factory()->create();
    $dienst = Dienst::factory()->create(['kapper_id' => $kapper->id, 'duur_minuten' => 30]);

    Beschikbaarheid::create([
        'kapper_id' => $kapper->id,
        'dag_van_week' => 0,
        'start_tijd' => '09:00',
        'eind_tijd' => '12:00',
    ]);

    $maandag = Carbon::now()->next('Monday')->toDateString();
    $service = new BeschikbaarheidsService();
    $slots = $service->getVrijeTijdslots($kapper, $dienst, $maandag);

    expect($slots)->not->toBeEmpty();
    expect($slots[0])->toBe('09:00');
});

it('klant kan afspraak inplannen', function () {
    $klant = User::factory()->create(['role' => 'klant']);
    $kapper = Kapper::factory()->create();
    $dienst = Dienst::factory()->create(['kapper_id' => $kapper->id, 'duur_minuten' => 30]);

    Beschikbaarheid::create([
        'kapper_id' => $kapper->id,
        'dag_van_week' => 0,
        'start_tijd' => '09:00',
        'eind_tijd' => '17:00',
    ]);

    $maandag = Carbon::now()->next('Monday')->toDateString();

    Livewire::actingAs($klant)
        ->test(BoekingWizard::class, ['kapperSlug' => $kapper->slug, 'dienstId' => $dienst->id])
        ->set('gekozenDatum', $maandag)
        ->set('gekozenTijdslot', '09:00')
        ->set('betaalmethode', 'in_zaak')
        ->call('bevestig')
        ->assertHasNoErrors()
        ->assertRedirect();

    expect(Afspraak::where('klant_id', $klant->id)->where('kapper_id', $kapper->id)->exists())->toBeTrue();
});

it('dubbele boeking op hetzelfde tijdslot is niet mogelijk', function () {
    $klant = User::factory()->create(['role' => 'klant']);
    $kapper = Kapper::factory()->create();
    $dienst = Dienst::factory()->create(['kapper_id' => $kapper->id, 'duur_minuten' => 30]);
    $maandag = Carbon::now()->next('Monday')->toDateString();

    Afspraak::create([
        'klant_id' => $klant->id,
        'kapper_id' => $kapper->id,
        'dienst_id' => $dienst->id,
        'datum' => $maandag,
        'start_tijd' => '09:00',
        'eind_tijd' => '09:30',
        'status' => 'gepland',
        'betaalmethode' => 'in_zaak',
    ]);

    Beschikbaarheid::create([
        'kapper_id' => $kapper->id,
        'dag_van_week' => 0,
        'start_tijd' => '09:00',
        'eind_tijd' => '17:00',
    ]);

    $service = new BeschikbaarheidsService();
    $slots = $service->getVrijeTijdslots($kapper, $dienst, $maandag);

    expect($slots)->not->toContain('09:00');
});
```

- [ ] **Stap 2: Draai tests — verwacht FAIL**

```bash
php artisan test tests/Feature/BoekingTest.php
```

- [ ] **Stap 3: Maak `app/Services/BeschikbaarheidsService.php`**

```php
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
        $dagVanWeek = $date->dayOfWeekIso - 1; // 0=maandag

        $beschikbaarheid = Beschikbaarheid::where('kapper_id', $kapper->id)
            ->where('dag_van_week', $dagVanWeek)
            ->first();

        if (!$beschikbaarheid) return [];

        if ($kapper->sluitingsdagen()->whereDate('datum', $datum)->exists()) return [];

        $geboekteAfspraken = Afspraak::where('kapper_id', $kapper->id)
            ->where('datum', $datum)
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
```

- [ ] **Stap 4: Maak BoekingWizard component**

```bash
php artisan make:livewire Klant/BoekingWizard
```

Inhoud `app/Http/Livewire/Klant/BoekingWizard.php`:

```php
<?php

namespace App\Http\Livewire\Klant;

use App\Models\Afspraak;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Livewire\Component;

class BoekingWizard extends Component
{
    public Kapper $kapper;
    public Dienst $dienst;
    public string $gekozenDatum = '';
    public string $gekozenTijdslot = '';
    public string $betaalmethode = 'in_zaak';

    public function mount(string $kapperSlug, int $dienstId): void
    {
        $this->kapper = Kapper::where('slug', $kapperSlug)->where('actief', true)->firstOrFail();
        $this->dienst = Dienst::where('id', $dienstId)->where('kapper_id', $this->kapper->id)->firstOrFail();
        $this->gekozenDatum = Carbon::now()->addDay()->toDateString();
    }

    public function getVrijeSlotsProperty(): array
    {
        if (!$this->gekozenDatum) return [];
        return (new BeschikbaarheidsService())->getVrijeTijdslots($this->kapper, $this->dienst, $this->gekozenDatum);
    }

    public function bevestig(): void
    {
        $this->validate([
            'gekozenDatum' => 'required|date|after_or_equal:today',
            'gekozenTijdslot' => 'required|string',
            'betaalmethode' => 'required|in:online,in_zaak',
        ]);

        $eind = Carbon::parse("{$this->gekozenDatum} {$this->gekozenTijdslot}")
            ->addMinutes($this->dienst->duur_minuten)
            ->format('H:i');

        Afspraak::create([
            'klant_id' => auth()->id(),
            'kapper_id' => $this->kapper->id,
            'dienst_id' => $this->dienst->id,
            'datum' => $this->gekozenDatum,
            'start_tijd' => $this->gekozenTijdslot,
            'eind_tijd' => $eind,
            'status' => 'gepland',
            'betaalmethode' => $this->betaalmethode,
        ]);

        session()->flash('boeking_bevestigd', true);
        $this->redirect(route('klant.afspraken'));
    }

    public function render()
    {
        return view('livewire.klant.boeking-wizard')->layout('layouts.publiek');
    }
}
```

Inhoud `resources/views/livewire/klant/boeking-wizard.blade.php`:

```blade
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-2">Afspraak boeken</h1>
    <p class="text-gray-600 mb-6">{{ $kapper->salon_naam }} — {{ $dienst->naam }} ({{ $dienst->duur_minuten }} min, € {{ $dienst->prijs_in_euros }})</p>

    <form wire:submit="bevestig" class="space-y-6">
        <div>
            <label class="block font-medium mb-1">Kies een datum</label>
            <input wire:model.live="gekozenDatum" type="date"
                min="{{ today()->addDay()->toDateString() }}"
                class="rounded border-gray-300 w-full">
            @error('gekozenDatum') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        @if($gekozenDatum)
        <div>
            <label class="block font-medium mb-2">Kies een tijdstip</label>
            @if(count($vrijeslots) === 0)
                <p class="text-gray-500">Geen beschikbare tijdsloten op deze datum.</p>
            @else
            <div class="grid grid-cols-4 gap-2">
                @foreach($vrijeslots as $slot)
                <button type="button"
                    wire:click="$set('gekozenTijdslot', '{{ $slot }}')"
                    class="py-2 rounded text-sm font-medium border {{ $gekozenTijdslot === $slot ? 'bg-indigo-600 text-white border-indigo-600' : 'border-gray-300 hover:border-indigo-400' }}">
                    {{ $slot }}
                </button>
                @endforeach
            </div>
            @error('gekozenTijdslot') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            @endif
        </div>
        @endif

        @if($gekozenTijdslot)
        <div>
            <label class="block font-medium mb-2">Betaalmethode</label>
            <div class="flex gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="betaalmethode" type="radio" value="in_zaak"> In de zaak betalen
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="betaalmethode" type="radio" value="online"> Online betalen
                </label>
            </div>
        </div>

        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700">
            Afspraak bevestigen
        </button>
        @endif
    </form>
</div>
```

- [ ] **Stap 5: Draai tests — verwacht PASS**

```bash
php artisan test tests/Feature/BoekingTest.php
```

- [ ] **Stap 6: Commit**

```bash
git add -A
git commit -m "feat: boekingswizard met beschikbaarheidssysteem"
```

---

## Task 10: Layouts + Kapper agenda + Klant afspraken + Admin + Routes

**Files:**
- Create: `resources/views/layouts/publiek.blade.php`
- Create: `resources/views/layouts/kapper.blade.php`
- Create: `resources/views/layouts/klant.blade.php`
- Create: `resources/views/layouts/admin.blade.php`
- Create: `app/Http/Livewire/Kapper/AgendaOverzicht.php`
- Create: `resources/views/livewire/kapper/agenda-overzicht.blade.php`
- Create: `app/Http/Livewire/Klant/MijnAfspraken.php`
- Create: `resources/views/livewire/klant/mijn-afspraken.blade.php`
- Create: `app/Http/Livewire/Kapper/ProfielBeheer.php`
- Create: `resources/views/livewire/kapper/profiel-beheer.blade.php`
- Create: `app/Http/Livewire/Admin/KappersOverzicht.php`
- Create: `resources/views/livewire/admin/kappers-overzicht.blade.php`
- Create: `database/seeders/AdminSeeder.php`
- Modify: `routes/web.php`

- [ ] **Stap 1: Maak `resources/views/layouts/publiek.blade.php`**

```blade
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b px-4 py-3 flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-indigo-600 text-lg">{{ config('app.name') }}</a>
        <div class="flex gap-4 text-sm">
            @auth
                <a href="{{ auth()->user()->isKapper() ? route('kapper.dashboard') : route('klant.afspraken') }}" class="text-gray-600 hover:text-indigo-600">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Inloggen</a>
                <a href="{{ route('kapper.registreer') }}" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700">Kapper worden</a>
            @endauth
        </div>
    </nav>
    <main>{{ $slot }}</main>
    @livewireScripts
</body>
</html>
```

- [ ] **Stap 2: Maak `resources/views/layouts/kapper.blade.php`**

```blade
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} — Kapper</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen flex">
    <aside class="w-56 bg-gray-900 text-gray-300 min-h-screen p-4 flex flex-col gap-1">
        <p class="text-white font-bold text-lg mb-6">{{ auth()->user()->kapper->salon_naam ?? config('app.name') }}</p>
        <a href="{{ route('kapper.dashboard') }}" class="px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('kapper.dashboard') ? 'bg-gray-700 text-white' : '' }}">Agenda</a>
        <a href="{{ route('kapper.diensten') }}" class="px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('kapper.diensten') ? 'bg-gray-700 text-white' : '' }}">Diensten</a>
        <a href="{{ route('kapper.beschikbaarheid') }}" class="px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('kapper.beschikbaarheid') ? 'bg-gray-700 text-white' : '' }}">Beschikbaarheid</a>
        <a href="{{ route('kapper.profiel-beheer') }}" class="px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('kapper.profiel-beheer') ? 'bg-gray-700 text-white' : '' }}">Profiel</a>
        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-700 rounded text-sm">Uitloggen</button>
            </form>
        </div>
    </aside>
    <main class="flex-1 p-6">{{ $slot }}</main>
    @livewireScripts
</body>
</html>
```

- [ ] **Stap 3: Maak `resources/views/layouts/klant.blade.php`**

```blade
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b px-4 py-3 flex justify-between items-center">
        <a href="{{ route('home') }}" class="font-bold text-indigo-600 text-lg">{{ config('app.name') }}</a>
        <div class="flex gap-4 text-sm">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600">Kappers zoeken</a>
            <a href="{{ route('klant.afspraken') }}" class="text-gray-600 hover:text-indigo-600">Mijn afspraken</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-gray-600 hover:text-indigo-600">Uitloggen</button>
            </form>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto px-4 py-8">{{ $slot }}</main>
    @livewireScripts
</body>
</html>
```

- [ ] **Stap 4: Maak `resources/views/layouts/admin.blade.php`**

```blade
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen flex">
    <aside class="w-56 bg-gray-900 text-gray-300 min-h-screen p-4">
        <p class="text-white font-bold text-lg mb-6">Admin</p>
        <a href="{{ route('admin.kappers') }}" class="block px-3 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.kappers') ? 'bg-gray-700 text-white' : '' }}">Kappers</a>
    </aside>
    <main class="flex-1 p-6">{{ $slot }}</main>
    @livewireScripts
</body>
</html>
```

- [ ] **Stap 5: Maak AgendaOverzicht component**

```bash
php artisan make:livewire Kapper/AgendaOverzicht
```

Inhoud `app/Http/Livewire/Kapper/AgendaOverzicht.php`:

```php
<?php

namespace App\Http\Livewire\Kapper;

use App\Models\Afspraak;
use Livewire\Component;

class AgendaOverzicht extends Component
{
    public string $geselecteerdeDatum;

    public function mount(): void
    {
        $this->geselecteerdeDatum = today()->toDateString();
    }

    public function noShow(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'no_show']);
    }

    public function voltooid(int $id): void
    {
        Afspraak::where('id', $id)->where('kapper_id', auth()->user()->kapper->id)->update(['status' => 'voltooid']);
    }

    public function render()
    {
        return view('livewire.kapper.agenda-overzicht', [
            'afspraken' => Afspraak::where('kapper_id', auth()->user()->kapper->id)
                ->where('datum', $this->geselecteerdeDatum)
                ->with(['klant', 'dienst'])
                ->orderBy('start_tijd')
                ->get(),
        ])->layout('layouts.kapper');
    }
}
```

Inhoud `resources/views/livewire/kapper/agenda-overzicht.blade.php`:

```blade
<div>
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold">Agenda</h2>
        <input wire:model.live="geselecteerdeDatum" type="date" class="rounded border-gray-300">
    </div>
    <div class="space-y-3">
        @forelse($afspraken as $afspraak)
        <div class="bg-white rounded shadow p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold">{{ $afspraak->start_tijd }} — {{ $afspraak->eind_tijd }}</p>
                <p class="text-gray-700">{{ $afspraak->klant->name }}</p>
                <p class="text-gray-500 text-sm">{{ $afspraak->dienst->naam }} · {{ $afspraak->betaalmethode === 'online' ? 'Online betaald' : 'In zaak betalen' }}</p>
            </div>
            <div class="flex gap-2">
                @if($afspraak->status === 'gepland')
                <button wire:click="voltooid({{ $afspraak->id }})" class="text-sm bg-green-600 text-white px-3 py-1 rounded">Voltooid</button>
                <button wire:click="noShow({{ $afspraak->id }})" wire:confirm="No-show markeren?" class="text-sm bg-red-600 text-white px-3 py-1 rounded">No-show</button>
                @else
                <span class="text-sm px-3 py-1 rounded
                    {{ $afspraak->status === 'voltooid' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $afspraak->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}
                    {{ $afspraak->status === 'geannuleerd' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
                </span>
                @endif
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center py-8 bg-white rounded shadow">Geen afspraken op deze dag.</p>
        @endforelse
    </div>
</div>
```

- [ ] **Stap 6: Maak MijnAfspraken component**

```bash
php artisan make:livewire Klant/MijnAfspraken
```

Inhoud `app/Http/Livewire/Klant/MijnAfspraken.php`:

```php
<?php

namespace App\Http\Livewire\Klant;

use App\Models\Afspraak;
use Livewire\Component;

class MijnAfspraken extends Component
{
    public function annuleer(int $id): void
    {
        Afspraak::where('id', $id)->where('klant_id', auth()->id())->where('status', 'gepland')->update(['status' => 'geannuleerd']);
    }

    public function render()
    {
        return view('livewire.klant.mijn-afspraken', [
            'aankomend' => Afspraak::where('klant_id', auth()->id())
                ->where('datum', '>=', today())
                ->where('status', 'gepland')
                ->with(['kapper', 'dienst'])
                ->orderBy('datum')->orderBy('start_tijd')->get(),
            'geschiedenis' => Afspraak::where('klant_id', auth()->id())
                ->where(fn($q) => $q->where('datum', '<', today())->orWhereNotIn('status', ['gepland']))
                ->with(['kapper', 'dienst'])
                ->orderByDesc('datum')->limit(20)->get(),
        ])->layout('layouts.klant');
    }
}
```

Inhoud `resources/views/livewire/klant/mijn-afspraken.blade.php`:

```blade
<div>
    @if(session('boeking_bevestigd'))
    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6">Afspraak bevestigd!</div>
    @endif

    <h2 class="text-xl font-bold mb-4">Aankomende afspraken</h2>
    <div class="space-y-3 mb-8">
        @forelse($aankomend as $afspraak)
        <div class="bg-white rounded shadow p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold">{{ $afspraak->datum->format('d-m-Y') }} om {{ $afspraak->start_tijd }}</p>
                <p class="text-gray-700">{{ $afspraak->kapper->salon_naam }}</p>
                <p class="text-gray-500 text-sm">{{ $afspraak->dienst->naam }}</p>
            </div>
            <button wire:click="annuleer({{ $afspraak->id }})" wire:confirm="Afspraak annuleren?"
                class="text-sm text-red-600 border border-red-300 px-3 py-1 rounded hover:bg-red-50">
                Annuleer
            </button>
        </div>
        @empty
        <p class="text-gray-500">Geen aankomende afspraken.</p>
        @endforelse
    </div>

    <h2 class="text-xl font-bold mb-4">Geschiedenis</h2>
    <div class="space-y-2">
        @forelse($geschiedenis as $afspraak)
        <div class="bg-white rounded shadow p-3 flex justify-between items-center text-sm">
            <span class="font-medium">{{ $afspraak->datum->format('d-m-Y') }}</span>
            <span class="text-gray-500">{{ $afspraak->kapper->salon_naam }}</span>
            <span class="text-gray-500">{{ $afspraak->dienst->naam }}</span>
            <span class="px-2 py-0.5 rounded text-xs
                {{ $afspraak->status === 'voltooid' ? 'bg-green-100 text-green-800' : '' }}
                {{ $afspraak->status === 'geannuleerd' ? 'bg-gray-100 text-gray-600' : '' }}
                {{ $afspraak->status === 'no_show' ? 'bg-red-100 text-red-800' : '' }}">
                {{ ucfirst(str_replace('_', ' ', $afspraak->status)) }}
            </span>
        </div>
        @empty
        <p class="text-gray-500">Geen eerdere afspraken.</p>
        @endforelse
    </div>
</div>
```

- [ ] **Stap 7: Maak ProfielBeheer component**

```bash
php artisan make:livewire Kapper/ProfielBeheer
```

Inhoud `app/Http/Livewire/Kapper/ProfielBeheer.php`:

```php
<?php

namespace App\Http\Livewire\Kapper;

use Livewire\Component;

class ProfielBeheer extends Component
{
    public string $salon_naam = '';
    public string $adres = '';
    public string $stad = '';
    public string $telefoon = '';
    public string $bio = '';

    public function mount(): void
    {
        $kapper = auth()->user()->kapper;
        $this->salon_naam = $kapper->salon_naam;
        $this->adres = $kapper->adres ?? '';
        $this->stad = $kapper->stad;
        $this->telefoon = $kapper->telefoon ?? '';
        $this->bio = $kapper->bio ?? '';
    }

    protected function rules(): array
    {
        return [
            'salon_naam' => 'required|string|max:255',
            'adres' => 'nullable|string|max:255',
            'stad' => 'required|string|max:255',
            'telefoon' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ];
    }

    public function opslaan(): void
    {
        $this->validate();
        auth()->user()->kapper->update([
            'salon_naam' => $this->salon_naam,
            'adres' => $this->adres ?: null,
            'stad' => $this->stad,
            'telefoon' => $this->telefoon ?: null,
            'bio' => $this->bio ?: null,
        ]);
        session()->flash('message', 'Profiel opgeslagen.');
    }

    public function render()
    {
        return view('livewire.kapper.profiel-beheer')->layout('layouts.kapper');
    }
}
```

Inhoud `resources/views/livewire/kapper/profiel-beheer.blade.php`:

```blade
<div>
    <h2 class="text-xl font-bold mb-4">Mijn profiel</h2>
    @if(session('message'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('message') }}</div>
    @endif
    <form wire:submit="opslaan" class="bg-white p-6 rounded shadow space-y-4 max-w-lg">
        <div>
            <label class="block text-sm font-medium">Saloonnaam</label>
            <input wire:model="salon_naam" type="text" class="mt-1 block w-full rounded border-gray-300">
            @error('salon_naam') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Adres</label>
            <input wire:model="adres" type="text" class="mt-1 block w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Stad</label>
            <input wire:model="stad" type="text" class="mt-1 block w-full rounded border-gray-300">
            @error('stad') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium">Telefoonnummer</label>
            <input wire:model="telefoon" type="text" class="mt-1 block w-full rounded border-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium">Bio</label>
            <textarea wire:model="bio" rows="4" class="mt-1 block w-full rounded border-gray-300"></textarea>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Opslaan</button>
    </form>
</div>
```

- [ ] **Stap 8: Maak AdminSeeder + KappersOverzicht**

```bash
php artisan make:seeder AdminSeeder
php artisan make:livewire Admin/KappersOverzicht
```

Inhoud `database/seeders/AdminSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@kapperplatform.nl'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );
    }
}
```

Inhoud `app/Http/Livewire/Admin/KappersOverzicht.php`:

```php
<?php

namespace App\Http\Livewire\Admin;

use App\Models\Kapper;
use Livewire\Component;

class KappersOverzicht extends Component
{
    public function activeer(int $id): void { Kapper::find($id)->update(['actief' => true]); }
    public function deactiveer(int $id): void { Kapper::find($id)->update(['actief' => false]); }

    public function render()
    {
        return view('livewire.admin.kappers-overzicht', [
            'kappers' => Kapper::with('user')->orderByDesc('created_at')->get(),
        ])->layout('layouts.admin');
    }
}
```

Inhoud `resources/views/livewire/admin/kappers-overzicht.blade.php`:

```blade
<div>
    <h2 class="text-xl font-bold mb-4">Kappers</h2>
    <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Salon</th>
                    <th class="px-4 py-2 text-left">Stad</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Abonnement</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($kappers as $kapper)
                <tr class="border-t">
                    <td class="px-4 py-2">
                        <p class="font-medium">{{ $kapper->salon_naam }}</p>
                        <p class="text-gray-500 text-xs">{{ $kapper->user->email }}</p>
                    </td>
                    <td class="px-4 py-2">{{ $kapper->stad }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-0.5 rounded text-xs {{ $kapper->actief ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $kapper->actief ? 'Actief' : 'Inactief' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $kapper->abonnement_status }}</td>
                    <td class="px-4 py-2">
                        @if($kapper->actief)
                        <button wire:click="deactiveer({{ $kapper->id }})" class="text-red-600 hover:underline text-xs">Deactiveer</button>
                        @else
                        <button wire:click="activeer({{ $kapper->id }})" class="text-green-600 hover:underline text-xs">Activeer</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
```

- [ ] **Stap 9: Vul `routes/web.php` volledig in**

```php
<?php

use App\Http\Livewire\Admin\KappersOverzicht;
use App\Http\Livewire\Kapper\AgendaOverzicht;
use App\Http\Livewire\Kapper\BeschikbaarheidBeheer;
use App\Http\Livewire\Kapper\DienstenBeheer;
use App\Http\Livewire\Kapper\ProfielBeheer;
use App\Http\Livewire\Kapper\Registratie as KapperRegistratie;
use App\Http\Livewire\Klant\BoekingWizard;
use App\Http\Livewire\Klant\KapperZoeken;
use App\Http\Livewire\Klant\MijnAfspraken;
use Illuminate\Support\Facades\Route;

// Publiek
Route::get('/', KapperZoeken::class)->name('home');
Route::get('/kapper/registreer', KapperRegistratie::class)->name('kapper.registreer');
Route::get('/kapper/{slug}', function ($slug) {
    $kapper = \App\Models\Kapper::where('slug', $slug)
        ->where('actief', true)
        ->where('abonnement_status', 'actief')
        ->with('diensten')
        ->firstOrFail();
    return view('pages.kapper-profiel', compact('kapper'));
})->name('kapper.profiel');

// Kapper dashboard
Route::middleware(['auth'])->prefix('kapper')->name('kapper.')->group(function () {
    Route::get('/dashboard', AgendaOverzicht::class)->name('dashboard');
    Route::get('/diensten', DienstenBeheer::class)->name('diensten');
    Route::get('/beschikbaarheid', BeschikbaarheidBeheer::class)->name('beschikbaarheid');
    Route::get('/profiel', ProfielBeheer::class)->name('profiel-beheer');
});

// Klant
Route::middleware(['auth'])->group(function () {
    Route::get('/mijn-afspraken', MijnAfspraken::class)->name('klant.afspraken');
    Route::get('/boeken/{kapperSlug}/{dienstId}', BoekingWizard::class)->name('boeken');
});

// Admin
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/kappers', KappersOverzicht::class)->name('kappers');
});
```

- [ ] **Stap 10: Draai seeder + alle tests**

```bash
php artisan db:seed --class=AdminSeeder
php artisan test
```

Verwacht: alle tests PASS.

- [ ] **Stap 11: Commit**

```bash
git add -A
git commit -m "feat: layouts, dashboards, admin en alle routes"
```

---

## Plan 1 voltooid ✅

Na Task 10 heb je:
- ✅ Werkend Laravel project op `c:\Users\jerma\kapper-systeem`
- ✅ Auth + 3 rollen (admin/kapper/klant)
- ✅ Kapper registratie + profielbeheer
- ✅ Diensten CRUD
- ✅ Beschikbaarheid + sluitingsdagen
- ✅ Publieke zoekpagina + profielpagina
- ✅ Boekingswizard (zonder betaling)
- ✅ Kapper agenda dashboard
- ✅ Klant afsprakenoverzicht
- ✅ Admin kappers beheer

**Volgende plannen:**
- **Plan 2:** Stripe — kapper abonnement, online betaling klant, no-show pre-auth
- **Plan 3:** Notificaties (email + SMS) + PWA + deploy naar Hostinger
