<?php

namespace App\Livewire\Klant;

use App\Mail\AfspraakBevestigingMail;
use App\Models\Afspraak;
use App\Models\Dienst;
use App\Models\Kapper;
use App\Models\Kortingscode;
use App\Models\Wachtlijst;
use App\Notifications\NieuweAfspraakNotificatie;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class BoekingWizard extends Component
{
    public Kapper $kapper;
    public Dienst $dienst;
    public string $gekozenDatum    = '';
    public string $gekozenTijdslot = '';
    public string $betaalmethode   = 'in_zaak';

    public string $kortingscodeInput    = '';
    public ?int   $toegepasdeCodeId     = null;
    public int    $kortingBedrag        = 0;
    public string $kortingLabel         = '';
    public string $kortingFout          = '';

    // Wachtlijst
    public bool   $toonWachtlijstForm   = false;
    public string $wachtlijstNaam       = '';
    public string $wachtlijstEmail      = '';
    public string $wachtlijstTelefoon   = '';
    public bool   $wachtlijstVerstuurd  = false;
    public string $wachtlijstFout       = '';

    public function mount(string $kapperSlug, int $dienstId): void
    {
        $this->kapper = Kapper::where('slug', $kapperSlug)->where('actief', true)->firstOrFail();
        $this->dienst = Dienst::where('id', $dienstId)->where('kapper_id', $this->kapper->id)->firstOrFail();
        $this->gekozenDatum = Carbon::now()->addDay()->toDateString();

        if (auth()->check()) {
            $this->wachtlijstNaam  = auth()->user()->name;
            $this->wachtlijstEmail = auth()->user()->email;
        }
    }

    public function wachtlijstAanmelden(): void
    {
        $this->wachtlijstFout = '';
        $this->validate([
            'wachtlijstNaam'     => 'required|string|min:2',
            'wachtlijstEmail'    => 'required|email',
            'wachtlijstTelefoon' => 'nullable|string|max:20',
        ]);

        $bestaatAl = Wachtlijst::where('kapper_id', $this->kapper->id)
            ->where('email', $this->wachtlijstEmail)
            ->exists();

        if ($bestaatAl) {
            $this->wachtlijstFout = 'Dit e-mailadres staat al op de wachtlijst.';
            return;
        }

        Wachtlijst::create([
            'kapper_id'      => $this->kapper->id,
            'klant_id'       => auth()->id(),
            'naam'           => $this->wachtlijstNaam,
            'email'          => $this->wachtlijstEmail,
            'telefoonnummer' => $this->wachtlijstTelefoon ?: null,
            'gewenste_datum' => $this->gekozenDatum ?: null,
            'status'         => 'wachtend',
        ]);

        $this->wachtlijstVerstuurd = true;
        $this->toonWachtlijstForm  = false;
    }

    public function kortingscodeToepassen(): void
    {
        $this->kortingFout    = '';
        $this->kortingBedrag  = 0;
        $this->toegepasdeCodeId = null;
        $this->kortingLabel   = '';

        $invoer = trim($this->kortingscodeInput);
        if ($invoer === '') {
            $this->kortingFout = 'Voer een code in.';
            return;
        }

        $code = Kortingscode::where('kapper_id', $this->kapper->id)
            ->whereRaw('UPPER(code) = ?', [strtoupper($invoer)])
            ->first();

        if (!$code || !$code->isGeldig()) {
            $this->kortingFout = 'Deze code is ongeldig of niet meer actief.';
            return;
        }

        $this->toegepasdeCodeId = $code->id;
        $this->kortingBedrag    = $code->berekenKorting($this->dienst->prijs);
        $this->kortingLabel     = $code->label;
        $this->kortingscodeInput = strtoupper($invoer);
    }

    public function kortingscodeVerwijderen(): void
    {
        $this->kortingscodeInput = '';
        $this->toegepasdeCodeId  = null;
        $this->kortingBedrag     = 0;
        $this->kortingLabel      = '';
        $this->kortingFout       = '';
    }

    public function bevestig(): void
    {
        $maxDatum = Carbon::now()->addMonths($this->kapper->vooruitboeken_maanden ?? 2)->toDateString();

        $this->validate([
            'gekozenDatum'    => "required|date|after_or_equal:today|before_or_equal:{$maxDatum}",
            'gekozenTijdslot' => 'required|string',
            'betaalmethode'   => 'required|in:online,in_zaak',
        ]);

        $afspraak = DB::transaction(function () {
            // Hercheck slot binnen transaction om double-booking te voorkomen
            $vrijeslots = (new BeschikbaarheidsService())->getVrijeTijdslots(
                $this->kapper, $this->dienst, $this->gekozenDatum
            );
            if (!in_array($this->gekozenTijdslot, $vrijeslots)) {
                $this->addError('gekozenTijdslot', 'Dit tijdslot is zojuist bezet geraakt. Kies een ander tijdstip.');
                return null;
            }

            // Hercheck kortingscode geldigheid + max gebruik binnen transaction
            if ($this->toegepasdeCodeId) {
                $code = Kortingscode::lockForUpdate()->find($this->toegepasdeCodeId);
                if (!$code || !$code->isGeldig()) {
                    $this->kortingBedrag  = 0;
                    $this->toegepasdeCodeId = null;
                    $this->kortingLabel   = '';
                    $this->addError('kortingscodeInput', 'Kortingscode is niet meer geldig.');
                    return null;
                }
            }

            $eind = Carbon::parse("{$this->gekozenDatum} {$this->gekozenTijdslot}")
                ->addMinutes($this->dienst->duur_minuten)
                ->format('H:i');

            $afspraak = Afspraak::create([
                'klant_id'        => auth()->id(),
                'kapper_id'       => $this->kapper->id,
                'dienst_id'       => $this->dienst->id,
                'datum'           => $this->gekozenDatum,
                'start_tijd'      => $this->gekozenTijdslot,
                'eind_tijd'       => $eind,
                'status'          => 'gepland',
                'betaalmethode'   => $this->betaalmethode,
                'kortingscode_id' => $this->toegepasdeCodeId,
                'korting_bedrag'  => $this->kortingBedrag > 0 ? $this->kortingBedrag : null,
            ]);

            if ($this->toegepasdeCodeId) {
                Kortingscode::where('id', $this->toegepasdeCodeId)->increment('gebruik_teller');
            }

            return $afspraak;
        });

        if (!$afspraak) return;

        $afspraak->load(['kapper', 'dienst', 'klant']);

        Mail::to(auth()->user()->email)->send(new AfspraakBevestigingMail($afspraak));
        $afspraak->kapper->user->notify(new NieuweAfspraakNotificatie($afspraak));

        session()->flash('boeking_bevestigd', true);
        $this->redirect(route('klant.afspraken'));
    }

    public function render()
    {
        $service = new BeschikbaarheidsService();
        $vrijeslots = $this->gekozenDatum
            ? $service->getVrijeTijdslots($this->kapper, $this->dienst, $this->gekozenDatum)
            : [];

        $kapperWerktDag = $this->gekozenDatum
            ? $service->heeftBeschikbaarheid($this->kapper, $this->gekozenDatum)
            : false;

        $teBetalenCenten = max(0, $this->dienst->prijs - $this->kortingBedrag);

        $maxDatum = Carbon::now()->addMonths($this->kapper->vooruitboeken_maanden ?? 2)->toDateString();

        return view('livewire.klant.boeking-wizard', compact('vrijeslots', 'kapperWerktDag', 'teBetalenCenten', 'maxDatum'))
            ->layout('layouts.publiek');
    }
}
