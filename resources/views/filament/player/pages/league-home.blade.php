<x-filament-panels::page>
    <h1 class="text-xl font-bold mb-4">Welcome DipShits</h1>
    @foreach ($leagueData as $label => $data)
        <div x-data="{ open: false }" class="border rounded-xl p-4 mb-4 shadow">
            <button @click="open = !open" class="w-full text-left text-xl font-bold">
                {{ $data['team']->name }}
            </button>

            <div x-show="open" x-transition class="mt-4 space-y-2">
                <div>
                    <span class="font-semibold">Captain:</span>
                    {{ $data['captain']->combined_name }} 
                </div>

                <div>
                    <span class="font-semibold">Players:</span>
                    <ul class="ml-6">
                        @foreach ($data['players'] as $player)
                            <li>{{ $player->combined_name }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endforeach

</x-filament-panels::page>
