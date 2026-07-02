@props(['datas'])

<div class="grid grid-cols-1">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex flex-col lg:flex-row border-gray-400">
            <div
                class="w-full lg:basis-[22%] flex flex-col md:flex-row lg:flex-col gap-6 border-b lg:border-r lg:border-b-0 pb-5 lg:pb-0 lg:pr-5">
                <div class="flex-1 flex flex-col gap-2">
                    <h3 class="text-gray-800 dark:text-white/90 text-theme-sm md:text-md font-normal">
                        {{ __('common.to_account')}}
                    </h3>
                    <span class="text-gray-800 dark:text-white/90 font-semibold text-xl md:text-2xl">
                        {{ __('common.idr')}} {{ number_format($datas['summary']['total_target'], 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex-1 flex flex-col gap-2">
                    <h3 class="text-gray-800 dark:text-white/90 font-normal text-theme-sm md:text-md">
                        {{ __('common.total_investment') }}
                    </h3>
                    <span class="text-gray-800 dark:text-white/90 font-semibold text-lg md:text-xl">
                        {{ __('common.idr')}} {{ number_format($datas['summary']['total_investment'], 0, ',', '.') }}
                    </span>
                </div>
                <div class="flex-1 flex flex-col gap-2">
                    <h3 class="text-gray-800 dark:text-white/90 font-normal text-theme-sm md:text-md">
                        {{ __('common.remaining_target') }}
                    </h3>
                    <span class="text-gray-800 dark:text-white/90 font-semibold text-lg md:text-xl">
                        {{ __('common.idr')}} {{ number_format($datas['summary']['remaining_target'], 0, ',', '.') }}
                    </span>
                </div>
            </div>
            <div class="flex flex-col pt-5 lg:pt-0 pb-5 lg:pb-0">
                <div id="targetPieChart" class="flex" data-value="{{ $datas['summary']['percentage'] }}"></div>
                <div class="flex flex-col justify-center items-center">
                    <span class="text-sm font-medium text-gray-800 dark:text-white/90">{{ __('common.idr') }}
                        {{ number_format($datas['summary']['total_investment'], 0, ',', '.') }} / {{ __('common.idr') }}
                        {{ number_format($datas['summary']['total_target'], 0, ',', '.') }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ __('common.total_target') }}</span>
                </div>
            </div>
            <div class="flex-1 flex flex-col border-t lg:border-t-0 lg:border-l pt-5 lg:pt-0 lg:pl-5 gap-6 overflow-x-auto custom-scrollbar">
                <h3 class="text-gray-800 dark:text-white/90 font-semibold text-lg">{{ __('common.progress_target') }}</h3>
                <div class="overflow-x-auto custom-scrollbar pb-2">
                    <div class="flex flex-col gap-4 lg:gap-5">
                        @if (collect($datas['targets'])->isEmpty())
                            <h5 class="flex justify-center text-gray-500">
                                {{ __('common.no_targets_available') }}
                            </h5>
                        @else
                            @foreach ($datas['targets'] as $target)
                                <div class="flex items-center gap-3 lg:gap-10">
                                    <div class="flex items-center gap-2 w-60 shrink-0">
                                        <i data-lucide="{{ $target->icon }}" class="w-4 h-4 shrink-0 text-gray-900 dark:text-white"></i>
                                        <div class="text-theme-sm whitespace-nowrap text-gray-800 dark:text-white/90">
                                            {{ $target->title }}</div>
                                    </div>
                                    <div class="flex flex-1 items-center gap-4 min-w-0">
                                        <div
                                            class="relative flex-1 min-w-[100px] h-2 rounded-sm bg-gray-200 dark:bg-gray-800">
                                            <div class="absolute left-0 top-0 flex h-full items-center justify-center rounded-sm bg-main"
                                                style="width: {{ $target->percentage }}%"></div>
                                        </div>
                                        <p
                                            class="w-[220px] shrink-0 text-theme-sm font-medium text-gray-800 dark:text-white/90 whitespace-nowrap">
                                            {{ __('common.idr') }} {{ number_format($target->total_current, 0, ',', '.') }} / {{ __('common.idr') }}
                                            {{ number_format($target->target_amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
