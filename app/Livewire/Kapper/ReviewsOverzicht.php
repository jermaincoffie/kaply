<?php

namespace App\Livewire\Kapper;

use App\Models\Review;
use Livewire\Component;

class ReviewsOverzicht extends Component
{
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
