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
