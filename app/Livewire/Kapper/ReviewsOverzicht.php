<?php

namespace App\Livewire\Kapper;

use App\Models\Review;
use Livewire\Component;

class ReviewsOverzicht extends Component
{
    public ?int $reageerOpId = null;
    public string $reactieTekst = '';

    public function openReagerenForm(int $reviewId): void
    {
        $this->reageerOpId = $reviewId;
        $this->reactieTekst = '';
    }

    public function annuleerReageren(): void
    {
        $this->reageerOpId = null;
        $this->reactieTekst = '';
    }

    public function slaReactieOp(): void
    {
        $this->validate(['reactieTekst' => 'required|string|min:2|max:1000']);

        $kapperId = auth()->user()->kapper->id;

        $review = Review::where('id', $this->reageerOpId)
            ->where('kapper_id', $kapperId)
            ->whereNull('reactie')
            ->firstOrFail();

        $review->update([
            'reactie'    => trim($this->reactieTekst),
            'reactie_op' => now(),
        ]);

        $this->reageerOpId  = null;
        $this->reactieTekst = '';
    }

    public function verwijderReactie(int $reviewId): void
    {
        $kapperId = auth()->user()->kapper->id;

        Review::where('id', $reviewId)
            ->where('kapper_id', $kapperId)
            ->update(['reactie' => null, 'reactie_op' => null]);
    }

    public function render()
    {
        $kapperId = auth()->user()->kapper->id;

        $reviews = Review::where('kapper_id', $kapperId)
            ->with('klant')
            ->orderByDesc('created_at')
            ->get();

        $gemiddeld = $reviews->where('zichtbaar', true)->avg('rating');
        $totaal    = $reviews->count();

        return view('livewire.kapper.reviews-overzicht', compact('reviews', 'gemiddeld', 'totaal'))
            ->layout('layouts.kapper', ['title' => 'Reviews']);
    }
}
