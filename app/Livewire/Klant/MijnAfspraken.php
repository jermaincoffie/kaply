<?php

namespace App\Livewire\Klant;

use App\Mail\AfspraakGeannuleerdMail;
use App\Models\Afspraak;
use App\Models\Review;
use App\Notifications\AfspraakGeannuleerdNotificatie;
use App\Services\BeschikbaarheidsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class MijnAfspraken extends Component
{
    public ?int $reviewAfspraakId = null;
    public int $reviewRating = 0;
    public string $reviewTekst = '';

    // Verzetten
    public ?int $verzetAfspraakId = null;
    public string $verzetDatum = '';
    public array $verzetTijdsloten = [];
    public string $verzetTijd = '';
    public bool $verzetGeslaagd = false;

    public function annuleer(int $id): void
    {
        $afspraak = Afspraak::where('id', $id)
            ->where('klant_id', auth()->id())
            ->where('status', 'gepland')
            ->with(['kapper', 'dienst', 'klant'])
            ->first();

        if (!$afspraak) return;

        $afspraak->update(['status' => 'geannuleerd']);

        Mail::to($afspraak->klant->email)->send(new AfspraakGeannuleerdMail($afspraak));
        $afspraak->kapper->user->notify(new AfspraakGeannuleerdNotificatie($afspraak));
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
            ->with(['dienst', 'kapper'])
            ->first();

        if (!$afspraak) return;

        $eind = Carbon::parse($this->verzetDatum . ' ' . $this->verzetTijd)
            ->addMinutes($afspraak->dienst->duur_minuten)
            ->format('H:i');

        $afspraak->update([
            'datum'      => $this->verzetDatum,
            'start_tijd' => $this->verzetTijd,
            'eind_tijd'  => $eind,
        ]);

        $this->verzetGeslaagd = true;
    }

    public function sluitVerzetten(): void
    {
        $this->verzetAfspraakId = null;
        $this->verzetGeslaagd = false;
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
            ->where('status', 'voltooid')
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

    public function render()
    {
        return view('livewire.klant.mijn-afspraken', [
            'aankomend' => Afspraak::where('klant_id', auth()->id())
                ->where('datum', '>=', today())
                ->where('status', 'gepland')
                ->with(['kapper', 'dienst'])
                ->orderBy('datum')->orderBy('start_tijd')
                ->get(),
            'geschiedenis' => Afspraak::where('klant_id', auth()->id())
                ->where(fn($q) => $q->where('datum', '<', today())->orWhereNotIn('status', ['gepland']))
                ->with(['kapper', 'dienst', 'review'])
                ->orderByDesc('datum')
                ->limit(20)
                ->get(),
        ])->layout('layouts.klant');
    }
}
