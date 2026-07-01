<div class="overflow-hidden">
    <div class="max-w-full px-5 overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr class="border-gray-200 border-y dark:border-gray-700">
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">No</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Date</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Investment</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Goal</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Name</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Amount</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Account</th>
                    <th class="px-4 py-3 font-normal text-gray-900 dark:text-white text-start text-theme-sm">Description</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="(history, index) in paginatedHistories" :key="`${history.type ?? 'history'}-${history.id ?? index}`">
                    <tr>
                        <td :class="index === paginatedHistories.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-500 dark:text-gray-400"
                                x-text="(currentPage - 1) * itemsPerPage + index + 1">
                            </div>
                        </td>

                        <td :class="index === paginatedHistories.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="formatDate(history.date ?? history.created_at)">
                            </div>
                        </td>

                        <td :class="index === paginatedHistories.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <span
                                :class="{
                                    'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500': (history.type ?? '').toLowerCase() === 'investment',
                                    'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-500': (history.type ?? '').toLowerCase() === 'goal',
                                    'bg-orange-50 text-orange-600 dark:bg-orange-500/15 dark:text-orange-500': (history.type ?? '').toLowerCase() === 'record',
                                    'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400': !['investment', 'goal', 'record'].includes((history.type ?? '').toLowerCase())
                                }"
                                class="rounded-full px-2 py-1 text-xs font-medium"
                                x-text="history.type ?? '-'">
                            </span>
                        </td>

                        <td :class="index === paginatedHistories.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="history.goal?.name ?? history.goal_name ?? '-'">
                            </div>
                        </td>

                        <td :class="index === paginatedHistories.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm font-medium text-gray-900 dark:text-white"
                                x-text="history.title ?? history.name ?? '-'">
                            </div>
                        </td>

                        <td :class="index === paginatedHistories.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="formatRupiah(history.amount)">
                            </div>
                        </td>

                        <td :class="index === paginatedHistories.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="history.account?.name ?? history.account_name ?? '-'">
                            </div>
                        </td>

                        <td :class="index === paginatedHistories.length - 1 ? 'px-4 py-4 whitespace-nowrap' : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">
                            <div
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="history.description ?? '-'">
                            </div>
                        </td>
                    </tr>
                </template>

                <template x-if="paginatedHistories.length === 0">
                    <tr>
                        <td colspan="8" class="py-8 text-center text-gray-500 dark:text-gray-400">
                            No histories found
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>