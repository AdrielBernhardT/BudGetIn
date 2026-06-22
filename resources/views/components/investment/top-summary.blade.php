@props(['datas'])

<div class="grid grid-cols-1">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex flex-col lg:flex-row border-gray-400">
            <div
                class="w-full lg:basis-[22%] flex flex-col md:flex-row lg:flex-col gap-6 border-b lg:border-r lg:border-b-0 pb-5 lg:pb-0 lg:pr-5">
                <div class="flex-1 flex flex-col gap-2">
                    <h3 class="text-gray-800 dark:text-white/90 text-theme-sm md:text-md font-normal">
                        Total Target
                    </h3>
                    <span class="text-gray-800 dark:text-white/90 font-semibold text-xl md:text-2xl">
                        IDR {{ number_format($datas['summary']['total_target'], 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex-1 flex flex-col gap-2">
                    <h3 class="text-gray-800 dark:text-white/90 font-normal text-theme-sm md:text-md">
                        Total Investment
                    </h3>
                    <span class="text-gray-800 dark:text-white/90 font-semibold text-lg md:text-xl">
                        IDR {{ number_format($datas['summary']['total_investment'], 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex-1 flex flex-col gap-2">
                    <h3 class="text-gray-800 dark:text-white/90 font-normal text-theme-sm md:text-md">
                        Remaining Target
                    </h3>
                    <span class="text-gray-800 dark:text-white/90 font-semibold text-lg md:text-xl">
                        IDR {{ number_format($datas['summary']['remaining_target'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="flex flex-col pt-5 lg:pt-0 pb-5 lg:pb-0">
                <div id="targetPieChart" class="flex" data-value="{{ $datas['summary']['percentage'] }}"></div>
                <div class="flex flex-col justify-center items-center">
                    <span class="text-sm font-medium text-gray-800 dark:text-white/90">IDR
                        {{ number_format($datas['summary']['total_investment'], 0, ',', '.') }} / IDR
                        {{ number_format($datas['summary']['total_target'], 0, ',', '.') }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Total Target</span>
                </div>
            </div>
            <div class="flex-1 flex flex-col border-t lg:border-t-0 lg:border-l pt-5 lg:pt-0 lg:pl-5 gap-6 overflow-x-auto custom-scrollbar">
                <h3 class="text-gray-800 dark:text-white/90 font-semibold text-lg">Progress Target</h3>
                <div class="overflow-x-auto custom-scrollbar pb-2">
                    <div class="flex flex-col gap-4 lg:gap-5">
                        @foreach ($datas['targets'] as $target)
                            <div class="flex items-center gap-3 lg:gap-10">
                                <div class="flex items-center gap-2 w-60 shrink-0">
                                    <i data-lucide="{{ $target->icon }}" class="w-4 h-4 shrink-0 text-gray-900 dark:text-white"></i>
                                    <div class="text-theme-sm whitespace-nowrap text-gray-800 dark:text-white/90">
                                        {{ $target->title }}</div>
                                    @if ($target->target_date)
                                        @php
                                            $daysLeft = $target->days_left;
                                            $badgeClass = $daysLeft < 0
                                                ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400'
                                                : ($daysLeft <= 7
                                                    ? 'bg-orange-50 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400'
                                                    : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400');
                                            $badgeLabel = $daysLeft < 0 ? 'Overdue' : ($daysLeft . 'd left');
                                        @endphp
                                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[11px] font-medium whitespace-nowrap {{ $badgeClass }}">
                                            {{ $badgeLabel }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex flex-1 items-center gap-4 min-w-0">
                                    <div
                                        class="relative flex-1 min-w-[100px] h-2 rounded-sm bg-gray-200 dark:bg-gray-800">
                                        <div class="absolute left-0 top-0 flex h-full items-center justify-center rounded-sm bg-main"
                                            style="width: {{ $target->percentage }}%"></div>
                                    </div>
                                    <p
                                        class="w-[220px] shrink-0 text-theme-sm font-medium text-gray-800 dark:text-white/90 whitespace-nowrap">
                                        IDR {{ number_format($target->total_current, 0, ',', '.') }} / IDR
                                        {{ number_format($target->target_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
