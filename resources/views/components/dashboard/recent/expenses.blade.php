@props(['transactions'])

<div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Recent Expenses</h3>
    </div>

    <a <a href="{{ route('expense.index') }}"
    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
        See all
    </a>
</div>

<div class="max-w-full overflow-x-auto custom-scrollbar">
    <table class="min-w-full">
        @if (!$transactions->isEmpty())
            <thead>
                <tr class="border-t border-gray-100 dark:border-gray-800">
                    <th class="py-3 text-left">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Title</p>
                    </th>
                    <th class="py-3 text-left">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Amount</p>
                    </th>
                    <th class="py-3 text-left">
                        <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Account Bank</p>
                    </th>
                </tr>
            </thead>
        @endif
        <tbody>
            @if ($transactions->isEmpty())
                <tr>
                    <td colspan="3" class="py-8 text-center text-gray-500">
                        No expenses found
                    </td>
                </tr>
            @else
                @foreach($transactions as $i)
                    <tr class="border-t border-gray-100 dark:border-gray-800">
                        <td class="py-3 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                    {{ $i['title'] }}
                                </p>
                            </div>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">IDR {{ number_format($i['amount'], 0, ',', '.') }}</p>
                        </td>
                        <td class="py-3 whitespace-nowrap">
                            <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $i['account_bank'] }}</p>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>