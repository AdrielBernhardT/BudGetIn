@props(['summary'])

<div class="h-full rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between">
            <div class="flex flex-col">
                <h3 class="text-gray-800 dark:text-white/90 text-theme-sm md:text-md font-normal">
                    {{ __('dashboard.current_balance') }}
                </h3>
                <h2 class="text-gray-800 dark:text-white/90 font-semibold text-lg md:text-xl">
                    {{ __('common.idr') }} {{ number_format($summary['current_balance'], 0, ',', '.') }}
                </h2>
            </div>

            <button @click="$dispatch('add-account')" class="w-auto whitespace-nowrap justify-center inline-flex items-center gap-3 rounded-lg border border-gray-300 bg-main px-4 py-2 text-theme-xs md:text-theme-sm font-medium text-white shadow-theme-xs hover:bg-main-hover hover:text-white/90 dark:border-gray-700 dark:bg-main dark:text-white dark:hover:bg-main-hover dark:hover:text-white/90">
                <i data-lucide="plus" class="w-3 h-3 md:w-4 md:h-4 shrink-0 text-white dark:text-white"></i>
                {{ __('sentence.add_new') }}
            </button>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <div class="flex gap-2 pb-2">
                @forelse($summary['accounts'] as $account)
                    <div class="min-w-56 max-w-56">
                        <x-dashboard.summary.account :account="$account"/>
                    </div>
                @empty
                    <div class="flex items-center justify-center w-full h-32">
                        <span class="text-gray-500">
                            {{ __('sentence.no_accounts_found') }}
                        </span>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- <div class="flex flex-col gap-2">
            <h3 class="text-gray-800 dark:text-white/90 text-md md:text-lg font-medium">
                Top 3 Expenses This Month
            </h3>
            <div class="grid grid-cols-3 gap-2">
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-800 md:p-4">
                    <div class="flex flex-col items-center gap-1">
                        <i data-lucide="home" class="w-6 h-6 shrink-0 text-gray-900 dark:text-white/90"></i>
                        <span class="text-gray-800 dark:text-white/90 text-theme-sm md:text-md font-normal">Home</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-800 md:p-4">
                    <div class="flex flex-col items-center gap-1">
                        <i data-lucide="home" class="w-6 h-6 shrink-0 text-gray-900 dark:text-white/90"></i>
                        <span class="text-gray-800 dark:text-white/90 text-theme-sm md:text-md font-normal">Home</span>
                    </div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-800 md:p-4">
                    <div class="flex flex-col items-center gap-1">
                        <i data-lucide="home" class="w-6 h-6 shrink-0 text-gray-900 dark:text-white/90"></i>
                        <span class="text-gray-800 dark:text-white/90 text-theme-sm md:text-md font-normal">Home</span>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="flex flex-col gap-2">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                {{ __('dashboard.quick_actions') }}
            </h3>
            <div class="grid grid-cols-3 gap-4">
            <a href="{{ route('income.index') }}"
            class="flex flex-col items-center justify-center group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-main dark:hover:bg-main p-3 dark:border-gray-800 dark:bg-gray-800 md:p-4">
                <div class="flex flex-col items-center gap-2">
                    <i data-lucide="arrow-down" class="w-7 h-7 shrink-0 text-gray-900 group-hover:text-white dark:text-white/90"></i>
                    <span class="text-gray-800 group-hover:text-white dark:text-white/90 text-theme-sm md:text-md font-normal">{{ __('nav.income') }}</span>
                </div>
            </a>

            <a href="{{ route('expense.index') }}"
            class="flex flex-col items-center justify-center group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-main dark:hover:bg-main p-3 dark:border-gray-800 dark:bg-gray-800 md:p-4">
                <div class="flex flex-col items-center gap-2">
                    <i data-lucide="arrow-up" class="w-7 h-7 shrink-0 text-gray-900 group-hover:text-white dark:text-white/90"></i>
                    <span class="text-gray-800 group-hover:text-white dark:text-white/90 text-theme-sm md:text-md font-normal">{{ __('nav.expense') }}</span>
                </div>
            </a>

            <a href="{{ route('transfer.index') }}"
            class="flex flex-col items-center justify-center group rounded-2xl border border-gray-200 bg-gray-50 hover:bg-main dark:hover:bg-main p-3 dark:border-gray-800 dark:bg-gray-800 md:p-4">
                <div class="flex flex-col items-center gap-2">
                    <i data-lucide="arrow-left-right" class="w-7 h-7 shrink-0 text-gray-900 group-hover:text-white dark:text-white/90"></i>
                    <span class="text-gray-800 group-hover:text-white dark:text-white/90 text-theme-sm md:text-md font-normal">{{ __('nav.transfer') }}</span>
                </div>
            </a>
        </div>
        </div>
    </div>
</div>

