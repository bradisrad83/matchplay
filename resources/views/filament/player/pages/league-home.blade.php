<x-filament-panels::page>
    <div class="mx-auto w-full max-w-3xl px-4 py-6">
        <x-filament::section class="rounded-2xl border border-gray-200 dark:border-white/3">
            <div style="margin-top: -1.5rem;"
            class="-mx-6 flex divide-x divide-gray-200 dark:divide-white/30">
                @foreach ($teams as $team)
                    <div class="w-1/2 p-6 flex flex-col items-center gap-3">
                        <h3 class="text-xs sm:text-sm md:text-lg font-semibold text-center">{{ $team->name }}</h3>

                        <div style="max-width: 150px;"
                        class="flex-grow overflow-hidden">
                            <img src="{{ asset($team->logo) }}" alt="{{ $team->name }} logo"
                                class="w-full h-full object-contain" />
                        </div>

                        <div class="text-xl font-bold tracking-tight">
                            {{ $team->current_score }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="-mx-6 px-4 border-t border-gray-200 dark:border-white/3 pt-4 text-center">
                <span class="text-sm font-medium">Race to 18.5 points</span>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
