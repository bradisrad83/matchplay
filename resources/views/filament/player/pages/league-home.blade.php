<x-filament-panels::page>
    <div class="mx-auto w-full max-w-3xl px-4 py-6">
        <x-filament::section class="rounded-2xl border border-gray-200 dark:border-white/5">
            <div
                class="-mx-6 grid grid-cols-1 divide-y divide-gray-200 dark:divide-white/5
                  md:grid-cols-2 md:divide-y-0 md:divide-x">
                @foreach ($teams as $team)
                    <div class="p-6 flex flex-col items-center gap-3">
                        <h3 class="text-lg font-semibold">{{ $team->name }}</h3>
                        <div style="max-width: 150px"
                            class="grid place-items-center dark:border-white/10 overflow-hidden">
                            <img src="/{{ $team->logo }}" alt="{{ $team->name }} logo" class="object-contain" />
                        </div>
                        <div class="text-4xl font-bold tracking-tight">{{ $team->current_score }}</div>
                    </div>
                @endforeach
            </div>
            <div class="-mx-6 border-t border-gray-200 dark:border-white/5 pt-3 text-center">
                <span class="text-sm font-medium">Race to 18.5 points</span>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
