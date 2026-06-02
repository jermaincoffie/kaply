<div>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">Kappers</h2>
        <a href="{{ route('kapper.registreer') }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">
            + Kapper registreren
        </a>
    </div>
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
                @forelse($kappers as $kapper)
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
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">Geen kappers geregistreerd.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
