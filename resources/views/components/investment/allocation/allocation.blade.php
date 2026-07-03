@props(['datas', 'goals'])

<div class="flex flex-col xl:flex-row gap-6 items-start">
    <div
        class="flex w-full lg:basis-[30%] rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex flex-col gap-8 w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('common.investment_allocation') }}
            </h3>

            @if (collect($datas['targets'])->isEmpty())
                <h5 class="flex justify-center text-gray-500">
                    {{ __('common.no_allocation_available') }}
                </h5>
            @else
                <div class="flex justify-center items-center">
                    <div id="allocationChart" class="w-full" data-chart='@json($datas['allocation_chart'])'></div>
                </div>
            @endif
        </div>
    </div>
    <div
        class="flex lg:flex-1 w-full min-w-0 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex flex-col gap-8 w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('common.investment_list') }}
            </h3>

            @if (collect($datas['targets'])->isEmpty())
                <h5 class="flex justify-center text-gray-500">
                    {{ __('common.no_investments_available') }}
                </h5>
            @else
                @foreach ($datas['targets'] as $target)
                <div x-data="{ expanded: false }">
                    <div @click="expanded = !expanded" class="flex justify-between items-center cursor-pointer">
                        <div class="flex items-start gap-4">
                            <i data-lucide="{{ $target->icon }}" class="w-4 h-4 mt-1 shrink-0 text-gray-900 dark:text-white"></i>
                            <div class="flex flex-col gap-2">
                                <div class="text-md font-semibold text-gray-800 dark:text-white/90">
                                    {{ $target->title }}
                                </div>
                                <div class="text-theme-xs text-gray-800 dark:text-white/90">
                                    {{ __('common.idr') }} {{ number_format($target->target_amount, 0, ',', '.') }}
                                </div>
                                <div class="text-theme-xs text-gray-500 dark:text-gray-400">
                                    {{ count($target->items) }} {{ __('nav.investments') }}
                                </div>
                            </div>
                        </div>

                        <i data-lucide="chevron-down" :class="expanded ? 'rotate-180' : ''"
                            class="transition-transform duration-300">
                        </i>
                    </div>

                    <div x-show="expanded" x-transition class="mt-4">
                        <x-investment.allocation.table :target="$target" />
                    </div>
                </div>
                @endforeach
            @endif

        </div>
    </div>
</div>