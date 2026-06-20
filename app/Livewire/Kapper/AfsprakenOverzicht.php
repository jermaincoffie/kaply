<?php

namespace App\Livewire\Kapper;

use App\Mail\NoShowMail;
use App\Models\Afspraak;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class AfsprakenOverzicht extends Component
{
    use WithPagination;

    public string $periode = 'aankomend';
    public string $filterStatus = '';

    // No-show modal
    public ?int $noShowAfspraakId = null;
    public string $noShowOptie = 'waarschuwing'; // waarschuwing|fee
    public string $noShowFeeEuros = '';
    public string $noShowFout = '';

    public function updatingPeriode(): void { $this->resetPage(); }
    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function openNoShowModal(int $id): void
    {
        $this->noShowAfspraakId = $id;
        $this->noShowOptie      = 'waarschuwing';
        $this->noShowFeeEuros   = '';
        $this->noShowFout       = '';
    }

    public function sluitNoShowModal(): void
    {
        $this->noShowAfspraakId = null;
        $this->noShowFout       = '';
    }

    public function bevestigNoShow(): void
    {
        $this->noShowFout = '';

        if (!$this->noShowAfspraakId) return;

        $afspraak = Afspraak::where('id', $this->noShowAfspraakId)
            ->where('kapper_id', auth()->user()->kapper->id)
            ->where('status', 'gepland')
            ->where('datum', '<', today())
            ->with(['klant', 'dienst', 'kapper'])
            ->first();

        if (!$afspraak) {
            $this->noShowAfspraakId = null;
            return;
        }

        $checkoutUrl = null;

        if ($this->noShowOptie === 'fee') {
            $bedrag = (float) str_replace(',', '.', $this->noShowFeeEuros);
            if ($bedrag <= 0) {
                $this->noShowFout = 'Vul een geldig bedrag in.';
                return;
            }
            $feeInCents = (int) round($bedrag * 100);

            Stripe::setApiKey(config('cashier.secret'));

            $params = [
                'line_items' => [[
                    'price_data' => [
                        'currency'     => 'eur',
                        'unit_amount'  => $feeInCents,
                        'product_data' => [
                            'name'        => 'No-show fee – ' . $afspraak->kapper->salon_naam,
                            'description' => 'Gemiste afspraak op ' . $afspraak->datum->format('d M Y') . ' om ' . $afspraak->start_tijd,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode'                 => 'payment',
                'payment_method_types' => ['card', 'ideal'],
                'metadata'             => [
                    'type'        => 'no_show_fee',
                    'afspraak_id' => $afspraak->id,
                ],
                'success_url' => route('home'),
                'cancel_url'  => route('home'),
            ];

            if ($afspraak->klant?->stripe_id) {
                $params['customer'] = $afspraak->klant->stripe_id;
            } elseif ($afspraak->klant) {
                $params['customer_email'] = $afspraak->klant->email;
            }

            $session     = StripeSession::create($params);
            $checkoutUrl = $session->url;
        }

        $afspraak->update(['status' => 'no_show']);

        if ($afspraak->klant) {
            Mail::to($afspraak->klant->email)->send(new NoShowMail($afspraak, $checkoutUrl));
        }

        $this->noShowAfspraakId = null;
    }

    public function render()
    {
        $kapperId = auth()->user()->kapper->id;

        $heeftAfspraken = Afspraak::where('kapper_id', $kapperId)->exists();

        $afspraken = Afspraak::where('kapper_id', $kapperId)
            ->when($this->periode === 'aankomend', fn($q) => $q->where('datum', '>=', today())->where('status', 'gepland'))
            ->when($this->periode === 'verleden',  fn($q) => $q->where(fn($q) => $q->where('datum', '<', today())->orWhereIn('status', ['voltooid','geannuleerd','no_show'])))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->with(['klant', 'dienst'])
            ->orderBy('datum', $this->periode === 'verleden' ? 'desc' : 'asc')
            ->orderBy('start_tijd')
            ->paginate(15);

        return view('livewire.kapper.afspraken-overzicht', compact('afspraken', 'heeftAfspraken'))
            ->layout('layouts.kapper', ['title' => 'Afspraken']);
    }
}
