@props(['datas'])

<div class="flex flex-col xl:flex-row gap-6 items-start">
    <div
        class="flex w-full lg:basis-[30%] rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex flex-col gap-8 w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Investment Allocation
            </h3>

            <div class="flex justify-center items-center">
                <div id="allocationChart" class="w-full" data-chart='@json($datas['allocation_chart'])'></div>
            </div>
        </div>
    </div>
    <div
        class="flex lg:flex-1 w-full min-w-0 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex flex-col gap-8 w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Investment List
            </h3>

            @foreach ($datas['targets'] as $target)
            <div x-data="{ open: false }">
                <div @click="open = !open" class="flex justify-between items-center cursor-pointer">
                    <div class="flex items-start gap-4">
                        <i data-lucide="{{ $target->icon }}" class="w-4 h-4 mt-1 shrink-0 text-gray-900 dark:text-white"></i>
                        <div class="flex flex-col gap-2">
                            <div class="text-md font-semibold text-gray-800 dark:text-white/90">
                                {{ $target->title }}
                            </div>
                            <div class="text-theme-xs text-gray-800 dark:text-white/90">
                                IDR {{ number_format($target->target_amount, 0, ',', '.') }}
                            </div>
                            <div class="text-theme-xs text-gray-500 dark:text-gray-400">
                                {{ count($target->items) }} Investments
                            </div>
                        </div>
                    </div>

                    <i data-lucide="chevron-down" :class="open ? 'rotate-180' : ''"
                        class="transition-transform duration-300">
                    </i>
                </div>

                <div x-show="open" x-transition class="mt-4">
                    <x-investment.allocation.table :target="$target" />
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
