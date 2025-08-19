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

            <div class="md:hidden">
                @foreach (collect($meta) as $team)
                    <div class="py-8 space-y-6">
                        <div class="flex items-center justify-between pb-2">
                            <div class="flex flex-col">
                                @foreach (collect($team['users'])->take(2) as $user)
                                    <span class="text-base font-semibold py-1">
                                        {{ $user['name'] ?? $user['name'] }}
                                    </span>
                                @endforeach
                            </div>

                            <div style="max-width: 75px" class="size-12 grid place-items-center overflow-hidden">
                                <img src="{{ asset($team['logo']) }}" alt="{{ $team['name'] }} logo"
                                    class="w-full h-full object-contain" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between pb-2">
                            <button type="button"
                                class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                                <span class="text-2xl leading-none">−</span>
                            </button>

                            <div
                                class="min-w-[88px] px-6 h-12 rounded grid place-items-center border border-gray-200 dark:border-white/3">
                                <span class="text-2xl font-bold">
                                    {{ $team['score'] ?? 0 }}
                                </span>
                            </div>

                            <button type="button"
                                class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                                <span class="text-2xl leading-none">+</span>
                            </button>
                        </div>
                    </div>

                    @if (!$loop->last)
                        <div class="-mx-6 border-t border-gray-200 dark:border-white/3"></div>
                    @endif
                @endforeach
            </div>


            @php
                $teams = collect($meta);
                $left = $teams->get(0);
                $right = $teams->get(1);
            @endphp

            <div class="hidden md:flex flex-row justify-between items-stretch">

                @if ($left)
                    <div 
                    style="padding: 2rem 1.5rem 2rem 0;"
                    class="py-8 space-y-6 w-full md:w-1/2 flex flex-col justify-between">
                        <div class="flex grow items-center justify-between">
                            <div class="flex flex-col">
                                @foreach (collect($left['users'])->take(2) as $user)
                                    <span class="text-base font-semibold py-1">
                                        {{ $user['name'] }}
                                    </span>
                                @endforeach
                            </div>

                            <div style="max-width: 150px" class="size-12 grid place-items-center overflow-hidden">
                                <img src="{{ asset($left['logo']) }}" alt="{{ $left['name'] }} logo"
                                    class="w-full h-full object-contain" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="button"
                                class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                                <span class="text-2xl leading-none">−</span>
                            </button>

                            <div
                                class="min-w-[88px] px-6 h-12 rounded grid place-items-center border border-gray-200 dark:border-white/3">
                                <span class="text-2xl font-bold">
                                    {{ $left['score'] ?? 0 }}
                                </span>
                            </div>

                            <button type="button"
                                class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                                <span class="text-2xl leading-none">+</span>
                            </button>
                        </div>
                    </div>
                @endif


                <div class="hidden md:block w-px bg-gray-200 dark:bg-white/3 self-stretch"></div>


                @if ($right)
                    <div style="padding: 2rem 0 2rem 1.5rem;"
                    class="py-8 space-y-6 w-full md:w-1/2 flex flex-col justify-between">
                        <div class="flex grow items-center justify-between">
                            <div class="flex flex-col">
                                @foreach (collect($right['users'])->take(2) as $user)
                                    <span class="text-base font-semibold py-1">
                                        {{ $user['name'] }}
                                    </span>
                                @endforeach
                            </div>

                            <div style="max-width: 150px" class="size-12 grid place-items-center overflow-hidden">
                                <img src="{{ asset($right['logo']) }}" alt="{{ $right['name'] }} logo"
                                    class="w-full h-full object-contain" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="button"
                                class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                                <span class="text-2xl leading-none">−</span>
                            </button>

                            <div
                                class="min-w-[88px] px-6 h-12 rounded grid place-items-center border border-gray-200 dark:border-white/3">
                                <span class="text-2xl font-bold">
                                    {{ $right['score'] ?? 0 }}
                                </span>
                            </div>

                            <button type="button"
                                class="size-12 px-2 rounded-full border border-gray-200 dark:border-white/3 grid place-items-center">
                                <span class="text-2xl leading-none">+</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="-mx-6 border-t border-gray-200 dark:border-white/3"></div>

            {{-- Bottom: navigation buttons --}}
            <div class="py-4">
                <div class="pt-6 pb-2 flex items-center justify-between gap-6">
                    <button type="button"
                        class="flex-1 h-14 rounded-lg p-2 border border-gray-300 dark:border-white/3 inline-flex items-center justify-center gap-2">
                        <span class="text-sm">←</span>
                        <span class="text-sm font-semibold tracking-wide">Prev Hole</span>
                    </button>

                    <button type="button"
                        class="flex-1 h-14 rounded-lg p-2 border border-gray-300 dark:border-white/3 inline-flex items-center justify-center gap-2">
                        <span class="text-sm font-semibold tracking-wide">Next Hole</span>
                        <span class="text-sm">→</span>
                    </button>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
