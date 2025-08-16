<x-filament-panels::page>
    <div class="mx-auto w-full px-6">
        <x-filament::section class="rounded-2xl border border-gray-200 dark:border-white/3 overflow-hidden">

            {{-- TOP: Hole + Match status --}}
            <div class="py-4">
                <div class="pt-2 pb-6 flex items-center justify-between">
                    <div class="flex flex-col">
                        <span class="text-xl font-bold leading-tight">Hole 1</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1">
                        <span class="text-xs font-semibold tracking-wide">ALL SQUARE</span>
                    </div>
                </div>
            </div>

            <div class="-mx-6 border-t border-gray-200 dark:border-white/3"></div>

            <div class="">
                <div class="py-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-base font-semibold">Player 1</span>
                            <span class="text-base font-semibold">Player 2</span>
                        </div>
                        <div
                            class="size-12 rounded-full bg-gray-100 dark:bg-white/10 grid place-items-center text-xs text-gray-500">
                            LOGO
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button"
                            class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                            <span class="text-2xl leading-none">−</span>
                        </button>
                        <div
                            class="min-w-[88px] px-6 h-12 rounded grid place-items-center border border-gray-200 dark:border-white/3">
                            <span class="text-2xl font-bold">3</span>
                        </div>
                        <button type="button"
                            class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                            <span class="text-2xl leading-none">+</span>
                        </button>
                    </div>
                </div>

                <div class="-mx-6 border-t border-gray-200 dark:border-white/3"></div>

                <div class="py-8 space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-base font-semibold">Player 3</span>
                            <span class="text-base font-semibold">Player 4</span>
                        </div>
                        <div
                            class="size-12 rounded-full bg-gray-100 dark:bg-white/10 grid place-items-center text-xs text-gray-500">
                            LOGO
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="button"
                            class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                            <span class="text-2xl leading-none">−</span>
                        </button>
                        <div
                            class="min-w-[88px] px-6 h-12 rounded grid place-items-center border border-gray-200 dark:border-white/3">
                            <span class="text-2xl font-bold">3</span>
                        </div>
                        <button type="button"
                            class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                            <span class="text-2xl leading-none">+</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="-mx-6 border-t border-gray-200 dark:border-white/3"></div>

            {{-- Bottom: navigation buttons --}}
            <div class="py-4">
                <div class="pt-6 pb-2 flex items-center justify-between gap-8">
                    <button type="button"
                        class="flex-1 h-14 rounded-lg p-2 border border-gray-300 dark:border-white/3 inline-flex items-center justify-center gap-2">
                        <span class="text-lg">←</span>
                        <span class="text-sm font-semibold tracking-wide">Prev Hole</span>
                    </button>

                    <button type="button"
                        class="flex-1 h-14 rounded-lg p-2 border border-gray-300 dark:border-white/3 inline-flex items-center justify-center gap-2">
                        <span class="text-sm font-semibold tracking-wide">Next Hole</span>
                        <span class="text-lg">→</span>
                    </button>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
