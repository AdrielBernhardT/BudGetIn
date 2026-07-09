@props(['statistics', 'isStatistics'])

<div
    x-data="statisticsChart(@js($statistics))"
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6"
>
    <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
        <div class="w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Statistics
            </h3>
        </div>

        @if ($isStatistics)
            <div class="flex items-start w-full gap-3 sm:justify-end">
                <div class="inline-flex w-fit items-center gap-0.5 rounded-lg bg-gray-100 p-0.5 dark:bg-gray-900">
                    @php
                        $options = [
                            ['value' => 'overview', 'label' => 'Overview'],
                            ['value' => 'income', 'label' => 'Income'],
                            ['value' => 'expense', 'label' => 'Expense'],
                        ];
                    @endphp

                    @foreach ($options as $option)
                        <button
                            type="button"
                            @click="changeChart('{{ $option['value'] }}')"
                            :class="selected === '{{ $option['value'] }}'
                                ? 'shadow-theme-xs text-gray-900 dark:text-white bg-white dark:bg-gray-800'
                                : 'text-gray-500 dark:text-gray-400'"
                            class="px-3 py-2 font-medium rounded-md text-theme-sm hover:text-gray-900 dark:hover:text-white"
                        >
                            {{ $option['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    @if ($isStatistics)
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <div x-ref="chart" class="-ml-4 pl-2 xl:min-w-full"></div>
        </div>
    @else
        <div class="flex flex-col justify-center items-center py-8 text-center">
            <h3 class="text-gray-500">
                No statistics yet
            </h3>
        </div>
    @endif
</div>