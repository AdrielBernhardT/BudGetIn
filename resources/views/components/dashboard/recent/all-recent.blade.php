@props(['recentTransactions'])

<div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
    <div x-data="allRecent()">
        <div class="flex gap-6">
            <button @click="tab='incomes'"
                :class="tab === 'incomes' ? 'border-b-2 border-main text-main' : 'text-gray-500 dark:text-gray-400'"
                class="pb-2 font-medium transition-colors duration-300">
                Incomes
            </button>
            <button @click="tab='expenses'"
                :class="tab === 'expenses' ? 'border-b-2 border-main text-main' : 'text-gray-500 dark:text-gray-400'"
                class="pb-2 font-medium transition-colors duration-300">
                Expenses
            </button>
            <button @click="tab='transfers'"
                :class="tab === 'transfers' ? 'border-b-2 border-main text-main' : 'text-gray-500 dark:text-gray-400'"
                class="pb-2 font-medium transition-colors duration-300">
                Transfers
            </button>
        </div>

        <div class="mt-4">
            <div x-show="tab === 'incomes'" x-cloak>
                <x-dashboard.recent.incomes
                    :transactions="$recentTransactions['incomes']" />
            </div>

            <div x-show="tab === 'expenses'" x-cloak>
                <x-dashboard.recent.expenses
                    :transactions="$recentTransactions['expenses']" />
            </div>

            <div x-show="tab === 'transfers'" x-cloak>
                <x-dashboard.recent.transfers
                    :transactions="$recentTransactions['transfers']" />
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function allRecent() {
            return {
                tab: 'incomes',
            }
        }
    </script>
@endpush