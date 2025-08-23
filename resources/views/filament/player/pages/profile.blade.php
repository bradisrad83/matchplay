<x-filament-panels::page>
    <x-filament::page>
        <form wire:submit.prevent="submit" class="space-y-6">
            {{ $this->form }}

            <x-filament::button type="submit">
                Save Changes
            </x-filament::button>
        </form>
    </x-filament::page>

</x-filament-panels::page>
