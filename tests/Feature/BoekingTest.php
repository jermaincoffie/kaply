<?php

use App\Models\Afspraak;
use App\Models\Beschikbaarheid;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\User;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Livewire\Livewire;
use App\Livewire\Klant\BoekingWizard;

it('beschikbaarheidsservice geeft vrije tijdslots', function () {
    $kapper = Kapper::factory()->create();
    $dienst = Dienst::factory()->create(['kapper_id' => $kapper->id, 'duur_minuten' => 30]);

    Beschikbaarheid::create([
        'kapper_id' => $kapper->id,
        'dag_van_week' => 0, // maandag
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
