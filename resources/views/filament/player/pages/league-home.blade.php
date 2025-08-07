<x-filament-panels::page>
    <h1 class="text-xl font-bold mb-4">Welcome to the League</h1>

    <div class="grid grid-cols-2 gap-4">
        @foreach ($teams as $label => $team)
            <x-filament::card>
                <p class="text-2xl font-bold">{{ $team?->name ?? 'N/A' }}</p>
            </x-filament::card>
        @endforeach
    </div>
</x-filament-panels::page>
