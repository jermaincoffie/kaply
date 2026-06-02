@extends('layouts.publiek')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="{{ route('home') }}" class="text-indigo-600 hover:underline text-sm mb-4 inline-block">← Terug naar zoeken</a>
    <h1 class="text-3xl font-bold">{{ $kapper->salon_naam }}</h1>
    <p class="text-gray-500">{{ $kapper->adres }}{{ $kapper->adres ? ', ' : '' }}{{ $kapper->stad }}</p>
    @if($kapper->telefoon)
    <p class="text-gray-600 mt-1">📞 {{ $kapper->telefoon }}</p>
    @endif
    @if($kapper->bio)
    <p class="mt-4 text-gray-700">{{ $kapper->bio }}</p>
    @endif

    <h2 class="text-xl font-bold mt-8 mb-4">Diensten</h2>
    @if($kapper->diensten->isEmpty())
    <p class="text-gray-500">Geen diensten beschikbaar.</p>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($kapper->diensten as $dienst)
        <div class="bg-white rounded shadow p-4 flex justify-between items-center">
            <div>
                <p class="font-semibold">{{ $dienst->naam }}</p>
                <p class="text-gray-500 text-sm">{{ $dienst->duur_minuten }} minuten</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-indigo-600">€ {{ $dienst->prijs_in_euros }}</p>
                @auth
                <a href="{{ route('boeken', ['kapperSlug' => $kapper->slug, 'dienstId' => $dienst->id]) }}"
                   class="bg-indigo-600 text-white text-sm px-3 py-1 rounded hover:bg-indigo-700 mt-1 inline-block">
                    Boek
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="bg-gray-600 text-white text-sm px-3 py-1 rounded hover:bg-gray-700 mt-1 inline-block">
                    Inloggen om te boeken
                </a>
                @endauth
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
