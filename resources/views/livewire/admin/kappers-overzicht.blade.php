<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-semibold text-gray-800 dark:text-neutral-100">Kappers</h1>
            <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">Overzicht van alle geregistreerde kappers</p>
        </div>
        <a href="{{ route('kapper.registreer') }}"
           class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Kapper registreren
        </a>
    </div>

    {{-- Tabel --}}
    <div class="bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100 dark:border-neutral-700">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Salon</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Stad</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 dark:text-neutral-500 uppercase tracking-wide">Abonnement</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-neutral-700">
                @forelse($kappers as $kapper)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-neutral-700/20">
                    <td class="px-6 py-3.5">
                        <p class="font-medium text-gray-800 dark:text-neutral-100">{{ str($kapper->salon_naam)->title() }}</p>
                        <p class="text-xs text-gray-400 dark:text-neutral-500 mt-0.5">{{ strtolower($kapper->user->email) }}</p>
                    </td>
                    <td class="px-6 py-3.5 text-gray-500 dark:text-neutral-400">{{ str($kapper->stad)->title() }}</td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $kapper->actief
                                ? 'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400'
                                : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $kapper->actief ? 'bg-green-500' : 'bg-gray-400 dark:bg-neutral-500' }}"></span>
                            {{ $kapper->actief ? 'Actief' : 'Inactief' }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $kapper->abonnement_status === 'actief'
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                : 'bg-gray-100 text-gray-500 dark:bg-neutral-700 dark:text-neutral-400' }}">
                            {{ $kapper->abonnement_status }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-right">
                        @if($kapper->actief)
                        <button wire:click="deactiveer({{ $kapper->id }})"
                                class="text-xs font-medium text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                            Deactiveer
                        </button>
                        @else
                        <button wire:click="activeer({{ $kapper->id }})"
                                class="text-xs font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                            Activeer
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <p class="text-sm text-gray-400 dark:text-neutral-500">Geen kappers geregistreerd</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
