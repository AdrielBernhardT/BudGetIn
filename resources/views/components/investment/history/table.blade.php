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
                <template x-for="(history, index) in paginatedHistories" :key="history.id">

                    <tr>
                        <!-- No -->
                        <td
                            :class="index === paginatedHistories.length - 1
                                ? 'px-4 py-4 whitespace-nowrap'
                                : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">

                            <span
                                class="text-sm text-gray-500"
                                x-text="(currentPage - 1) * itemsPerPage + index + 1">
                            </span>
                        </td>

                        <!-- Date -->
                        <td
                            :class="index === paginatedHistories.length - 1
                                ? 'px-4 py-4 whitespace-nowrap'
                                : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">

                            <span
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="formatDate(history.date)">
                            </span>
                        </td>

                        <!-- Investment -->
                        <td
                            :class="index === paginatedHistories.length - 1
                                ? 'px-4 py-4 whitespace-nowrap'
                                : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">

                            <span
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="history.investment?.name ?? '-'">
                            </span>
                        </td>

                        <!-- Goal -->
                        <td
                            :class="index === paginatedHistories.length - 1
                                ? 'px-4 py-4 whitespace-nowrap'
                                : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">

                            <span
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="history.investment?.goal?.name ?? '-'">
                            </span>
                        </td>

                        <!-- Name -->
                        <td
                            :class="index === paginatedHistories.length - 1
                                ? 'px-4 py-4 whitespace-nowrap'
                                : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">

                            <span
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="history.investment?.name ?? '-'">
                            </span>
                        </td>

                        <!-- Amount -->
                        <td
                            :class="index === paginatedHistories.length - 1
                                ? 'px-4 py-4 whitespace-nowrap'
                                : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">

                            <span
                                class="text-sm font-medium text-green-600"
                                x-text="formatRupiah(history.transaction_amount)">
                            </span>
                        </td>

                        <!-- Account -->
                        <td
                            :class="index === paginatedHistories.length - 1
                                ? 'px-4 py-4 whitespace-nowrap'
                                : 'px-4 py-4 whitespace-nowrap border-b border-gray-200 dark:border-gray-700'">

                            <span
                                class="text-sm text-gray-900 dark:text-white"
                                x-text="history.account?.name ?? '-'">
                            </span>
                        </td>

                        <!-- Description -->
                        <td
                            :class="index === paginatedHistories.length - 1
                                ? 'px-4 py-4'
                                : 'px-4 py-4 border-b border-gray-200 dark:border-gray-700'">

                            <span
                                class="text-sm text-gray-600 dark:text-gray-300"
                                x-text="history.description || '-'">
                            </span>
                        </td>
                    </tr>

                </template>

                <template x-if="paginatedHistories.length === 0">
                    <tr>
                        <td colspan="8"
                            class="py-8 text-center text-gray-500 dark:text-gray-400">
                            No investment history found.
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>