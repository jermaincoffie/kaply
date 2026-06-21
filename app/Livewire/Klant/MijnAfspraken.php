<?php

namespace App\Livewire\Klant;

use App\Mail\AfspraakGeannuleerdMail;
use App\Mail\AfspraakVerzetKapperMail;
use App\Mail\WachtlijstNotificatieMail;
use App\Models\Afspraak;
use App\Models\Review;
use App\Models\Wachtlijst;
use App\Notifications\AfspraakGeannuleerdNotificatie;
use App\Notifications\AfspraakVerzetNotificatie;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class MijnAfspraken extends Component
{
    public ?int $reviewAfspraakId = null;
    public int $reviewRating = 0;
    public string $reviewTekst = '';
    public string $annuleerFout = '';
    public ?int $annuleringFeeAfspraakId = null;
    public int $annuleringFeeKosten = 0;

    // Verzetten
    public ?int $verzetAfspraakId = null;
    public string $verzetDatum = '';
    public array $verzetTijdsloten = [];
    public string $verzetTijd = '';
    public bool $verzetGeslaagd = false;

    public function annuleer(int $id): void
    {
        $this->annuleerFout = '';

        $afspraak = Afspraak::where('id', $id)
            ->where('klant_id', auth()->id())
            ->where('status', 'gepland')
            ->with(['kapper', 'dienst', 'klant'])
            ->first();

        if (!$afspraak) return;

        if ($afspraak->kapper->annulering_uren) {
            $deadline = \Carbon\Carbon::parse($afspraak->datum->format('Y-m-d') . ' ' . $afspraak->start_tijd)
                ->subHours($afspraak->kapper->annulering_uren);

            if (now()->isAfter($deadline)) {
                if ($afspraak->kapper->annulering_kosten > 0) {
                    $this->annuleringFeeAfspraakId = $afspraak->id;
                    $this->annuleringFeeKosten = $afspraak->kapper->annulering_kosten;
                    return;
                }

                $uren = $afspraak->kapper->annulering_uren;
                $this->annuleerFout = $uren >= 24
                    ? 'Annuleren is niet meer mogelijk. Dit salon hanteert een annuleringstermijn van ' . ($uren / 24) . ($uren / 24 > 1 ? ' dagen' : ' dag') . '.'
                    : 'Annuleren is niet meer mogelijk. Dit salon hanteert een annuleringstermijn van ' . $uren . ' uur.';
                return;
            }
        }

        $afspraak->update(['status' => 'geannuleerd']);

        Mail::to($afspraak->klant->email)->send(new AfspraakGeannuleerdMail($afspraak));
        $afspraak->kapper->user->notify(new AfspraakGeannuleerdNotificatie($afspraak));

        // Vandaag geannuleerd → kapper belt zelf, geen mail
        // Toekomstige datum geannuleerd → iedereen op wachtlijst mailen
        if ($afspraak->datum->isAfter(today())) {
            $wachtenden = Wachtlijst::where('kapper_id', $afspraak->kapper_id)
                ->where('status', 'wachtend')
                ->get();

            foreach ($wachtenden as $wachtende) {
                Mail::to($wachtende->email)->send(new WachtlijstNotificatieMail($afspraak->kapper));
                $wachtende->update(['status' => 'genotificeerd']);
            }
        }
    }

    public function sluitAnnuleringFee(): void
    {
        $this->annuleringFeeAfspraakId = null;
        $this->annuleringFeeKosten = 0;
    }

    public function openVerzetten(int $id): void
    {
        $this->verzetAfspraakId = $id;
        $this->verzetDatum = today()->toDateString();
        $this->verzetTijdsloten = [];
        $this->verzetTijd = '';
        $this->verzetGeslaagd = false;
        $this->laadVerzetTijdsloten();
    }

    public function updatedVerzetDatum(): void
    {
        $this->verzetTijd = '';
        $this->laadVerzetTijdsloten();
    }

    private function laadVerzetTijdsloten(): void
    {
        if (!$this->verzetAfspraakId || !$this->verzetDatum) {
            $this->verzetTijdsloten = [];
            return;
        }

        $afspraak = Afspraak::with(['kapper', 'dienst'])->find($this->verzetAfspraakId);
        if (!$afspraak) return;

        $service = new BeschikbaarheidsService();
        $sluitingsdag = $service->getSluitingsdag($afspraak->kapper, $this->verzetDatum);
        $this->verzetTijdsloten = $sluitingsdag
            ? []
            : $service->getVrijeTijdslots(
                $afspraak->kapper,
                $afspraak->dienst,
                $this->verzetDatum,
                $afspraak->medewerker_id,
                $afspraak->id
            );
    }

    public function bevestigVerzetten(): void
    {
        if (!$this->verzetAfspraakId || !$this->verzetDatum || !$this->verzetTijd) return;

        $afspraak = Afspraak::where('id', $this->verzetAfspraakId)
            ->where('klant_id', auth()->id())
            ->where('status', 'gepland')
            ->with(['dienst', 'kapper', 'klant'])
            ->first();

        if (!$afspraak) return;

        $oudeDatum = $afspraak->datum->format('Y-m-d');
        $oudeTijd  = $afspraak->start_tijd;

        $eind = Carbon::parse($this->verzetDatum . ' ' . $this->verzetTijd)
            ->addMinutes($afspraak->dienst->duur_minuten)
            ->format('H:i');

        $afspraak->update([
            'datum'      => $this->verzetDatum,
            'start_tijd' => $this->verzetTijd,
            'eind_tijd'  => $eind,
        ]);

        $afspraak->refresh();

        Mail::to($afspraak->kapper->user->email)
            ->send(new AfspraakVerzetKapperMail($afspraak, $oudeDatum, $oudeTijd));

        $afspraak->kapper->user->notify(
            new AfspraakVerzetNotificatie($afspraak, $oudeDatum, $oudeTijd)
        );

        $this->verzetGeslaagd = true;
    }

    public function sluitVerzetten(): void
    {
        $this->verzetAfspraakId = null;
        $this->verzetGeslaagd = false;
    }

    public function toggleFavoriet(int $kapperId): void
    {
        $user = auth()->user();
        if ($user->favorieteKappers()->where('kapper_id', $kapperId)->exists()) {
            $user->favorieteKappers()->detach($kapperId);
        } else {
            $user->favorieteKappers()->attach($kapperId);
        }
    }

    public function openReview(int $afspraakId): void
    {
        $this->reviewAfspraakId = $afspraakId;
        $this->reviewRating = 0;
        $this->reviewTekst = '';
    }

    public function submitReview(): void
    {
        $this->validate([
            'reviewRating' => 'required|integer|min:1|max:5',
            'reviewTekst'  => 'nullable|string|max:500',
        ]);

        $afspraak = Afspraak::where('id', $this->reviewAfspraakId)
            ->where('klant_id', auth()->id())
            ->where(fn($q) => $q
                ->where('status', 'voltooid')
                ->orWhere(fn($q2) => $q2->where('status', 'gepland')->where('datum', '<', today()))
            )
            ->firstOrFail();

        Review::create([
            'kapper_id'   => $afspraak->kapper_id,
            'klant_id'    => auth()->id(),
            'afspraak_id' => $afspraak->id,
            'rating'      => $this->reviewRating,
            'tekst'       => $this->reviewTekst ?: null,
            'zichtbaar'   => true,
        ]);

        $this->reviewAfspraakId = null;
    }

    public function wachtlijstAfmelden(int $id): void
    {
        Wachtlijst::where('id', $id)->where('klant_id', auth()->id())->delete();
    }

    public function render()
    {
        return view('livewire.klant.mijn-afspraken', [
            'aankomend' => Afspraak::where('klant_id', auth()->id())
                ->where('status', 'gepland')
                ->where(fn($q) => $q
                    ->where('datum', '>', today())
                    ->orWhere(fn($q) => $q
                        ->where('datum', today())
                        ->where('start_tijd', '>', now()->format('H:i'))
                    )
                )
                ->with(['kapper', 'dienst'])
                ->orderBy('datum')->orderBy('start_tijd')
                ->get(),
            'wachtlijst' => Wachtlijst::where('klant_id', auth()->id())
                ->where('status', 'wachtend')
                ->where(fn($q) => $q
                    ->whereNull('gewenste_datum')
                    ->orWhere('gewenste_datum', '>=', today())
                )
                ->with('kapper')
                ->orderByDesc('created_at')
                ->get(),
            'favorieteKappers'  => auth()->user()->favorieteKappers()->get(),
            'favorietKapperIds' => auth()->user()->favorieteKappers()->pluck('kappers.id'),
            'geschiedenis' => Afspraak::where('klant_id', auth()->id())
                ->where(fn($q) => $q
                    ->where('datum', '<', today())
                    ->orWhere(fn($q) => $q->where('datum', today())->where('start_tijd', '<=', now()->format('H:i'))->where('status', 'gepland'))
                    ->orWhereNotIn('status', ['gepland'])
                )
                ->with(['kapper', 'dienst', 'review'])
                ->orderByDesc('datum')
                ->limit(20)
                ->get(),
        ])->layout('layouts.klant');
    }
}
